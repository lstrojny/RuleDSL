<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitableInterface;
use RuleEngine\Language\AST\Visitor\VisitorInterface;

abstract class AbstractNode implements VisitableInterface
{
    private $token;

    private $extraTokens = [];

    public function __construct(array $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function addExtraToken(array $token)
    {
        $this->extraTokens[] = $token;
    }

    public function addExtraTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->addExtraToken($token);
        }
    }

    public function getExtraTokens()
    {
        return $this->extraTokens;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitToken($this->token);
        $this->acceptExtraTokens($visitor);
    }

    protected function acceptExtraTokens(VisitorInterface $visitor)
    {
        foreach ($this->extraTokens as $token) {
            $visitor->visitExtraToken($token);
        }
    }
}