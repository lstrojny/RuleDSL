<?php
namespace RuleEngine\Language\Lexer;

use ReflectionClass;

class Lexer
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

    private $string;

    private $line = 1;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public static function getTokenName($tokenValue)
    {
        $class = new ReflectionClass(get_called_class());
        foreach ($class->getConstants() as $name => $value) {
            if ($value === $tokenValue) {
                return $name;
            }
        }
    }

    public function scan()
    {
        $tokens = [];

        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE;
        $matches = preg_split('/(\s|[^\s]+)/', $this->string, -1, $flags);

        foreach ($matches as $match) {
            $tokens[] = $this->getToken($match);
        }

        $lastToken = end($tokens) ?: array('end' => 0);
        $tokens[] = array(
            'value' => '',
            'line'  => $this->line,
            'type'  => self::T_END,
            'start' => $lastToken['end'],
            'end' => $lastToken['end'],
        );

        return $tokens;
    }

    public function getToken(array $match)
    {
        return [
            'value' => $match[0],
            'line'  => $this->line,
            'type'  => $this->getType($match[0]),
            'start' => $match[1],
            'end'   => $match[1] + mb_strlen($match[0], 'UTF-8'),
        ];
    }

    private function getType($value)
    {
        switch ($value) {
            case 'TRUE':
            case 'FALSE':
                $type = self::T_BOOLEAN;
                break;

            case 'RETURN':
                $type = self::T_RETURN;
                break;

            case 'ALL':
            case 'ANY':
                $type = self::T_QUANTIFIER;
                break;

            case 'MATCH':
                $type = self::T_MATCH;
                break;

            case ' ':
                $type = self::T_WHITESPACE;
                break;

            case 'IF':
            case 'WHEN':
                $type = self::T_IF;
                break;

            case 'OF':
                $type = self::T_OF;
                break;

            case 'IS':
                $type = self::T_IS;
                break;

            case "\n":
                $type = self::T_WHITESPACE;
                ++$this->line;
                break;

            default:
                $type = self::T_STRING;
                break;
        }

        return $type;
    }
}