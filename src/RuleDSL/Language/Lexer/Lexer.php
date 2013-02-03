<?php
namespace RuleDSL\Language\Lexer;

use ReflectionClass;

class Lexer
{
    private $string;

    private $line = 1;

    private $grammar;

    public function __construct($string, Grammar $grammar)
    {
        $this->string = $string;
        $this->grammar = $grammar;
    }

    public function scan()
    {
        $tokens = [];

        $regex = <<<'EOS'
        /(?:
            (\s)                        # Single space
            |
            ([\-\+])?                   # Plus, minus (algebraic signs)
            (
                (?:                     # Avoid invalid numbers like 001 (treat as string)
                    [1-9][0-9]*         # Multi digit number starts always with 1-9
                    |
                    0(?=^0)             # Single digit number may start with 0 (but not followed by another 0)
                )
                (?:\.\d+)?              # Optional fraction
            )
            |
            ([^\s]+)                    # Everything not seperated by spaces
            )
        /x
EOS;

        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE;
        $matches = preg_split($regex, $this->string, -1, $flags);

        foreach ($matches as $match) {
            $tokens[] = $this->getToken($match);
        }

        $lastToken = end($tokens) ?: ['end' => 0];
        $tokens[] = [
            'value' => '',
            'line'  => $this->line,
            'type'  => Grammar::T_END,
            'start' => $lastToken['end'],
            'end'   => $lastToken['end'],
        ];

        return new TokenStream($tokens, $this->grammar);
    }

    public function getToken(array $match)
    {
        return [
            'value' => $match[0],
            'line'  => $this->line,
            'type'  => $this->grammar->getValueToken($match[0], $this->line),
            'start' => $match[1],
            'end'   => $match[1] + mb_strlen($match[0], 'UTF-8'),
        ];
    }
}
