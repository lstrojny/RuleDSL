<?php
namespace RuleEngine\Language\Token;

class IntegerToken extends AbstractToken
{
    public function __construct($value)
    {
        parent::__construct((integer) $value);
    }
}
