<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\RuleContext;

class BooleanValueTest extends \PHPUnit_Framework_TestCase
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
            'Expected "TRUE" or "FALSE", got "FOO"'
        );
        new BooleanValue('FOO');
    }

    public function testEquals()
    {
        $this->assertTrue((new BooleanValue('TRUE'))->equals(new BooleanValue('TRUE'), $this->context));
        $this->assertFalse((new BooleanValue('TRUE'))->equals(new BooleanValue('FALSE'), $this->context));
        $this->assertTrue((new BooleanValue('FALSE'))->equals(new BooleanValue('FALSE'), $this->context));
        $this->assertFalse((new BooleanValue('FALSE'))->equals(new BooleanValue('TRUE'), $this->context));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new BooleanValue('TRUE'))->notEquals(new BooleanValue('TRUE'), $this->context));
        $this->assertTrue((new BooleanValue('TRUE'))->notEquals(new BooleanValue('FALSE'), $this->context));
        $this->assertFalse((new BooleanValue('FALSE'))->notEquals(new BooleanValue('FALSE'), $this->context));
        $this->assertTrue((new BooleanValue('FALSE'))->notEquals(new BooleanValue('TRUE'), $this->context));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->equals($this->valueMock, $this->context);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->notEquals($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->lessThan($this->valueMock, $this->context);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->lessThanOrEquals($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->greaterThan($this->valueMock, $this->context);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->greaterThanOrEquals($this->valueMock, $this->context);
    }

    public function testLessThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->lessThan(new BooleanValue('TRUE'), $this->context);
    }

    public function testLessThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->lessThanOrEquals(new BooleanValue('TRUE'), $this->context);
    }

    public function testGreaterThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->greaterThan(new BooleanValue('TRUE'), $this->context);
    }

    public function testGreaterThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->greaterThanOrEquals(new BooleanValue('TRUE'), $this->context);
    }
}