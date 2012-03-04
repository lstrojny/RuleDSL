<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Context\ContextInterface;

interface PropositionInterface
{
    /**
     * public function and(PropositionInterface $proposition);
     * public function or(PropositionInterface $proposition);
     * public function xor(PropositionInterface $proposition);
     * public function not(PropositionInterface $proposition);
     */

    public function evaluate(ContextInterface $context);
}