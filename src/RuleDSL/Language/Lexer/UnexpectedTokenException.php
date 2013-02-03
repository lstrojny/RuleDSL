<?php
namespace RuleDSL\Language\Lexer;

use RuntimeException;

class UnexpectedTokenException extends RuntimeException
{
    public function __construct($expectedToken, $currentValue, $currentType, $line, $start, $end, $near)
    {
        parent::__construct(
            sprintf(
                'Expected %s, got "%s" (%s) at position %d - %d, line %d near "%s"',
                join(', ', $expectedToken),
                $currentValue,
                $currentType,
                $line,
                $start,
                $end,
                $near
            )
        );
    }
}