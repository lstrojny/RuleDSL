<?php
namespace RuleEngine\Language;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function testParseSimpleRule()
    {
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
MEMBERSHIP OF USER IS NOT PREMIUM
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('MEMBERSHIP'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS NOT'),
                new Token\WhitespaceToken(' '),
                new Token\PropertyToken('PREMIUM'),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
    }

    public function testParseRuleWithBoolClause()
    {
        $result = $this->parser->parse('RETURN 1 WHEN ANY RULE APPLY
BEGIN
CONFIRMED OF USER IS TRUE
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(1),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ANY'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('CONFIRMED'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
    }

    public function testParseRuleWithNumericComparison()
    {
        $result = $this->parser->parse('RETURN 100 WHEN ALL APPLY
BEGIN
AGE OF USER IS GREATER THAN 18
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(100),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('AGE'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS GREATER THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(18),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN -100 WHEN ALL
BEGIN
AGE OF USER IS GREATER THAN +18
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('AGE'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS GREATER THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(18),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN FALSE IF ANY RULES APPLY
BEGIN
DEBT OF USER IS GREATER THAN -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('FALSE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('IF'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ANY'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS GREATER THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
DEBT OF USER IS LESS THAN -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS LESS THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
DEBT OF USER IS LESS -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS LESS'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
DEBT OF USER IS NOT LESS THAN -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS NOT LESS THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
DEBT OF USER IS NOT GREATER OR EQUAL THAN 1 000
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS NOT GREATER OR EQUAL THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(1000),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
DEBT OF USER IS NOT LESS THAN -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('DEBT'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('IS NOT LESS THAN'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
    }

    public function testAlternativeComparisonNames()
    {
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
LAST LOGIN OF USER WAS NOT LESS THAN OR EQUAL -100
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('LAST LOGIN'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('WAS NOT LESS THAN OR EQUAL'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(-100),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
YESTERDAYS FRIENDS OF USER WERE NOT 10
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('YESTERDAYS FRIENDS'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('WERE NOT'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(10),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
        $result = $this->parser->parse('RETURN TRUE WHEN ALL RULES APPLY
BEGIN
FRIENDS OF USER ARE GREATER 10
END');
        $this->assertEqualTokenStreams(
            array(
                new Token\ReturnToken('RETURN'),
                new Token\WhitespaceToken(' '),
                new Token\BooleanToken('TRUE'),
                new Token\WhitespaceToken(' '),
                new Token\WhenToken('WHEN'),
                new Token\WhitespaceToken(' '),
                new Token\EvaluationToken('ALL'),
                new Token\WhitespaceToken("\n"),
                new Token\BeginToken('BEGIN'),
                new Token\WhitespaceToken("\n"),
                new Token\PropertyToken('FRIENDS'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectOperatorToken('OF'),
                new Token\WhitespaceToken(' '),
                new Token\ObjectToken('USER'),
                new Token\WhitespaceToken(' '),
                new Token\ComparisonToken('ARE GREATER'),
                new Token\WhitespaceToken(' '),
                new Token\IntegerToken(10),
                new Token\WhitespaceToken("\n"),
                new Token\EndToken('END'),
            ),
            $result
        );
    }

    protected function assertEqualTokenStreams($expected, $got)
    {
        $this->assertEquals($expected, $got);

        foreach ($expected as $key => $token) {
            $this->assertSame($token->getValue(), $got[$key]->getValue());
        }
    }
}
