<?php
namespace RuleDSL\Language\AST\Visitor;

interface VisitableInterface
{
    public function accept(VisitorInterface $visitor);
}
