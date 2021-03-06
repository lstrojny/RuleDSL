<?php
namespace RuleDSL\Language\Parser;

use RuleDSL\Language\Lexer\Lexer;
use RuleDSL\Language\Compiler\Printer;
use RuleDSL\Language\Lexer\Grammar;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->printer = new Printer();
    }

    public function test_T_RETURN()
    {
        $grammar = new Grammar();
        $lexer = new Lexer(' FOO BAR BAZ BLA GNARF WTF', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_RETURN, got "FOO" (T_STRING) at position 1 - 4, line 1 near " FOO BAR BAZ BLA"'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FOO', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_NUMBER, T_MINUS, T_PLUS, T_BOOLEAN, got "FOO" (T_STRING) at position 7 - 10, line 1 near "RETURN FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE FOO', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_IF, got "FOO" (T_STRING) at position 13 - 16, line 1 near "RETURN FALSE FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE IF FOO', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_QUANTIFIER, got "FOO" (T_STRING) at position 16 - 19, line 1 near "RETURN FALSE IF FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_OR_T_IF()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE IF ANY FOO', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_IF, got "FOO" (T_STRING) at position 20 - 23, line 1 near " FALSE IF ANY FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_T_IF()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH FOO', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_IF, got "FOO" (T_STRING) at position 26 - 29, line 1 near " IF ANY MATCH FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE IF ANY IF', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_BOOLEAN, T_STRING, got "" (T_END) at position 22 - 22, line 1 near "FALSE IF ANY IF'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_STRING_T_OF()
    {
        $grammar = new Grammar();
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH IF PROPERTY NAME OF', $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_STRING, got "" (T_END) at position 45 - 45, line 1 near "IF PROPERTY NAME OF'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE IF ANY MATCH IF FOO';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE IF ANY IF FOO';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_RULE_T_MATCH_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE IF ALL RULES MATCH IF FOO';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_RULE_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE IF ALL RULES IF FOO';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF2_T_QUANTIFIER_T_RULE_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE WHEN ALL RULES MATCH IF FOO';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_RULE_T_IF_T_BOOLEAN()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE WHEN ALL RULES MATCH IF FALSE';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_RULE_T_IF_T_PROPERTY_T_OF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE WHEN ALL RULES MATCH IF EXAMPLE PROPERTY OF EXAMPLE OBJECT';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_RULE_T_IF_T_PROPERTY_T_OF_T_VARIABLE_2()
    {
        $grammar = new Grammar();
        $string = 'RETURN FALSE WHEN ALL RULES MATCH IF EXAMPLE PROPERTY OF EXAMPLE';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_NUMBER_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN 100 WHEN ALL RULES MATCH IF VARIABLE NAME';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_MINUS_T_NUMBER_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN -100 WHEN ALL RULES MATCH IF VARIABLE NAME';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function test_T_RETURN_T_PLUS_T_NUMBER_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN +100.10 WHEN ALL RULES MATCH IF VARIABLE NAME';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function testIsExpected_T_RETURN_T_PLUS_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN + WHEN ALL RULES MATCH IF VARIABLE NAME';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_NUMBER, got "WHEN" (T_IF) at position 9 - 13, line 1 near "RETURN + WHEN ALL RULES MATCH"'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_MINUS_T_IF_T_QUANTIFIER_T_IF_T_VARIABLE()
    {
        $grammar = new Grammar();
        $string = 'RETURN - WHEN ALL RULES MATCH IF VARIABLE NAME';
        $lexer = new Lexer($string, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_NUMBER, got "WHEN" (T_IF) at position 9 - 13, line 1 near "RETURN - WHEN ALL RULES MATCH"'
        );
        $parser->parse();
    }
}