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
        $this->assertTrue((new StringValue('TRUE'))->equals(new StringValue('TRUE')));
        $this->assertFalse((new StringValue('TRUE'))->equals(new StringValue('FALSE')));
        $this->assertTrue((new StringValue('FALSE'))->equals(new StringValue('FALSE')));
        $this->assertFalse((new StringValue('FALSE'))->equals(new StringValue('TRUE')));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new StringValue('TRUE'))->notEquals(new StringValue('TRUE')));
        $this->assertTrue((new StringValue('TRUE'))->notEquals(new StringValue('FALSE')));
        $this->assertFalse((new StringValue('FALSE'))->notEquals(new StringValue('FALSE')));
        $this->assertTrue((new StringValue('FALSE'))->notEquals(new StringValue('TRUE')));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->equals($this->valueMock);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->notEquals($this->valueMock);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->lessThan($this->valueMock);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->lessThanOrEquals($this->valueMock);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->greaterThan($this->valueMock);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "string" to type "mock"');
        (new StringValue('TRUE'))->greaterThanOrEquals($this->valueMock);
    }

    public function testLessThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->lessThan(new StringValue('TRUE'));
    }

    public function testLessThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->lessThanOrEquals(new StringValue('TRUE'));
    }

    public function testGreaterThanThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->greaterThan(new StringValue('TRUE'));
    }

    public function testGreaterThanOrEqualsThrowsException()
    {
        $this->setExpectedException('BadMethodCallException', 'Cannot express less than on non numeric type "string"');
        (new StringValue('TRUE'))->greaterThanOrEquals(new StringValue('TRUE'));
    }
}