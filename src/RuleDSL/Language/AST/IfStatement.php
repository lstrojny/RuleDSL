<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class IfStatement extends AbstractNode
{
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitIfStatement($this);
        parent::accept($visitor);
    }
}