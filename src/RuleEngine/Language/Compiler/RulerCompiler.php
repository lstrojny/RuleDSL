<?php
namespace RuleEngine\Language\Compiler;

use RuleEngine\Language\AST\Visitor\VisitorInterface;
use RuleEngine\Language\AST;
use Ruler\Rule;
use Ruler\Operator;
use Ruler\Variable;
use Ruler\Value;
use Functional as F;

class PropertyVariable extends Variable
{
    private $propertyPath;

    public function __construct(array $propertyPath, $value = null)
    {
        parent::__construct(join('->', $propertyPath), $value);
        $this->propertyPath = $propertyPath;
    }

    public function prepareValue(\Ruler\Context $context)
    {
        $value = $context;
        foreach ($this->propertyPath as $path) {
            $value = $this->resolve($value, $path);
            if ($value === null) {
                break;
            }
        }
        $value = $value === null ? $this->getValue() : $value;

        return $value instanceof Value ? $value : new Value($value);
    }

    private function resolve($object, $property)
    {
        if (is_array($object) || $object instanceof \ArrayAccess && isset($object[$property])) {
            return $object[$property];
        }
        if (is_object($object) && isset($object->{$property})) {
            return $object->{$property};
        }
    }
}

class RulerCompiler implements VisitorInterface
{
    use ContextSensitiveCompilerTrait;

    private $quantifier;

    private $conditions = [];

    private $nextCondition;

    private $variablePosition = 0;

    private $propertyPosition = 0;

    private $returnValue;

    private $scope = null;

    private function visitBooleanExpressionInReturnContext(AST\BooleanExpression $booleanExpression)
    {
        $this->returnValue = $this->getBooleanValue($booleanExpression);
    }

    private function visitQuantifierStatementInQuantifierContext(AST\QuantifierStatement $quantifierExpression)
    {
        $this->quantifier = $quantifierExpression->getToken()['value'] === 'ALL';
    }

    private function visitIfStatementInRuleContext(AST\IfStatement $ifStatement)
    {
        if ($this->nextCondition) {
            $this->conditions[] = $this->nextCondition;
            $this->variablePosition = 0;
        }

        $this->nextCondition = [
            'negate' => false,
            'values' => [['booleanTrue', null], ['booleanTrue', null]],
        ];
    }

    private function visitVariableExpressionInRuleContext(AST\VariableExpression $variableExpression)
    {
        $variableName = join('', F\pluck($variableExpression->getToken(), 'value'));
        $this->nextCondition['values'][$this->variablePosition++] = [$variableName, $this->scope];
    }

    private function visitPropertyExpressionInRuleAndPropertyContext(AST\PropertyExpression $propertyExpression)
    {
        if ($this->propertyPosition > 1) {
            $this->propertyPosition = 0;
        }
    }

    private function visitVariableExpressionInRuleAndPropertyContext(AST\VariableExpression $variableExpression)
    {
        $variableName = join('', F\pluck($variableExpression->getToken(), 'value'));
        $this->nextCondition['values'][$this->variablePosition][$this->propertyPosition++] = $variableName;
    }

    private function visitNumericExpressionInReturnContext(AST\NumericExpression $numericExpression)
    {
        $this->returnValue = $this->getNumericValue($numericExpression);
    }

    private function visitNegateExpressionInRuleContext(AST\NegateExpression $negateExpression)
    {
        $this->nextCondition['negate'] = true;
    }

    private function getNumericValue(AST\NumericExpression $numericExpression)
    {
        $multiplier = $numericExpression->getAlgebraicSignToken()['value'] === '-' ? -1 : 1;

        return $multiplier * $numericExpression->getToken()['value'];
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        if ($this->nextCondition) {
            $this->conditions[] = $this->nextCondition;
        }

        $conditions = [];
        foreach ($this->conditions as $condition) {
            $left = $this->getVariable($condition['values'][0]);
            $right = $this->getVariable($condition['values'][1]);
            $operator = $this->getOperator(!$condition['negate'], $left, $right);
            $conditions[] = $operator;
        }

        $condition = $this->getLogicalOperator($this->quantifier, $conditions);

        $returnValue = $this->returnValue;
        return new Rule(
            $condition,
            static function() use ($returnValue) {
                return $returnValue;
            }
        );
    }

    private function getBooleanValue(AST\BooleanExpression $booleanExpression)
    {
        return strtolower($booleanExpression->getToken()['value']) === 'true';
    }

    private function getOperator($value, $left, $right)
    {
        switch ($value) {
            case true:
                return new Operator\EqualTo($left, $right);

            case false:
                return new Operator\NotEqualTo($left, $right);
        }
    }

    private function getLogicalOperator($value, array $operators)
    {
        switch ($value) {
            case true:
                return new Operator\LogicalAnd($operators);

            case false:
                return new Operator\LogicalOr($operators);
        }
    }

    private function getVariable($variable)
    {
        if ($variable[1] !== null) {
            return new PropertyVariable(array_reverse($variable));
        }

        list($variableName) = $variable;
        switch ($variableName) {
            case 'booleanTrue':
                return new Variable('booleanTrue', true);

            case 'booleanFalse':
                return new Variable('booleanFalse', false);

            default:
                return new Variable($variableName);
        }
    }
}