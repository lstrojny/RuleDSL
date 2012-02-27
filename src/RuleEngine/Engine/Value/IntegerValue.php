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

    public function equals(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $this->getValue($context) === $value->getValue($context);
    }

    public function lessThan(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $this->getValue($context) < $value->getValue($context);
    }
}