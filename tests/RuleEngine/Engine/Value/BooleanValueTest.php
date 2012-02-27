<?php
namespace RuleEngine\Engine\Value;

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
        $this->assertTrue((new BooleanValue('TRUE'))->equals(new BooleanValue('TRUE')));
        $this->assertFalse((new BooleanValue('TRUE'))->equals(new BooleanValue('FALSE')));
        $this->assertTrue((new BooleanValue('FALSE'))->equals(new BooleanValue('FALSE')));
        $this->assertFalse((new BooleanValue('FALSE'))->equals(new BooleanValue('TRUE')));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new BooleanValue('TRUE'))->notEquals(new BooleanValue('TRUE')));
        $this->assertTrue((new BooleanValue('TRUE'))->notEquals(new BooleanValue('FALSE')));
        $this->assertFalse((new BooleanValue('FALSE'))->notEquals(new BooleanValue('FALSE')));
        $this->assertTrue((new BooleanValue('FALSE'))->notEquals(new BooleanValue('TRUE')));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->equals($this->valueMock);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->notEquals($this->valueMock);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->lessThan($this->valueMock);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->lessThanOrEquals($this->valueMock);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->greaterThan($this->valueMock);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "boolean" to type "mock"');
        (new BooleanValue('TRUE'))->greaterThanOrEquals($this->valueMock);
    }

    public function testLessThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->lessThan(new BooleanValue('TRUE'));
    }

    public function testLessThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->lessThanOrEquals(new BooleanValue('TRUE'));
    }

    public function testGreaterThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->greaterThan(new BooleanValue('TRUE'));
    }

    public function testGreaterThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "boolean"');
        (new BooleanValue('TRUE'))->greaterThanOrEquals(new BooleanValue('TRUE'));
    }
}