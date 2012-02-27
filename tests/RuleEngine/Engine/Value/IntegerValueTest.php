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
        $this->assertTrue((new IntegerValue(100))->equals(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->equals(new IntegerValue(200)));
        $this->assertTrue((new IntegerValue(-100))->equals(new IntegerValue(-100)));
        $this->assertFalse((new IntegerValue(-200))->equals(new IntegerValue(-100)));
    }

    public function testNotEquals()
    {
        $this->assertFalse((new IntegerValue(-100))->notEquals(new IntegerValue(-100)));
        $this->assertTrue((new IntegerValue(-100))->notEquals(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->notEquals(new IntegerValue(100)));
        $this->assertTrue((new IntegerValue(100))->notEquals(new IntegerValue(-100)));
    }

    public function testCannotCallEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->equals($this->valueMock);
    }

    public function testCannotCallNotEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->notEquals($this->valueMock);
    }

    public function testCannotCallLessThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->lessThan($this->valueMock);
    }

    public function testCannotCallLessThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->lessThanOrEquals($this->valueMock);
    }

    public function testCannotCallGreaterThanWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->greaterThan($this->valueMock);
    }

    public function testCannotCallGreaterThanOrEqualsWithDifferentTypes()
    {
        $this->setExpectedException('InvalidArgumentException', 'Cannot compare type "integer" to type "mock"');
        (new IntegerValue(100))->greaterThanOrEquals($this->valueMock);
    }

    public function testLessThan()
    {
        $this->assertTrue((new IntegerValue(100))->lessThan(new IntegerValue(101)));
        $this->assertFalse((new IntegerValue(100))->lessThan(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->lessThan(new IntegerValue(99)));
    }

    public function testLessThanOrEquals()
    {
        $this->assertTrue((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(101)));
        $this->assertTrue((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->lessThanOrEquals(new IntegerValue(99)));
    }

    public function testGreaterThan()
    {
        $this->assertTrue((new IntegerValue(100))->greaterThan(new IntegerValue(99)));
        $this->assertFalse((new IntegerValue(100))->greaterThan(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->greaterThan(new IntegerValue(101)));
    }

    public function testGreaterThanOrEquals()
    {
        $this->assertTrue((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(99)));
        $this->assertTrue((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(100)));
        $this->assertFalse((new IntegerValue(100))->greaterThanOrEquals(new IntegerValue(101)));
    }
}