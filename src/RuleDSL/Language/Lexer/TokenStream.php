<?php
namespace RuleDSL\Language\Lexer;

use Countable;

class TokenStream implements Countable
{
    private $tokens;

    private $extraTokens = [];

    private $count;

    private $position = -1;

    private $grammar;

    public function __construct(array $tokens, GrammarInterface $grammar)
    {
        $this->tokens = $tokens;
        $this->count = count($tokens);
        $this->grammar = $grammar;
    }

    public function toArray()
    {
        return $this->tokens;
    }

    public function count()
    {
        return count($this->tokens);
    }

    public function getCurrentToken($field = null)
    {
        if ($field !== null) {
            return $this->tokens[$this->position][$field];
        }

        return $this->tokens[$this->position];
    }

    public function getSkippedTokens()
    {
        return $this->extraTokens;
    }

    public function next(array $ignoreTypes = [])
    {
        $this->extraTokens = [];

        do {
            if (!isset($this->tokens[$this->position + 1])) {
                return false;
            }

            ++$this->position;
            $this->extraTokens[] = $this->getCurrentToken();
        } while (in_array($this->getCurrentToken('type'), $ignoreTypes, true));

        array_pop($this->extraTokens);
        return true;
    }

    public function assert(array $expectedTokens)
    {
        if (!in_array($this->getCurrentToken('type'), $expectedTokens, true)) {
            $this->unexpectedToken($expectedTokens);
        }

        return $this->getCurrentToken();
    }

    public function captureNext(array $expectedTypes, array $stopTypes)
    {
        $capturedTokens = [];

        for ($newPosition = $this->position; $newPosition < $this->count; ++$newPosition) {

            if (in_array($this->tokens[$newPosition]['type'], $stopTypes)) {
                $this->position = $newPosition;
                return $capturedTokens;
            }

            if (!in_array($this->tokens[$newPosition]['type'], $expectedTypes)) {
                break;
            }

            $capturedTokens[] = $this->tokens[$newPosition];
        }

        $this->position = $newPosition;
        $this->unexpectedToken($expectedTypes);
    }

    public function lookAhead(array $expectedTypes, array $ignoreTypes = [])
    {
        for ($position = $this->position + 1; $position < $this->count; ++$position) {

            if (in_array($this->tokens[$position]['type'], $ignoreTypes)) {
                continue;
            }

            return in_array($this->tokens[$position]['type'], $expectedTypes);
        }
    }

    private function unexpectedToken(array $expectedTypes)
    {
        throw new UnexpectedTokenException(
            array_map(
                [$this->grammar, 'getTokenName'],
                $expectedTypes
            ),
            $this->getCurrentToken('value'),
            $this->grammar->getTokenName($this->getCurrentToken('type')),
            $this->getCurrentToken('start'),
            $this->getCurrentToken('end'),
            $this->getCurrentToken('line'),
            $this->getValues(array_slice($this->tokens, ($this->position - 7 > 0 ? $this->position - 7 : 0), $this->position + 7))
        );
    }

    private function getValues(array $tokens)
    {
        return array_reduce($tokens, function($result, $token) {
            return $result . $token['value'];
        });
    }
}