<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class BooleanExpression extends AbstractNode
{
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitBooleanExpression($this);
        parent::accept($visitor);
    }
}