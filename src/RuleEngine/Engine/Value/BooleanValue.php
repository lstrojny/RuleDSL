<?php
namespace RuleEngine\Engine\Value;

use InvalidArgumentException;
use BadMethodCallException;
use RuleEngine\Engine\RuleContext;

class BooleanValue extends AbstractValue
{
    public function __construct($value)
    {
        if ($value !== 'TRUE' && $value !== 'FALSE') {
            throw new InvalidArgumentException(sprintf('Expected "TRUE" or "FALSE", got "%s"', $value));
        }
        parent::__construct($value);
    }

    public function getName()
    {
        return 'boolean';
    }

    public function equals(AbstractValue $value)
    {
        $this->assertType($value);
        return $value->getPrimitive() === $this->getPrimitive();
    }
}