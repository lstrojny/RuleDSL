<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\RuleContext;
use RuleEngine\Engine\Value\StringValue;
use RuleEngine\Engine\Value\IntegerValue;
use RuleEngine\Engine\Value\BooleanValue;
use RuntimeException;

class Variable implements ValueInterface
{
    public function __construct($variableName)
    {
        $this->variableName = $variableName;
    }

    public function getValue(RuleContext $context)
    {
        $value = $context->lookup($this->variableName);
        $type = gettype($value);

        switch ($type) {
            case 'string':  return new StringValue($value);
            case 'integer': return new IntegerValue($value);
            case 'boolean': return new BooleanValue($value ? 'TRUE' : 'FALSE');
            default:        throw new RuntimeException(sprintf('Invalid type: "%s"', $type));
        }
    }

    public function getName()
    {
        return 'variable: ' . $this->variableName;
    }
}