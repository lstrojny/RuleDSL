<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Context\ContextInterface;

class OrProposition extends AbstractChainedProposition
{
    public function evaluate(ContextInterface $context)
    {
        return $this->leftProposition->evaluate($context) || $this->rightProposition->evaluate($context);
    }
}
