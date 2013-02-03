<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class BooleanExpression extends AbstractNode implements ExpressionInterface
{
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitBooleanExpression($this);
        parent::accept($visitor);
    }
}