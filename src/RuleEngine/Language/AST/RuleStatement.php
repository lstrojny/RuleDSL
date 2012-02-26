<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class RuleStatement extends AbstractNode
{
    private $expression;

    public function __construct(array $token, AbstractNode $expression)
    {
        parent::__construct($token);
        $this->expression = $expression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitToken($this->getToken());
        $this->acceptExtraTokens($visitor);
        $visitor->visitRuleStatement($this);
        $this->expression->accept($visitor);
    }
}