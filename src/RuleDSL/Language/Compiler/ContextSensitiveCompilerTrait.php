<?php
namespace RuleDSL\Language\Compiler;

use RuleDSL\Language\AST;

trait ContextSensitiveCompilerTrait
{
    private $context = [];

    public function visitRootNode(AST\RootNode $rootNode)
    {
        $this->context = ['root'];
        $this->dispatchForContext(__FUNCTION__, $rootNode);
    }

    public function visitReturnStatement(AST\ReturnStatement $returnStatement)
    {
        $this->context = ['return'];
        $this->dispatchForContext(__FUNCTION__, $returnStatement);
    }

    public function visitReturnStatementInReturnContext(AST\ReturnStatement $returnStatement)
    {
    }

    public function visitBooleanExpression(AST\BooleanExpression $booleanExpression)
    {
        $this->dispatchForContext(__FUNCTION__, $booleanExpression);
    }

    private function visitBooleanExpressionInReturnContext(AST\BooleanExpression $booleanExpression)
    {
    }

    public function visitQuantifierStatement(AST\QuantifierStatement $quantifierStatement)
    {
        $this->context = ['quantifier'];
        $this->dispatchForContext(__FUNCTION__, $quantifierStatement);
    }

    private function visitQuantifierStatementInQuantifierContext(AST\QuantifierStatement $quantifierStatement)
    {
    }

    public function visitIfStatement(AST\IfStatement $ifStatement)
    {
        $this->dispatchForContext(__FUNCTION__, $ifStatement);
    }

    private function visitIfStatementInQuantifierContext(AST\IfStatement $ifStatement)
    {
    }

    private function visitIfStatementInRuleContext(AST\IfStatement $ifStatement)
    {
    }

    public function visitRuleStatement(AST\RuleStatement $ruleStatement)
    {
        $this->context = ['rule'];
        $this->dispatchForContext(__FUNCTION__, $ruleStatement);
    }

    private function visitRuleStatementInRuleContext(AST\RuleStatement $ruleStatement)
    {
    }

    public function visitVariableExpression(AST\VariableExpression $variableExpression)
    {
        $this->dispatchForContext(__FUNCTION__, $variableExpression);
    }

    private function visitVariableExpressionInRuleContext(AST\VariableExpression $variableExpression)
    {
    }

    private function visitVariableExpressionInRuleAndPropertyContext(AST\VariableExpression $variableExpression)
    {
    }

    public function visitPropertyExpression(AST\PropertyExpression $propertyExpression)
    {
        $this->dispatchForContext(__FUNCTION__, $propertyExpression);
    }

    private function visitPropertyExpressionInRuleContext(AST\PropertyExpression $propertyExpression)
    {
        $this->context[] = 'property';
        $this->dispatchForContext('visitPropertyExpression', $propertyExpression);
    }

    private function visitPropertyExpressionInRuleAndPropertyContext(AST\PropertyExpression $propertyExpression)
    {
    }

    public function visitNumericExpression(AST\NumericExpression $numericExpression)
    {
        $this->dispatchForContext(__FUNCTION__, $numericExpression);
    }

    private function visitNumericExpressionInReturnContext(AST\NumericExpression $numericExpression)
    {
    }

    public function visitToken(array $token)
    {
        $this->dispatchForContext(__FUNCTION__, $token);
    }

    private function visitTokenInReturnContext(array $token)
    {
    }

    private function visitTokenInQuantifierContext(array $token)
    {
    }

    private function visitTokenInRuleContext(array $token)
    {
    }

    private function visitTokenInRuleAndPropertyContext(array $token)
    {
    }

    public function visitDecoratingToken(array $token)
    {
        $this->dispatchForContext(__FUNCTION__, $token);
    }

    private function visitDecoratingTokenInReturnContext(array $token)
    {
    }

    private function visitDecoratingTokenInQuantifierContext(array $token)
    {
    }

    private function visitDecoratingTokenInRuleContext(array $token)
    {
    }

    private function visitDecoratingTokenInRuleAndPropertyContext(array $token)
    {
    }

    public function visitNegateExpression(AST\NegateExpression $negateExpression)
    {
        $this->dispatchForContext(__FUNCTION__, $negateExpression);
    }

    private function dispatchForContext($methodName, $argument)
    {
        $context = array_map('ucfirst', $this->context);
        $methodName = $methodName . 'In' . join('And', $context) . 'Context';

        $this->{$methodName}($argument);
    }
}
