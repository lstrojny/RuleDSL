<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Value\ValueInterface;
use RuleEngine\Engine\Operator;
use RuleEngine\Engine\Context\ContextInterface;
use RuleEngine\Engine\Value\FixedValueInterface;

class Proposition implements PropositionInterface
{
    use PropositionLogicTrait;

    private $left;

    private $right;

    private $operator;

    public function __construct(ValueInterface $left, Operator $operator, ValueInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }

    public function evaluate(ContextInterface $context)
    {
        return $this->operator->evaluate(
            $this->determineFixedValue($this->left, $context),
            $this->determineFixedValue($this->right, $context)
        );
    }

    private function determineFixedValue(ValueInterface $value, ContextInterface $context)
    {
        while (!$value instanceof FixedValueInterface) {
            $value = $value->getValue($context);
        }

        return $value;
    }
}