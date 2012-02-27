<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\Context\ContextInterface;

interface ValueInterface
{
    public function getValue(ContextInterface $context);
    public function getName();
}