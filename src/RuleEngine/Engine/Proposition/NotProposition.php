<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Context\ContextInterface;


class NotProposition
{
    private $wrapped;

    public function __construct(PropositionInterface $proposition)
    {
        $this->wrapped = $proposition;
    }

    public function evaluate(ContextInterface $context)
    {
        return !$this->wrapped->evaluate($context);
    }
}