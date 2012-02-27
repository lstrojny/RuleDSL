<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\Context\SimpleContext;

class VariableValueTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = new SimpleContext([
            'str'             => 'STRING',
            'positiveInteger' => 100,
            'negativeInteger' => -100,
            'booleanTrue'     => true,
            'booleanFalse'    => false,
        ]);
    }

    public function testAccessingStringVariable()
    {
        $value = (new VariableValue('str'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\StringValue', $value);
        $this->assertSame('STRING', $value->getPrimitive());
    }

    public function testAccessingIntegerVariable()
    {
        $value = (new VariableValue('positiveInteger'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\IntegerValue', $value);
        $this->assertSame(100, $value->getPrimitive());

        $value = (new VariableValue('negativeInteger'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\IntegerValue', $value);
        $this->assertSame(-100, $value->getPrimitive());
    }

    public function testAccessingBooleanVariable()
    {
        $value = (new VariableValue('booleanFalse'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\BooleanValue', $value);
        $this->assertSame('FALSE', $value->getPrimitive());

        $value = (new VariableValue('booleanTrue'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\BooleanValue', $value);
        $this->assertSame('TRUE', $value->getPrimitive());
    }

    public function testAccessingInexistingVariable()
    {
        $this->setExpectedException('OutOfBoundsException', 'Invalid variable "invalidVariable"');
        (new VariableValue('invalidVariable'))->getValue($this->context);
    }
}