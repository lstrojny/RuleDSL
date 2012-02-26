<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class RuleStatement extends AbstractNode
{
    private $ifStatement;

    private $expression;

    public function __construct(IfStatement $ifStatement, GenericExpression $expression)
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