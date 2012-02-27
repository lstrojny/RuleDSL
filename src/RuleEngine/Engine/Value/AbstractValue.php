<?php
namespace RuleEngine\Engine\Value;

use InvalidArgumentException;
use BadMethodCallException;
use RuleEngine\Engine\RuleContext;

abstract class AbstractValue
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue(RuleContext $context)
    {
        return $this->value;
    }

    abstract public function getName();

    abstract public function equals(AbstractValue $value, RuleContext $context);

    public function notEquals(AbstractValue $value, RuleContext $context)
    {
        return !$this->equals($value, $context);
    }

    public function lessThan(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        throw new BadMethodCallException(
            sprintf('Cannot express less than on non numeric type "%s"', $this->getName())
        );
    }

    public function lessThanOrEquals(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $this->lessThan($value, $context) || $this->equals($value, $context);
    }

    public function greaterThan(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $value->lessThan($this, $context);
    }

    public function greaterThanOrEquals(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $value->lessThan($this, $context) || $this->equals($value, $context);
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
