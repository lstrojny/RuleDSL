<?php
namespace RuleEngine\Language\Compiler;

use RuleEngine\Language\AST\Visitor\VisitorInterface;
use RuleEngine\Language\AST;

class Printer implements VisitorInterface
{
    private $string = '';

    public function __toString()
    {
        return $this->string;
    }

    public function visitToken(array $token)
    {
        $this->string .= $token['value'];
    }

    public function visitExtraToken(array $token)
    {
        $this->string .= $token['value'];
    }

    public function visitQuantifierStatement(AST\QuantifierStatement $quantifierStatement)
    {
    }

    public function visitReturnStatement(AST\ReturnStatement $returnStatement)
    {
    }

    public function visitRootNode(AST\RootNode $rootNode)
    {
    }

    public function visitRuleStatement(AST\RuleStatement $ruleStatement)
    {
    }

    public function visitBooleanExpression(AST\BooleanExpression $booleanExpression)
    {
    }

    public function visitVariableExpression(AST\VariableExpression $expression)
    {
    }

    public function visitIfStatement(AST\IfStatement $ifStatement)
    {
    }
}