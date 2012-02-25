<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class RuleStatement extends AbstractNode
{
    private $expression;

    public function __construct(AbstractNode $expression)
    {
        $this->expression = $expression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitRuleStatement($this);
        $this->expression->accept($visitor);
        $this->acceptExtraTokens($visitor);
    }
}