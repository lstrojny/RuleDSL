<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\RuleContext;

class VariableTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = new RuleContext([
            'str'             => 'STRING',
            'positiveInteger' => 100,
            'negativeInteger' => -100,
            'booleanTrue'     => true,
            'booleanFalse'    => false,
        ]);
    }

    public function testAccessingStringVariable()
    {
        $value = (new Variable('str'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\StringValue', $value);
        $this->assertSame('STRING', $value->getPrimitive());
    }

    public function testAccessingIntegerVariable()
    {
        $value = (new Variable('positiveInteger'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\IntegerValue', $value);
        $this->assertSame(100, $value->getPrimitive());

        $value = (new Variable('negativeInteger'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\IntegerValue', $value);
        $this->assertSame(-100, $value->getPrimitive());
    }

    public function testAccessingBooleanVariable()
    {
        $value = (new Variable('booleanFalse'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\BooleanValue', $value);
        $this->assertSame('FALSE', $value->getPrimitive());

        $value = (new Variable('booleanTrue'))->getValue($this->context);
        $this->assertInstanceOf('RuleEngine\Engine\Value\BooleanValue', $value);
        $this->assertSame('TRUE', $value->getPrimitive());
    }
}