<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class ReturnStatement extends AbstractNode
{
    private $booleanExpression;

    private $quantifierStatement;

    private $ruleStatement;

    public function __construct(
        array $token,
        BooleanExpression $booleanExpression,
        QuantifierStatement $quantifierStatement,
        RuleStatement $ruleStatement
    )
    {
        parent::__construct($token);
        $this->booleanExpression = $booleanExpression;
        $this->quantifierStatement = $quantifierStatement;
        $this->ruleStatement = $ruleStatement;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitReturnStatement($this);
        var_dump($this->getToken());
        $visitor->visitToken($this->getToken());
        $this->acceptExtraTokens($visitor);

        $this->booleanExpression->accept($visitor);
        $this->quantifierStatement->accept($visitor);
        $this->ruleStatement->accept($visitor);
    }
}