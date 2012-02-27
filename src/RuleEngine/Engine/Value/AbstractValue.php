<?php
namespace RuleEngine\Engine\Value;

use InvalidArgumentException;
use BadMethodCallException;

abstract class AbstractValue
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    abstract public function getName();

    abstract public function equals(AbstractValue $value);

    public function notEquals(AbstractValue $value)
    {
        return !$this->equals($value);
    }

    public function lessThan(AbstractValue $value)
    {
        $this->assertType($value);
        throw new BadMethodCallException(
            sprintf('Cannot express less than on non numeric type "%s"', $this->getName())
        );
    }

    public function lessThanOrEquals(AbstractValue $value)
    {
        $this->assertType($value);
        return $this->lessThan($value) || $this->equals($value);
    }

    public function greaterThan(AbstractValue $value)
    {
        $this->assertType($value);
        return $value->lessThan($this);
    }

    public function greaterThanOrEquals(AbstractValue $value)
    {
        $this->assertType($value);
        return $value->lessThan($this) || $this->equals($value);
    }

    protected function assertType(AbstractValue $value)
    {
       if (!$value instanceof $this) {
            throw new InvalidArgumentException(
                sprintf('Cannot compare type "%s" to type "%s"', $this->getName(), $value->getName())
            );
        }
    }
}
