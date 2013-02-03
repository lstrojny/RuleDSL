<?php
namespace RuleEngine\Language\AST;

use RuleEngine\Language\AST\Visitor\VisitorInterface;

class NegateExpression extends AbstractNode implements ExpressionInterface
{
    private $nestedExpression;

    public function __construct(array $token, ExpressionInterface $nestedExpression)
    {
        parent::__construct($token);
        $this->nestedExpression = $nestedExpression;
    }

    public function getNestedExpression()
    {
        return $this->nestedExpression;
    }

    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitNegateExpression($this);
        $this->nestedExpression->accept($visitor);
        parent::accept($visitor);
    }
}