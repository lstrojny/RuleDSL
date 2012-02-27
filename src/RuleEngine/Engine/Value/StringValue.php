<?php
namespace RuleEngine\Engine\Value;

use InvalidArgumentException;
use BadMethodCallException;
use RuleEngine\Engine\RuleContext;

class StringValue extends AbstractValue
{
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('Expected value to be of type "string", "%s" given', gettype($value))
            );
        }
        parent::__construct($value);
    }

    public function getName()
    {
        return 'string';
    }

    public function equals(AbstractValue $value, RuleContext $context)
    {
        $this->assertType($value);
        return $value->getValue($context) === $this->getValue($context);
    }
}