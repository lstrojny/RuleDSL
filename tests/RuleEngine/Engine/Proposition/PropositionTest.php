<?php
namespace RuleEngine\Engine\Proposition;

use RuleEngine\Engine\Value;
use RuleEngine\Engine\Operator;
use RuleEngine\Engine\Context\SimpleContext;

class PropositionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = new SimpleContext([
            'string'  => 'STRING',
            'integer' => 100,
        ]);
    }

    public function testSimpleBooleanProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('TRUE')
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_1()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('FALSE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('TRUE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleBooleanProposition_FALSE_2()
    {
        $proposition = new Proposition(
            new Value\BooleanValue('TRUE'),
            new Operator(Operator::EQUAL),
            new Value\BooleanValue('FALSE')
        );
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\IntegerValue(100),
            new Operator(Operator::EQUAL),
            new Value\IntegerValue(100)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testSimpleIntegerProposition_TRUE_2()
    {
        $proposition = new Proposition(
            new Value\IntegerValue(100),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testVariableIntegerProposition_TRUE_1()
    {
        $proposition = new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        );
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testPropositionAndOr()
    {
        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        ))->and((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('NOT TEST')
        )))->or((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('TEST')
        )));
        $this->assertTrue(100 < 101 && 'TEST' === 'NOT TEST' || 'TEST' === 'TEST');
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testPropositionAndOrAnd()
    {
        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        ))->and((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('NOT TEST')
        )))->or((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('TEST')
        )))->and((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('TEST')
        )));
        $this->assertTrue(100 < 101 && 'TEST' === 'NOT TEST' || 'TEST' === 'TEST' && 'TEST' === 'TEST');
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testPropositionAndOrAnd_fail()
    {
        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(100)
        ))->and((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('NOT TEST')
        )))->or((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('NOT TEST')
        )))->and((new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('TEST')
        )));
        $this->assertFalse(100 < 100 && 'TEST' === 'NOT TEST' || 'TEST' === 'NOT TEST' && 'TEST' === 'TEST');
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testGroupedPropositions()
    {
        $group = (new Proposition(
            new Value\StringValue('TEST'),
            new Operator(Operator::EQUAL),
            new Value\StringValue('TEST')
        ))->and((new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::EQUAL),
            new Value\IntegerValue(100)
        )));

        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(100)
        ))->or($group);

        $this->assertTrue(100 < 100 || ('TEST' === 'TEST' && 100 === 100));
        $this->assertTrue($proposition->evaluate($this->context));
    }

    public function testXorProposition()
    {
        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        ))->xor(new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::EQUAL),
            new Value\IntegerValue(100)
        ));
        $this->assertFalse(100 < 101 xor 100 === 100);
        $this->assertFalse($proposition->evaluate($this->context));
    }

    public function testNegateProposition()
    {
        $proposition = (new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::LESS),
            new Value\IntegerValue(101)
        ))->and()->not(new Proposition(
            new Value\VariableValue('integer'),
            new Operator(Operator::EQUAL),
            new Value\IntegerValue(100)
        ));
        $this->assertFalse(100 < 101 && !(100 === 100));
        $this->assertFalse($proposition->evaluate($this->context));
    }
}