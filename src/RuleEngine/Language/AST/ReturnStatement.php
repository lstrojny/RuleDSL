<?php
namespace RuleEngine\Language\AST;

class ReturnStatement extends AbstractNode
{
    private $booleanExpression;

    private $quantifierExpression;

    private $ruleStatement;

    public function __construct(array $token, BooleanExpression $booleanExpression, QuantifierExpression $quantifierExpression, RuleStatement $ruleStatement)
    {
        parent::__construct($token);
        $this->booleanExpression = $booleanExpression;
        $this->quantifierExpression = $quantifierExpression;
        $this->ruleStatement = $ruleStatement;
    }

    public function getBooleanExpression()
    {
        return $this->booleanExpression;
    }

    public function getQuantifierExpression()
    {
        return $this->quantifierExpression;
    }

    public function getRuleStatement()
    {
        return $this->ruleStatement;
    }
}