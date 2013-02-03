<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class RuleStatement extends AbstractNode
{
    private $ifStatement;

    private $expression;

    public function __construct(IfStatement $ifStatement, ExpressionInterface $expression)
    {
        $this->ifStatement = $ifStatement;
        $this->expression = $expression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitRuleStatement($this);
        $this->ifStatement->accept($visitor);
        $this->expression->accept($visitor);
    }
}