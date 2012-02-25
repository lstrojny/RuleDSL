<?php
namespace RuleEngine\Language\Parser;

use RuleEngine\Language\Lexer\Lexer;
use RuleEngine\Language\Compiler\Printer;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->printer = new Printer();
    }

    public function testUnexpectedTokenTriggersSyntaxError()
    {
        $lexer = new Lexer(' FOO BAR BAZ BLA GNARF WTF');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_RETURN, got "FOO" (T_STRING) at position 1 - 4, line 1 near " FOO BAR BAZ BLA"'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN()
    {
        $lexer = new Lexer('RETURN FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_BOOLEAN, got "FOO" (T_STRING) at position 7 - 10, line 1 near "RETURN FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_IF, got "FOO" (T_STRING) at position 13 - 16, line 1 near "RETURN FALSE FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER()
    {
        $lexer = new Lexer('RETURN FALSE IF FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_QUANTIFIER, got "FOO" (T_STRING) at position 16 - 19, line 1 near "RETURN FALSE IF FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_OR_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_IF, got "FOO" (T_STRING) at position 20 - 23, line 1 near "FALSE IF ANY FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_IF, got "FOO" (T_STRING) at position 26 - 29, line 1 near "IF ANY MATCH FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY IF');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected T_BOOLEAN, T_STRING, got "" (T_END) at position 22 - 22, line 1 near "IF ANY IF'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_BOOLEAN()
    {
        $string = 'RETURN FALSE IF ANY MATCH IF FOO';
        $lexer = new Lexer($string);
        $parser = new Parser($lexer->scan());
        $rootNode = $parser->parse();
        $rootNode->accept($this->printer);
        $this->assertSame($string, (string) $this->printer);
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_STRING_T_OF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH IF PROPERTY NAME OF');
        $parser = new Parser($lexer->scan());
//        $this->setExpectedException(
//            'RuleEngine\Language\Parser\InvalidSyntaxException',
//            'Expected T_STRING, got "" (T_END) at position 29 - 32, line 1 near "ANY MATCH IF FOO'
//        );
        $parser->parse();
    }
}