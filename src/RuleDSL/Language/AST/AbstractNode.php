<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitableInterface;
use RuleDSL\Language\AST\Visitor\VisitorInterface;

abstract class AbstractNode implements VisitableInterface
{
    private $token;

    private $decoratingTokens = [];

    public function __construct(array $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function addDecoratingToken(array $token)
    {
        $this->decoratingTokens[] = $token;
    }

    public function addDecoratingTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->addDecoratingToken($token);
        }
    }

    public function getDecoratingTokens()
    {
        return $this->decoratingTokens;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitToken($this->token);
        $this->acceptDecoratingTokens($visitor);
    }

    protected function acceptDecoratingTokens(VisitorInterface $visitor)
    {
        foreach ($this->decoratingTokens as $token) {
            $visitor->visitDecoratingToken($token);
        }
    }
}