<?php
namespace RuleEngine\Engine;

use RuleEngine\Engine\Value\AbstractValue;

class Proposition
{
    private $left;

    private $right;

    private $operator;

    public function __construct(AbstractValue $left, Operator $operator, AbstractValue $right)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }

    public function evaluate(RuleContext $context)
    {
        return $this->operator->evaluate($this->left, $this->right);
    }
}