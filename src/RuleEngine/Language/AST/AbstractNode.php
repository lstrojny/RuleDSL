<?php
namespace RuleEngine\Language\AST;

abstract class AbstractNode
{
    private $token;

    public function __construct(array $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}