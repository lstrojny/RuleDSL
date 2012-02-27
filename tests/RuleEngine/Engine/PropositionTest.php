<?php
namespace RuleEngine\Engine;

use RuleEngine\Engine\Value\BooleanValue;
use RuleEngine\Engine\Value\IntegerValue;

class PropositionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = $this->getMockBuilder('RuleEngine\Engine\RuleContext')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    public function testSimpleBooleanProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new BooleanValue('TRUE')
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_1()
    {
        $proposition = new Proposition(
            new BooleanValue('FALSE'),
            new Operator(Operator::EQUAL),
            new BooleanValue('TRUE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_2()
    {
        $proposition = new Proposition(
            new BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new BooleanValue('FALSE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new IntegerValue(100),
            new Operator(Operator::EQUAL),
            new IntegerValue(100)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_2()
    {
        $proposition = new Proposition(
            new IntegerValue(100),
            new Operator(Operator::LESS),
            new IntegerValue(101)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }
}