<?php
namespace RuleEngine\Language\Parser;

use RuleEngine\Language\Lexer\Lexer;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testUnexpectedTokenTriggersSyntaxError()
    {
        $lexer = new Lexer(' FOO BAR BAZ BLA GNARF WTF');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_RETURN", got "FOO" at position 1 - 4, line 1 near " FOO BAR BAZ BLA"'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN()
    {
        $lexer = new Lexer('RETURN FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_BOOLEAN", got "FOO" at position 7 - 10, line 1 near "RETURN FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_IF", got "FOO" at position 13 - 16, line 1 near "RETURN FALSE FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER()
    {
        $lexer = new Lexer('RETURN FALSE IF FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_QUANTIFIER", got "FOO" at position 16 - 19, line 1 near "RETURN FALSE IF FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_OR_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_MATCH", "T_IF", got "FOO" at position 20 - 23, line 1 near "FALSE IF ANY FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_MATCH_T_IF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_IF", got "FOO" at position 26 - 29, line 1 near "IF ANY MATCH FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_BOOLEAN()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH IF FOO');
        $parser = new Parser($lexer->scan());

        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_BOOLEAN", got "FOO" at position 29 - 32, line 1 near "ANY MATCH IF FOO'
        );
        $parser->parse();
    }

    public function testIsExpected_T_RETURN_T_BOOLEAN_T_IF_T_QUANTIFIER_T_IF_T_STRING_T_OF()
    {
        $lexer = new Lexer('RETURN FALSE IF ANY MATCH IF PROPERTY_NAME FOO');
        $parser = new Parser($lexer->scan());
        $this->setExpectedException(
            'RuleEngine\Language\Parser\InvalidSyntaxException',
            'Expected "T_OF", got "FOO" at position 29 - 32, line 1 near "ANY MATCH IF FOO'
        );
        $parser->parse();
    }
}