<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class NumericExpression extends AbstractNode implements ExpressionInterface
{
    private $algebraicSignToken;

    public function __construct(array $token, array $algebraicSignToken = null)
    {
        parent::__construct($token);
        $this->algebraicSignToken = $algebraicSignToken;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitNumericExpression($this);
        if ($this->algebraicSignToken) {
            $visitor->visitToken($this->algebraicSignToken);
        }
        $visitor->visitToken($this->getToken());
        $this->acceptDecoratingTokens($visitor);
    }
}