<?php
namespace RuleEngine\Engine;

class Proposition
{
    private $left;

    private $right;

    private $operator;

    private $evaluationChain;

    public function __construct(Value\ValueInterface $left, Operator $operator, Value\ValueInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
        $this->evaluationChain = function(Context\ContextInterface $context) {
            return $this->operator->evaluate(
                $this->determineFixedValue($this->left, $context),
                $this->determineFixedValue($this->right, $context)
            );
        };
    }

    public function logicalAnd(Proposition $next)
    {
        $previous = $this->evaluationChain;
        $this->evaluationChain = function(Context\ContextInterface $context) use ($previous, $next) {
            return $previous($context) && $next->evaluate($context);
        };

        return $this;
    }

    public function logicalOr(Proposition $next)
    {
        $previous = $this->evaluationChain;
        $this->evaluationChain = function(Context\ContextInterface $context) use ($previous, $next) {
            return $previous($context) || $next->evaluate($context);
        };

        return $this;
    }

    public function logicalXor(Proposition $next)
    {
        $previous = $this->evaluationChain;
        $this->evaluationChain = function(Context\ContextInterface $context) use ($previous, $next) {
            return $previous($context) xor $next->evaluate($context);
        };

        return $this;
    }

    public function evaluate(Context\ContextInterface $context)
    {
        $chain = $this->evaluationChain;
        return $chain($context);
    }

    private function determineFixedValue(Value\ValueInterface $value, Context\ContextInterface $context)
    {
        while (!$value instanceof Value\FixedValueInterface) {
            $value = $value->getValue($context);
        }

        return $value;
    }
}