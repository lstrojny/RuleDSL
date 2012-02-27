<?php
namespace RuleEngine\Engine;

class RuleContext
{
    private $scope;

    public function __construct(array $scope)
    {
        $this->scope = $scope;
    }

    public function lookup($variableName)
    {
        return $this->scope[$variableName];
    }
}