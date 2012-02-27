<?php
namespace RuleEngine\Engine\Context;

use OutOfBoundsException;

class SimpleContext implements ContextInterface
{
    private $scope;

    public function __construct(array $scope)
    {
        $this->scope = $scope;
    }

    public function lookup($variableName)
    {
        if (!isset($this->scope[$variableName])) {
            throw new OutOfBoundsException(
                sprintf('Invalid variable "%s"', $variableName)
            );
        }

        return $this->scope[$variableName];
    }
}