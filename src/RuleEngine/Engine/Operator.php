<?php
namespace RuleEngine\Engine;

use InvalidArgumentException;
use ReflectionObject;
use RuleEngine\Engine\Value\AbstractValue;
use RuleEngine\Engine\RuleContext;

class Operator
{
    const EQUAL = 'EQUAL';

    const GREATER = 'GREATER THAN';

    const GREATER_OR_EQUAL = 'GREATER THAN OR EQUAL';

    const LESS = 'LESS THAN';

    const LESS_OR_EQUAL = 'LESS THAN OR EQUAL';

    private $type;

    public function __construct($type)
    {
        if (!in_array($type, (new ReflectionObject($this))->getConstants(), true)) {
            throw new InvalidArgumentException('Invalid operator "' . $type . '"');
        }

        $this->type = $type;
    }

    public function evaluate(AbstractValue $left, AbstractValue $right, RuleContext $context)
    {
        switch ($this->type) {
            case static::EQUAL:            return $left->equals($right, $context);
            case static::GREATER:          return $left->greaterThan($right, $context);
            case static::GREATER_OR_EQUAL: return $left->greaterThanOrEquals($right, $context);
            case static::LESS:             return $left->lessThan($right, $context);
            case static::LESS_OR_EQUAL:    return $left->lessThanOrEquals($right, $context);
        }
    }
}