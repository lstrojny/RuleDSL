<?php
namespace RuleEngine\Engine\Value;

use RuleEngine\Engine\Context\ContextInterface;
use RuntimeException;

class VariableValue implements ValueInterface
{
    private $variableHierarchy;

    public function __construct($variableName)
    {
        $this->variableHierarchy = func_get_args();
    }

    public function getValue(ContextInterface $context)
    {
        foreach ($this->variableHierarchy as $variableName) {
            $context = $context->lookup($variableName);
        }

        $type = gettype($context);

        switch ($type) {
            case 'string':  return new StringValue($context);
            case 'integer': return new IntegerValue($context);
            case 'boolean': return new BooleanValue($context ? 'TRUE' : 'FALSE');
            default:        throw new RuntimeException(sprintf('Invalid type: "%s"', $type));
        }
    }

    public function getName()
    {
        return 'variable: ' . $this->variableHierarchy;
    }
}