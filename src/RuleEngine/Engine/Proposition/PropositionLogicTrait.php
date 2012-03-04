<?php
namespace RuleEngine\Engine\Proposition;

use BadMethodCallException;

trait PropositionLogicTrait
{
    private $pendingBooleanOperator;

    public function __call($method, $arguments)
    {
        switch ($method) {
            case 'and':
                return $this->logicalAnd($this->prepareArgument($arguments));
                break;

            case 'or':
                return $this->logicalOr($this->prepareArgument($arguments));
                break;

            case 'xor':
                return $this->logicalXor($this->prepareArgument($arguments));
                break;

            case 'not':
                return $this->negate($this->__call($this->pendingBooleanOperator, $arguments));
                return;

            default:
                throw new BadMethodCallException(
                    sprintf('Invalid method "%s()" called', $method)
                );
        }
    }

    private function prepareArgument(array $arguments)
    {
        return isset($arguments[0]) ? $arguments[0] : null;
    }

    private function logicalAnd(PropositionInterface $next = null)
    {
        if ($next) {
            return new AndProposition($this, $next);
        }

        $this->pendingBooleanOperator = 'and';
        return $this;
    }

    private function logicalOr(PropositionInterface $next = null)
    {
        if ($next) {
            return new OrProposition($this, $next);
        }

        $this->pendingBooleanOperator = 'or';
        return $this;
    }

    private function logicalXor(PropositionInterface $next = null)
    {
        if ($next) {
            return new XorProposition($this, $next);
        }

        $this->pendingBooleanOperator = 'xor';
        return $this;
    }

    private function negate(PropositionInterface $next)
    {
        return new NotProposition($next);
    }
}