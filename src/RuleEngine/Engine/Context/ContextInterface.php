<?php
namespace RuleEngine\Engine\Context;

interface ContextInterface
{
    public function lookup($expression);
}