<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class GenericExpression extends AbstractNode
{
    private $expression;

    public function __construct(AbstractNode $expression)
    {
        $this->expression = $expression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitGenericExpression($this);
        $this->acceptDecoratingTokens($visitor);
        $this->expression->accept($visitor);
    }
}