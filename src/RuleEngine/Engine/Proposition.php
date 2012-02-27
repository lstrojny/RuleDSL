<?php
namespace RuleEngine\Engine;

class Proposition
{
    private $left;

    private $right;

    private $operator;

    public function __construct(Value\ValueInterface $left, Operator $operator, Value\ValueInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }

    public function evaluate(Context\ContextInterface $context)
    {
        return $this->operator->evaluate(
            $this->determineValue($this->left, $context),
            $this->determineValue($this->right, $context)
        );
    }

    private function determineValue(Value\ValueInterface $value, Context\ContextInterface $context)
    {
        while (!$value instanceof Value\FixedValueInterface) {
            $value = $value->getValue($context);
        }

        return $value;
    }
}