<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\Context\ContextInterface;
use RuntimeException;

class VariableValue implements ValueInterface
{
    public function __construct($variableName)
    {
        $this->variableName = $variableName;
    }

    public function getValue(ContextInterface $context)
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