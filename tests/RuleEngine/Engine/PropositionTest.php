<?php
namespace RuleEngine\Engine;

use RuleEngine\Engine\Context\SimpleContext;

class PropositionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = new SimpleContext([
            'string'  => 'STRING',
            'integer' => 100,
        ]);
    }

    public function testSimpleBooleanProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('TRUE')
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_1()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('FALSE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('TRUE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_2()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('FALSE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\IntegerValue(100),
            new Operator(Operator::EQUAL),
            new Value\IntegerValue(100)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_2()
    {
        $proposition = new Proposition(
            new Value\IntegerValue(100),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testVariableIntegerProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }
}