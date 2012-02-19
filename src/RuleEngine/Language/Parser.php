<?php
namespace RuleEngine\Language;

use RuntimeException;

class Parser
{
    private $stack = array();

    private $tokens = array();

    public function parse($string)
    {
        $this->tokens = array();

        $this->stack = array();
        $this->processAll($string);


        while ($this->stack) {
            $matches = array_shift($this->stack);

            foreach ($matches as $tokenName => $match) {
                array_shift($matches);

                $tokenName = preg_replace('/\d+$/', '', $tokenName);

                $methodName = 'process' . ucfirst($tokenName);
                if (!method_exists($this, $methodName)) {
                    throw new RuntimeException(
                        sprintf(
                            'Handler for "%s" ("%s") is not implemented',
                            $tokenName,
                            $methodName
                        )
                    );
                }

                $reset = false;
                foreach ($match as $value) {
                    $reset = $this->{$methodName}($value, $matches);
                }

                if ($reset) {
                    continue 2;
                }
            }
        }

        return $this->tokens;
    }

    private function match($string, $regex)
    {
        if (preg_match_all($regex, $string, $matches) === 0) {
            throw new RuntimeException(
                sprintf(
                    'Parse error: "%s" did not match "%s"',
                    $regex,
                    $string
                )
            );
        }

        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                unset($matches[$key]);
            } else {
                $matches[$key] = array_filter($matches[$key]);
            }
        }

        return $matches;
    }

    protected function processAll($value)
    {
        $value = preg_replace('/\s*(?<comment>#[^\n]+)$/sm', '', $value);

        return $this->processNaked($value);
    }

    protected function processNaked($value)
    {
        $regex = '/
            (?<whitespace0>\s*)
            (?<return>RETURN)
            (?<whitespace1>\s+)
            (?<expression>.+?)
            (?<whitespace2>\s+)
            (?<when>WHEN|IF)
            (?<whitespace3>\s+)
            (?<evaluation>ALL|ANY)
            (?:
                (?:\s+RULE[S]?)?
                (?:\s+APPLY)?
            )?
            (?<whitespace4>\s+)
            (?<begin>BEGIN)
            (?<whitespace5>\s+)
            (?<statement>.*)
            (?<whitespace6>\s+)
            (?<end>END)
        /x';

        $this->push($this->match($value, $regex));

        return true;
    }

    protected function processStatement($value, array $remainingMatches)
    {
        $this->push($remainingMatches);

        $regex = '/
            (?<property>.*)
            (?<whitespace2>\s+)
            (?<objectOperator>OF)
            (?<whitespace3>\s+)
            (?<object>[^\s]+)
            (?<whitespace4>\s+)
            (?<rule>.*)
        /x';

        $this->push($this->match($value, $regex));

        return true;
    }

    protected function processInteger($value)
    {
        $value = (integer) str_replace(' ', '', $value);
        $this->tokens[] = new Token\IntegerToken($value);

        return false;
    }

    protected function processBegin($value)
    {
        $this->tokens[] = new Token\BeginToken($value);

        return false;
    }

    protected function processWhitespace($value)
    {
        $this->tokens[] = new Token\WhitespaceToken($value);

        return false;
    }

    protected function processProperty($value)
    {
        $this->tokens[] = new Token\PropertyToken($value);

        return false;
    }

    protected function processObjectOperator($value)
    {
        $this->tokens[] = new Token\ObjectOperatorToken($value);

        return false;
    }

    protected function processObject($value)
    {
        $this->tokens[] = new Token\ObjectToken($value);

        return false;
    }

    protected function processEnd($value)
    {
        $this->tokens[] = new Token\EndToken($value);

        return false;
    }

    protected function processBool($value)
    {
        $this->tokens[] = new Token\BooleanToken($value);

        return false;
    }

    protected function processComparison($value)
    {
        $this->tokens[] = new Token\ComparisonToken($value);

        return false;
    }

    protected function processRule($value, array $remainingMatches)
    {
        $this->push($remainingMatches);

        $regex = '/^
                (?<comparison>
                    (ARE|IS|WAS|WERE)
                    (:?\s+NOT)?
                    (?:
                        \s+
                        (?:GREATER|LESS)
                        (?:\s+THAN)?
                        (?:\s+OR\s+EQUAL)?
                        (?:\s+THAN)?
                    )?
                )
                (?<whitespace>\s+)
                (?<expression>.+?)
            $/x';
        $this->push($this->match($value, $regex));

        return true;
    }

    protected function processReturn($value)
    {
        $this->tokens[] = new Token\ReturnToken($value);

        return false;
    }

    protected function processWhen($value)
    {
        $this->tokens[] = new Token\WhenToken($value);

        return false;
    }

    protected function processEvaluation($value)
    {
        $this->tokens[] = new Token\EvaluationToken($value);

        return false;
    }

    protected function processExpression($value, array $remainingMatches)
    {
        $this->push($remainingMatches);

        $regex = '/^(?:
            (?<bool>TRUE|FALSE)
            |
            \+?(?<integer>\-?[\d\s]+)
            |
            (?<property>.+)
        $)/x';

        $this->push($m = $this->match($value, $regex));

        return true;
    }

    private function push($value)
    {
        array_unshift($this->stack, $value);
    }
}
