<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class QuantifierStatement extends AbstractNode
{
    private $ifStatement;

    public function __construct(array $token, IfStatement $ifStatement)
    {
        parent::__construct($token);
        $this->ifStatement = $ifStatement;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitQuantifierStatement($this);
        $this->ifStatement->accept($visitor);
        $visitor->visitToken($this->getToken());
        $this->acceptExtraTokens($visitor);
    }
}