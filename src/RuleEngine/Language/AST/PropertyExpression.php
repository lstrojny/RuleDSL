<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

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
        parent::accept($visitor);
        $this->variableExpression->accept($visitor);
    }
}