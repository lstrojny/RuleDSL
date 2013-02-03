<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class PropertyExpression extends VariableExpression
{
    private $variableExpression;

    public function __construct(array $tokens, VariableExpression $variableExpression)
    {
        parent::__construct($tokens);
        $this->variableExpression = $variableExpression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitPropertyExpression($this);
        parent::accept($visitor);
        $this->variableExpression->accept($visitor);
    }
}