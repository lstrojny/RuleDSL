<?php
namespace RuleEngine\Engine\Value;

class IntegerValueTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->valueMock = $this->getMockBuilder('RuleEngine\Engine\Value\AbstractValue')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->valueMock->expects($this->any())
                        ->method('getName')
                        ->will($this->returnValue('mock'));
        $this->context = $this->getMockBuilder('RuleEngine\Engine\RuleContext')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    public function testExceptionIsThrownIfInvalidString()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Expected value to be of type "integer", "string" given'
        );
        new IntegerValue('FOO');
    }

    public function testEquals()
    {
        $this->assertTrue((new IntegerValue(100))->equals(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->equals(new IntegerValue(200), $this->context));
        $this->assertTrue((new IntegerValue(-100))->equals(new IntegerValue(-100), $this->context));
        $this->assertFalse((new IntegerValue(-200))->equals(new IntegerValue(-100), $this->context));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new IntegerValue(-100))->notEquals(new IntegerValue(-100), $this->context));
        $this->assertTrue((new IntegerValue(-100))->notEquals(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->notEquals(new IntegerValue(100), $this->context));
        $this->assertTrue((new IntegerValue(100))->notEquals(new IntegerValue(-100), $this->context));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->equals($this->valueMock, $this->context);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->notEquals($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->lessThan($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->lessThanOrEquals($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->greaterThan($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->greaterThanOrEquals($this->valueMock, $this->context);
    }

    public function testLessThan()
    {
        $this->assertTrue((new IntegerValue(100))->lessThan(new IntegerValue(101), $this->context));
        $this->assertFalse((new IntegerValue(100))->lessThan(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->lessThan(new IntegerValue(99), $this->context));
    }

    public function testLessThanOrEquals()
    {
        $this->assertTrue((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(101), $this->context));
        $this->assertTrue((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(99), $this->context));
    }

    public function testGreaterThan()
    {
        $this->assertTrue((new IntegerValue(100))->greaterThan(new IntegerValue(99), $this->context));
        $this->assertFalse((new IntegerValue(100))->greaterThan(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->greaterThan(new IntegerValue(101), $this->context));
    }

    public function testGreaterThanOrEquals()
    {
        $this->assertTrue((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(99), $this->context));
        $this->assertTrue((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(100), $this->context));
        $this->assertFalse((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(101), $this->context));
    }
}