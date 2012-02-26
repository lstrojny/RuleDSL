<?php
namespace RuleEngine\Language\Lexer;

use ReflectionObject;

class Grammar implements GrammarInterface
{
    private $valueTable = [];

    public function __construct()
    {
        $class = new ReflectionObject($this);
        foreach ($class->getConstants() as $name => $token) {
            $this->valueTable[$token] = $name;
        }
    }

    public function getTokenName($token)
    {
        return $this->valueTable[$token];
    }

    public function getValueToken($value, &$line)
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
                ++$line;
                break;

            default:
                $type = self::T_STRING;
                break;
        }

        return $type;
    }
}