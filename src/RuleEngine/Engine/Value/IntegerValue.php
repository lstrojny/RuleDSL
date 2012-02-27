<?php
namespace RuleEngine\Engine\Value;

use InvalidArgumentException;
use RuleEngine\Engine\RuleContext;

class IntegerValue extends AbstractValue
{
    public function __construct($value)
    {
        if (!is_integer($value)) {
            throw new InvalidArgumentException(
                sprintf('Expected value to be of type "integer", "%s" given', gettype($value))
            );
        }
        parent::__construct($value);
    }

    public function getName()
    {
        return 'integer';
    }

    public function equals(AbstractValue $value)
    {
        $this->assertType($value);
        return $this->getPrimitive() === $value->getPrimitive();
    }

    public function lessThan(AbstractValue $value)
    {
        $this->assertType($value);
        return $this->getPrimitive() < $value->getPrimitive();
    }
}