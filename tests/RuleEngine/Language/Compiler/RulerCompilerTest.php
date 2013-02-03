<?php
namespace RuleEngine\Language\Compiler;

use RuleEngine\Language\Lexer\Grammar;
use RuleEngine\Language\Lexer\Lexer;
use RuleEngine\Language\Parser\Parser;
use Ruler\Context;

class RulerCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RulerCompiler
     */
    private $compiler;

    public function setUp()
    {
        $this->compiler = new RulerCompiler();
    }

    public function testSimpleExpression_PositiveResult_Double()
    {
        $rule = $this->getRule('RETURN +100.10 WHEN ALL RULES MATCH IF VARIABLE NAME');

        $this->assertNull($rule->execute(new Context(array('VARIABLE NAME' => false))));
        $this->assertSame(100.10, $rule->execute(new Context(array('VARIABLE NAME' => true))));
    }

    public function testSimpleExpression_PositiveResult_Double_2()
    {
        $rule = $this->getRule('RETURN 100.10 WHEN ALL RULES MATCH IF VARIABLE NAME');

        $this->assertNull($rule->execute(new Context(array('VARIABLE NAME' => false))));
        $this->assertSame(100.10, $rule->execute(new Context(array('VARIABLE NAME' => true))));
    }

    public function testSimpleExpression_NegativeResult_Double()
    {
        $rule = $this->getRule('RETURN -100.10 WHEN ALL RULES MATCH IF VARIABLE NAME');

        $this->assertNull($rule->execute(new Context(array('VARIABLE NAME' => false))));
        $this->assertSame(-100.10, $rule->execute(new Context(array('VARIABLE NAME' => true))));
    }

    public function testSimpleExpression_BooleanReturn()
    {
        $rule = $this->getRule('RETURN FALSE WHEN ANY RULE IF NOT VARIABLE NAME');

        $this->assertNull($rule->execute(new Context(array('VARIABLE NAME' => true))));
        $this->assertFalse($rule->execute(new Context(array('VARIABLE NAME' => false))));
    }

    public function testSimpleExpression_BooleanReturn_2()
    {
        $rule = $this->getRule('RETURN FALSE WHEN ALL RULES IF NOT VARIABLE NAME');

        $this->assertNull($rule->execute(new Context(array('VARIABLE NAME' => true))));
        $this->assertFalse($rule->execute(new Context(array('VARIABLE NAME' => false))));
    }

    public function testSimpleExpression_BooleanReturn_3()
    {
        $rule = $this->getRule('RETURN TRUE WHEN ALL RULES IF VARIABLE OF OBJECT');

        $this->assertNull($rule->execute(new Context(array('OBJECT' => (object) ['VARIABLE' => false]))));
        $this->assertNull($rule->execute(new Context(array('OBJECT' => ['VARIABLE' => false]))));
        $this->assertTrue($rule->execute(new Context(array('OBJECT' => ['VARIABLE' => true]))));
        $this->assertTrue($rule->execute(new Context(array('OBJECT' => (object) ['VARIABLE' => true]))));
    }

    public function testSimpleExpression_BooleanReturn_4()
    {
        $rule = $this->getRule('RETURN TRUE WHEN ALL RULES IF VARIABLE OF OBJECT OF ANOTHER OBJECT');

        $this->assertNull($rule->execute(new Context(array('ANOTHER OBJECT' => (object) ['OBJECT' => (object) ['VARIABLE 2' => false]]))));
        $this->assertNull($rule->execute(new Context(array('ANOTHER OBJECT' => (object) ['OBJECT' => (object) ['VARIABLE' => false]]))));
        $this->assertTrue($rule->execute(new Context(array('ANOTHER OBJECT' => (object) ['OBJECT' => (object) ['VARIABLE' => true]]))));
        $this->assertTrue($rule->execute(new Context(array('ANOTHER OBJECT' => (object) ['OBJECT' => (object) ['VARIABLE' => true]]))));
        $this->assertTrue($rule->execute(new Context(array('ANOTHER OBJECT' => ['OBJECT' => (object) ['VARIABLE' => true]]))));
    }

    private function getRule($code)
    {
        $grammar = new Grammar();
        $lexer = new Lexer($code, $grammar);
        $parser = new Parser($lexer->scan(), $grammar);
        $rootNode = $parser->parse();
        $rootNode->accept($this->compiler);

        $rule = $this->compiler->getRule();
        $this->assertInstanceOf('Ruler\Rule', $rule);

        return $rule;
    }
}
