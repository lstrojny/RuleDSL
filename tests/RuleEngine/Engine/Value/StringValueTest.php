<?php
namespace RuleEngine\Engine\Value;

class StringValueTest extends \PHPUnit_Framework_TestCase
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
            'Expected value to be of type "string", "integer" given'
        );
        new StringValue(10);
    }

    public function testEquals()
    {
        $this->assertTrue((new StringValue('TRUE'))->equals(new StringValue('TRUE'), $this->context));
        $this->assertFalse((new StringValue('TRUE'))->equals(new StringValue('FALSE'), $this->context));
        $this->assertTrue((new StringValue('FALSE'))->equals(new StringValue('FALSE'), $this->context));
        $this->assertFalse((new StringValue('FALSE'))->equals(new StringValue('TRUE'), $this->context));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new StringValue('TRUE'))->notEquals(new StringValue('TRUE'), $this->context));
        $this->assertTrue((new StringValue('TRUE'))->notEquals(new StringValue('FALSE'), $this->context));
        $this->assertFalse((new StringValue('FALSE'))->notEquals(new StringValue('FALSE'), $this->context));
        $this->assertTrue((new StringValue('FALSE'))->notEquals(new StringValue('TRUE'), $this->context));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->equals($this->valueMock, $this->context);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->notEquals($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->lessThan($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->lessThanOrEquals($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->greaterThan($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->greaterThanOrEquals($this->valueMock, $this->context);
    }

    public function testLessThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->lessThan(new StringValue('TRUE'), $this->context);
    }

    public function testLessThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->lessThanOrEquals(new StringValue('TRUE'), $this->context);
    }

    public function testGreaterThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->greaterThan(new StringValue('TRUE'), $this->context);
    }

    public function testGreaterThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->greaterThanOrEquals(new StringValue('TRUE'), $this->context);
    }
}