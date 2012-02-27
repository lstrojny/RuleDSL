<?php
namespace RuleEngine\Engine;

use RuleEngine\Engine\Value\BooleanValue;
use RuleEngine\Engine\Value\IntegerValue;

class OperatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = $this->getMockBuilder('RuleEngine\Engine\RuleContext')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    public function testEqual()
    {
        $operator = new Operator(Operator::EQUAL);
        $this->assertTrue($operator->evaluate(new BooleanValue('TRUE'), new BooleanValue('TRUE'), $this->context));
        $this->assertFalse($operator->evaluate(new BooleanValue('FALSE'), new BooleanValue('TRUE'), $this->context));
    }

    public function testGreater()
    {
        $operator = new Operator(Operator::GREATER);
        $this->assertTrue($operator->evaluate(new IntegerValue(101), new IntegerValue(100), $this->context));
        $this->assertFalse($operator->evaluate(new IntegerValue(100), new IntegerValue(100), $this->context));
        $this->assertFalse($operator->evaluate(new IntegerValue(100), new IntegerValue(101), $this->context));
    }

    public function testGreaterOrEqual()
    {
        $operator = new Operator(Operator::GREATER_OR_EQUAL);
        $this->assertTrue($operator->evaluate(new IntegerValue(200), new IntegerValue(100), $this->context));
        $this->assertTrue($operator->evaluate(new IntegerValue(100), new IntegerValue(100), $this->context));
        $this->assertFalse($operator->evaluate(new IntegerValue(100), new IntegerValue(200), $this->context));
    }

    public function testLess()
    {
        $operator = new Operator(Operator::LESS);
        $this->assertTrue($operator->evaluate(new IntegerValue(200), new IntegerValue(300), $this->context));
        $this->assertTrue($operator->evaluate(new IntegerValue(100), new IntegerValue(200), $this->context));
        $this->assertFalse($operator->evaluate(new IntegerValue(300), new IntegerValue(200), $this->context));
    }

    public function testLessOrEqual()
    {
        $operator = new Operator(Operator::LESS_OR_EQUAL);
        $this->assertTrue($operator->evaluate(new IntegerValue(200), new IntegerValue(300), $this->context));
        $this->assertTrue($operator->evaluate(new IntegerValue(100), new IntegerValue(100), $this->context));
        $this->assertFalse($operator->evaluate(new IntegerValue(300), new IntegerValue(200), $this->context));
    }

    public function testInvalid()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid operator "FOO"');
        new Operator('FOO');
    }
}