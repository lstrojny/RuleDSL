<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class VariableExpression extends AbstractNode implements ExpressionInterface
{
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitVariableExpression($this);
        foreach ($this->getToken() as $token) {
            $visitor->visitToken($token);
        }
        $this->acceptDecoratingTokens($visitor);
    }
}