<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\RuleContext;

interface ValueInterface
{
    public function getValue(RuleContext $context);
    public function getName();
}