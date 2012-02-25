<?php
namespace RuleEngine\Language\AST\Visitor;

interface VisitableInterface
{
    public function accept(VisitorInterface $visitor);
}
