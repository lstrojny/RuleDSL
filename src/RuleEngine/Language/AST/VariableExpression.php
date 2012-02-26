<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class VariableExpression extends AbstractNode implements ExpressionInterface
{
    private $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function accept(VisitorInterface $visitor)
    {
        foreach ($this->tokens as $token) {
            $visitor->visitToken($token);
        }
        $this->acceptDecoratingTokens($visitor);
    }
}