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

    /**
     * rootNode = { returnStatements }
     *
     * @return \RuleEngine\Language\AST\RootNode
     */
    private function rootNode()
    {
        return new AST\RootNode($this->tokens, $this->returnStatements());
    }

    /**
     * returnStatements = { returnStatement }
     *
     * @return \RuleEngine\Language\AST\ReturnStatement[]
     */
    private function returnStatements()
    {
        $returnStatements = [];

        while ($this->nextToken()) {
            $returnStatements[] = $this->returnStatement();
        }

        return $returnStatements;
    }

    /**
     * returnStatement = "RETURN" booleanExpression quantifierStatement ruleStatement
     *
     * @return \RuleEngine\Language\AST\ReturnStatement
     */
    private function returnStatement()
    {
        $returnExtraTokens = $quantifierExtraTokens = $booleanExtraTokens = [];

        $this->assertToken([Lexer::T_RETURN]);
        $returnToken = $this->currentToken();

        $this->nextToken($returnExtraTokens);
        $booleanExpression = $this->booleanExpression();

        $this->nextToken($booleanExtraTokens);
        $booleanExpression->addExtraTokens($booleanExtraTokens);
        $quantifierStatement = $this->quantifierStatement();

        $this->nextToken();
        $ruleStatement = $this->ruleStatement();

        $returnStatement = new AST\ReturnStatement(
            $returnToken,
            $booleanExpression,
            $quantifierStatement,
            $ruleStatement
        );
        $returnStatement->addExtraTokens($returnExtraTokens);
        return $returnStatement;
    }

    /**
     * booleanExpression = "TRUE" | "FALSE"
     *
     * @return \RuleEngine\Language\AST\BooleanExpression
     */
    private function booleanExpression()
    {
        $booleanToken = $this->assertToken([Lexer::T_BOOLEAN]);

        return new AST\BooleanExpression($booleanToken);
    }

    /**
     * quantifierStatement = ifStatement ("ANY" | "ALL") ["MATCH"]
     *
     * @return \RuleEngine\Language\AST\QuantifierStatement
     */
    private function quantifierStatement()
    {
        $ifExtraTokens = [];
        $ifToken = $this->assertToken([Lexer::T_IF]);
        $ifStatement = new AST\IfStatement($ifToken);

        $this->nextToken($ifExtraTokens);
        $ifStatement->addExtraTokens($ifExtraTokens);

        $quantifierToken = $this->assertToken([Lexer::T_QUANTIFIER]);

        $quantifierStatement = new AST\QuantifierStatement($quantifierToken, $ifStatement);

        /** Find optional T_MATCH token */
        if ($quantifierExtraTokens = $this->tryAhead([Lexer::T_MATCH])) {
            $quantifierStatement->addExtraToken($quantifierExtraTokens);
        }

        return $quantifierStatement;
    }

    private function ruleStatement()
    {
        $ifToken = $this->assertToken([Lexer::T_IF]);

        $this->nextToken();
        $ruleStatement = new AST\RuleStatement($this->genericExpression());
        $ruleStatement->addExtraToken($ifToken);
        return $ruleStatement;
    }

    private function genericExpression()
    {
        $this->assertToken([Lexer::T_BOOLEAN, Lexer::T_STRING]);

        if ($this->currentToken('type') === Lexer::T_BOOLEAN) {
            return new AST\GenericExpression($this->booleanExpression());
        }

        return new AST\GenericExpression($this->variableExpression());
    }

    private function variableExpression()
    {
        $this->assertToken([Lexer::T_STRING]);

        if ($this->tryAhead([Lexer::T_OF], [Lexer::T_WHITESPACE, Lexer::T_STRING])) {
            return $this->propertyExpression();
        }

        return $this->singleVariableExpression();
    }

    private function singleVariableExpression()
    {
        return new AST\VariableExpression($this->captureAhead([Lexer::T_WHITESPACE, Lexer::T_STRING]));
    }

    private function propertyExpression()
    {
        $tokens = $this->captureAhead([Lexer::T_WHITESPACE, Lexer::T_STRING], [Lexer::T_OF]);
        var_dump($tokens);
    }

    private function captureAhead(array $tokens, array $until = [Lexer::T_END])
    {
        var_dump($tokens);

        $position = $this->position;

        $captured = [];

        while (in_array($this->tokens[$position]['type'], array_merge($tokens, $until), true)) {
            if ($position >= $this->tokenCount) {
                $this->syntaxError($tokens);
            }

            if (in_array($this->tokens[$position]['type'], $until, true)) {
                $this->position = $position;
                return $captured;
            }

            $captured[] = $this->tokens[$position];

            ++$position;
        }

        $this->syntaxError($tokens);
    }

    private function tryAhead(array $tokens, array $ignore = [Lexer::T_WHITESPACE])
    {
        $position = $this->position;
        $captured = [];

        do {
            if (++$position >= $this->tokenCount) {
                return false;
            }

            if (in_array($this->tokens[$position]['type'], $tokens)) {
                $this->position = $position;
                return $captured;
            }

            $captured[] = $this->tokens[$position];

        } while (in_array($this->tokens[$position]['type'], $ignore, true));

        return false;
    }

    private function currentToken($field = null)
    {
        $currentToken = $this->tokens[$this->position];

        if ($field !== null) {
            return $currentToken[$field];
        }

        return $currentToken;
    }

    private function nextToken(array &$extraTokens = [], array $ignore = [Lexer::T_WHITESPACE])
    {
        do {
            if (++$this->position >= $this->tokenCount) {
                return false;
            }
            $extraTokens[] = $this->currentToken();
        } while (in_array($this->currentToken('type'), $ignore, true));

        array_pop($extraTokens);

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
            Lexer::getTokenName($this->currentToken('type')),
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