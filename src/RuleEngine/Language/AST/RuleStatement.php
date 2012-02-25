<?php
namespace RuleEngine\Language\AST;

class RuleStatement extends AbstractNode
{
    private $booleanExpression;

    public function __construct(BooleanExpression $booleanExpression)
    {
        $this->booleanExpression = $booleanExpression;
    }
}