<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class VariableExpression extends AbstractNode
{
    private $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function accept(VisitorInterface $visitor)
    {
    }
}