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

        if (is_array($this->scope[$variableName])) {
            return new static($this->scope[$variableName]);
        }

        if (is_object($this->scope[$variableName])) {
            return new static(get_object_vars($this->scope[$variableName]));
        }

        return $this->scope[$variableName];
    }
}