<?php
namespace RuleEngine\Engine\Value;

interface DeterminedValueInterface extends ValueInterface
{
    public function getPrimitive();

    public function equals(AbstractValue $value);

    public function notEquals(AbstractValue $value);

    public function lessThan(AbstractValue $value);

    public function lessThanOrEquals(AbstractValue $value);

    public function greaterThan(AbstractValue $value);

    public function greaterThanOrEquals(AbstractValue $value);
}