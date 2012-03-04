<?php
namespace RuleEngine\Engine\Proposition;

abstract class AbstractChainedProposition implements PropositionInterface
{
    use PropositionLogicTrait;

    protected $leftProposition;

    protected $rightProposition;

    public function __construct(PropositionInterface $leftProposition, PropositionInterface $rightProposition)
    {
        $this->leftProposition = $leftProposition;
        $this->rightProposition = $rightProposition;
    }

    public function getLeftProposition()
    {
        return $this->leftProposition;
    }

    public function getRightProposition()
    {
        return $this->rightProposition;
    }
}