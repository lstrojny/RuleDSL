<?php
namespace RuleDSL\Language\Lexer;

use ReflectionClass;

class GrammarTest extends \PHPUnit_Framework_TestCase
{
    public function provideConstants()
    {
        $class = new ReflectionClass('RuleDSL\Language\Lexer\Grammar');

        $args = [];
        foreach ($class->getConstants() as $constantName => $constantValue) {
            $args[] = [$constantName, $constantValue];
        }

        return $args;
    }

    /**
     * @dataProvider provideConstants
     */
    public function testGetSymbolName($constantName, $constantValue)
    {
        $grammar = new Grammar();
        $val = constant('RuleDSL\Language\Lexer\Grammar::' . $constantName);
        $this->assertSame($constantName, $grammar->getTokenName($val));
        $this->assertSame($val, $constantValue);
    }

    public function testGrammar()
    {
        $this->assertFalse(
            Grammar::T_STRING === (Grammar::T_WHITESPACE & Grammar::T_STRING)
        );
    }

    public function specializedStringTokens()
    {
        return [
            [Grammar::T_STRING],
            [Grammar::T_RETURN],
            [Grammar::T_BOOLEAN],
            [Grammar::T_QUANTIFIER],
            [Grammar::T_MATCH],
            [Grammar::T_IF],
            [Grammar::T_OF],
            [Grammar::T_IS],
        ];
    }

    /**
     * @dataProvider specializedStringTokens
     */
    public function testSpecializedStringTokens($token)
    {
        $this->assertTrue(
            Grammar::T_STRING === (Grammar::T_STRING & $token)
        );
    }
}