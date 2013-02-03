<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class ReturnStatement extends AbstractNode
{
    private $valueExpression;

    private $quantifierStatement;

    private $ruleStatement;

    public function __construct(
        array $token,
        ExpressionInterface $valueExpression,
        QuantifierStatement $quantifierStatement,
        RuleStatement $ruleStatement
    )
    {
        parent::__construct($token);
        $this->valueExpression = $valueExpression;
        $this->quantifierStatement = $quantifierStatement;
        $this->ruleStatement = $ruleStatement;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitReturnStatement($this);
        $visitor->visitToken($this->getToken());
        $this->acceptDecoratingTokens($visitor);

        $this->valueExpression->accept($visitor);
        $this->quantifierStatement->accept($visitor);
        $this->ruleStatement->accept($visitor);
    }
}