<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Context\ContextInterface;

class XorProposition extends AbstractChainedProposition
{
    public function evaluate(ContextInterface $context)
    {
        return $this->leftProposition->evaluate($context) xor $this->rightProposition->evaluate($context);
    }
}
