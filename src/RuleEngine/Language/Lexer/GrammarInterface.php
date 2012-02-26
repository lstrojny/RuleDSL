<?php
namespace RuleEngine\Language\Lexer;

interface GrammarInterface
{
    const T_WHITESPACE = 0b000000000001;
    const T_END        = 0b000000000010;
    const T_STRING     = 0b000000000100;
    const T_RETURN     = 0b000000001100;
    const T_BOOLEAN    = 0b000000010100;
    const T_QUANTIFIER = 0b000000100100;
    const T_MATCH      = 0b000001000100;
    const T_IF         = 0b000010000100;
    const T_OF         = 0b000100000100;
    const T_IS         = 0b001000000100;
    const T_RULE       = 0b010000000100;

    public function getTokenName($token);

    public function getValueToken($value, &$line);
}