<?php
namespace RuleEngine\Language\Parser;

use RuntimeException;
use RuleEngine\Language\Lexer\Lexer;

class InvalidSyntaxException extends RuntimeException
{
    public function __construct($expectedToken, $value, $line, $start, $end, $near)
    {
        parent::__construct(
            sprintf(
                'Expected "%s", got "%s" at position %d - %d, line %d near "%s"',
                join('", "', $expectedToken),
                $value,
                $line,
                $start,
                $end,
                $near
            )
        );
    }
}