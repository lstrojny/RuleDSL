<?php
namespace RuleEngine\Language\Parser;

use RuleEngine\Language\AST;
use RuleEngine\Language\Lexer\Lexer;

class Parser
{
    private $tokens = [];

    private $tokenCount = 0;

    private $position = -1;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->tokenCount = count($tokens);
    }

    public function parse()
    {
        return $this->rootNode();
    }

    private function rootNode()
    {
        return new AST\RootNode($this->tokens, $this->returnStatements());
    }

    private function returnStatements()
    {
        $returnStatements = [];

        while ($this->nextToken()) {
            $returnStatements[] = $this->returnStatement();
        }

        return $returnStatements;
    }

    private function returnStatement()
    {
        $this->assertToken([Lexer::T_RETURN]);
        $returnToken = $this->currentToken();

        $this->nextToken();
        $booleanExpression = $this->booleanExpression();

        $this->nextToken();
        $quantifierExpression = $this->quantifierExpression();

        $this->nextToken();
        $ruleStatement = $this->ruleStatement();

        return new AST\ReturnStatement(
            $returnToken,
            $booleanExpression,
            $quantifierExpression,
            $ruleStatement
        );
    }

    private function booleanExpression()
    {
        $booleanToken = $this->assertToken([Lexer::T_BOOLEAN]);

        return new AST\BooleanExpression($booleanToken);
    }

    private function quantifierExpression()
    {
        $this->assertToken([Lexer::T_IF]);

        $this->nextToken();
        $quantifierToken = $this->assertToken([Lexer::T_QUANTIFIER]);

        return new AST\QuantifierExpression($quantifierToken);
    }

    private function ruleStatement()
    {
        $this->assertToken([Lexer::T_MATCH, Lexer::T_IF]);

        if ($this->currentToken('type') === Lexer::T_MATCH) {
            $this->nextToken();
        }

        $this->assertToken([Lexer::T_IF]);

        $this->nextToken();
        $booleanExpression = $this->booleanExpression();
        return new AST\RuleStatement($booleanExpression);
    }

    private function currentToken($field = null)
    {
        $currentToken = $this->tokens[$this->position];

        if ($field !== null) {
            return $currentToken[$field];
        }

        return $currentToken;
    }

    private function nextToken(array $ignore = [Lexer::T_WHITESPACE])
    {
        do {
            if (++$this->position >= $this->tokenCount) {
                return false;
            }
        } while (in_array($this->currentToken('type'), $ignore, true));

        return true;
    }

    private function syntaxError(array $tokens)
    {
        throw new InvalidSyntaxException(
            array_map(
                array('RuleEngine\Language\Lexer\Lexer', 'getTokenName'),
                $tokens
            ),
            $this->currentToken('value'),
            $this->currentToken('start'),
            $this->currentToken('end'),
            $this->currentToken('line'),
            $this->getSurroundingValues()
        );
    }

    private function getSurroundingValues()
    {
        $left = '';
        $matches = 0;
        for ($a = $this->position - 1; $a >= 0 && $matches < 3; --$a) {
            if ($this->tokens[$a]['type'] !== Lexer::T_WHITESPACE) {
                ++$matches;
            }

            $left = $this->tokens[$a]['value'] . $left;
        }

        $matches = 0;
        $right = '';
        for ($b = $this->position + 1; $b < $this->tokenCount && $matches < 3; ++$b) {
            if ($this->tokens[$b]['type'] !== Lexer::T_WHITESPACE) {
                ++$matches;
            }

            $right .= $this->tokens[$b]['value'];
        }

        return $left . $this->currentToken('value') . $right;
    }

    private function assertToken(array $tokens)
    {
        if (!in_array($this->currentToken('type'), $tokens, true)) {
            $this->syntaxError($tokens);
        }

        return $this->currentToken();
    }
}