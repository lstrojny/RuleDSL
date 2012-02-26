<?php
namespace RuleEngine\Language\AST\Visitor;

use RuleEngine\Language\AST;

interface VisitorInterface
{
    public function visitRootNode(AST\RootNode $rootNode);
    public function visitReturnStatement(AST\ReturnStatement $returnStatement);
    public function visitBooleanExpression(AST\BooleanExpression $booleanExpression);
    public function visitQuantifierStatement(AST\QuantifierStatement $quantifierExpression);
    public function visitIfStatement(AST\IfStatement $ifStatement);
    public function visitRuleStatement(AST\RuleStatement $ruleStatement);
    public function visitVariableExpression(AST\VariableExpression $variableExpression);
    public function visitNumericExpression(AST\NumericExpression $numericExpression);
    public function visitToken(array $token);
    public function visitDecoratingToken(array $token);
}