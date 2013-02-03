<?php
namespace RuleEngine\Language\Lexer;

interface GrammarInterface
{
    const T_WHITESPACE = 0b00000000000000001;
    const T_END        = 0b00000000000000010;
    const T_STRING     = 0b00000000000000100;
    const T_RETURN     = 0b00000000000001100;
    const T_BOOLEAN    = 0b00000000000010100;
    const T_QUANTIFIER = 0b00000000000100100;
    const T_MATCH      = 0b00000000001000100;
    const T_IF         = 0b00000000010000100;
    const T_OF         = 0b00000000100000100;
    const T_IS         = 0b00000001000000100;
    const T_RULE       = 0b00000010000000100;
    const T_NUMBER     = 0b00000100000000000;
    const T_PLUS       = 0b00001000000000000;
    const T_MINUS      = 0b00010000000000000;
    const T_NEGATE     = 0b00100000000000000;

    public function getTokenName($token);

    public function getValueToken($value, &$line);
}