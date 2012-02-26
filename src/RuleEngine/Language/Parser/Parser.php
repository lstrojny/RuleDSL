<?php
namespace RuleEngine\Language\Parser;

use RuleEngine\Language\AST;
use RuleEngine\Language\Lexer\GrammarInterface;
use RuleEngine\Language\Lexer\TokenStream;

class Parser
{
    private $tokenStream;

    private $grammar;

    private $tokenCount = 0;

    private $position = -1;

    public function __construct(TokenStream $tokenStream, GrammarInterface $grammar)
    {
        $this->tokenStream = $tokenStream;
        $this->tokenCount = count($tokenStream);
        $this->grammar = $grammar;
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
        return new AST\RootNode($this->tokenStream->toArray(), $this->returnStatements());
    }

    /**
     * returnStatements = { returnStatement }
     *
     * @return \RuleEngine\Language\AST\ReturnStatement[]
     */
    private function returnStatements()
    {
        $returnStatements = [];

        while ($this->tokenStream->next([GrammarInterface::T_WHITESPACE, GrammarInterface::T_END])) {
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
        $this->tokenStream->assert([GrammarInterface::T_RETURN]);
        $returnToken = $this->tokenStream->getCurrentToken();

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $returnExtraTokens = $this->tokenStream->getSkippedTokens();
        $valueExpression = $this->valueExpression();

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $valueExpression->addDecoratingTokens($this->tokenStream->getSkippedTokens());
        $quantifierStatement = $this->quantifierStatement();

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ruleStatement = $this->ruleStatement();
        $quantifierStatement->addDecoratingTokens($this->tokenStream->getSkippedTokens());

        $returnStatement = new AST\ReturnStatement(
            $returnToken,
            $valueExpression,
            $quantifierStatement,
            $ruleStatement
        );
        $returnStatement->addDecoratingTokens($returnExtraTokens);
        return $returnStatement;
    }

    /**
     * valueExpression = numericExpression | booleanExpression | stringExpression
     */
    private function valueExpression()
    {
        $this->tokenStream->assert(
            [
                GrammarInterface::T_NUMBER,
                GrammarInterface::T_MINUS,
                GrammarInterface::T_PLUS,
                GrammarInterface::T_BOOLEAN,
            ]
        );

        switch ($this->tokenStream->getCurrentToken('type')) {
            case GrammarInterface::T_MINUS:
            case GrammarInterface::T_PLUS:
            case GrammarInterface::T_NUMBER:
                return $this->numericExpression();
                break;

            case GrammarInterface::T_BOOLEAN:
                return $this->booleanExpression();
                break;
        }
    }

    /**
     * numericExpression = [ "+" | "-" ]
     * ( "0"
     *      | ( "1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" )
     *      [ ( "0" | "1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" ) ]
     * )
     */
    private function numericExpression()
    {
        $algebraicSign = null;
        $signs = [GrammarInterface::T_MINUS, GrammarInterface::T_PLUS];
        if (in_array($this->tokenStream->getCurrentToken('type'), $signs)) {
            $algebraicSign = $this->tokenStream->getCurrentToken();
            $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        }
        $this->tokenStream->assert([GrammarInterface::T_NUMBER]);

        $numberToken = $this->tokenStream->getCurrentToken();
        return new AST\NumericExpression($numberToken, $algebraicSign);
    }

    /**
     * booleanExpression = "TRUE" | "FALSE"
     *
     * @return \RuleEngine\Language\AST\BooleanExpression
     */
    private function booleanExpression()
    {
        $booleanToken = $this->tokenStream->assert([GrammarInterface::T_BOOLEAN]);

        return new AST\BooleanExpression($booleanToken);
    }

    /**
     * quantifierStatement = ifStatement ("ANY" | "ALL") ["RULE" | "RULES"] ["MATCH"]
     *
     * @return \RuleEngine\Language\AST\QuantifierStatement
     */
    private function quantifierStatement()
    {
        $ifToken = $this->tokenStream->assert([GrammarInterface::T_IF]);
        $ifStatement = new AST\IfStatement($ifToken);

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ifStatement->addDecoratingTokens($this->tokenStream->getSkippedTokens());

        $quantifierToken = $this->tokenStream->assert([GrammarInterface::T_QUANTIFIER]);
        $quantifierStatement = new AST\QuantifierStatement($quantifierToken, $ifStatement);

        if ($this->tokenStream->lookAhead([GrammarInterface::T_RULE], [GrammarInterface::T_WHITESPACE])) {
            $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
            $quantifierStatement->addDecoratingTokens($this->tokenStream->getSkippedTokens());
            $quantifierStatement->addDecoratingToken($this->tokenStream->getCurrentToken());
        }

        if ($this->tokenStream->lookAhead([GrammarInterface::T_MATCH], [GrammarInterface::T_WHITESPACE])) {
            $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
            $quantifierStatement->addDecoratingTokens($this->tokenStream->getSkippedTokens());
            $quantifierStatement->addDecoratingToken($this->tokenStream->getCurrentToken());
        }

        return $quantifierStatement;
    }

    private function ruleStatement()
    {
        $ifToken = $this->tokenStream->assert([GrammarInterface::T_IF]);
        $ifStatement = new AST\IfStatement($ifToken);

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ifStatement->addDecoratingTokens($this->tokenStream->getSkippedTokens());

        return new AST\RuleStatement($ifStatement, $this->expression());
    }

    private function expression()
    {
        $this->tokenStream->assert([GrammarInterface::T_BOOLEAN, GrammarInterface::T_STRING]);

        if ($this->tokenStream->getCurrentToken('type') === GrammarInterface::T_BOOLEAN) {
            return $this->booleanExpression();
        }

        return $this->variableExpression();
    }

    private function variableExpression()
    {
        $this->tokenStream->assert([GrammarInterface::T_STRING]);

        if ($this->tokenStream->lookAhead(
            [GrammarInterface::T_OF],
            [GrammarInterface::T_WHITESPACE, GrammarInterface::T_STRING])
        ) {
            return $this->propertyExpression();
        }

        return $this->singleVariableExpression();
    }

    private function singleVariableExpression()
    {
        return new AST\VariableExpression(
            $this->tokenStream->captureNext(
                [GrammarInterface::T_WHITESPACE, GrammarInterface::T_STRING],
                [GrammarInterface::T_END]
            )
        );
    }

    private function propertyExpression()
    {
        $tokens = $this->tokenStream->captureNext(
            [GrammarInterface::T_WHITESPACE, GrammarInterface::T_STRING],
            [GrammarInterface::T_OF]
        );

        $ofToken = $this->tokenStream->getCurrentToken();

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);

        $propertyExpression = new AST\PropertyExpression($tokens, $this->variableExpression());
        $propertyExpression->addDecoratingToken($ofToken);
        $propertyExpression->addDecoratingTokens($this->tokenStream->getSkippedTokens());

        return $propertyExpression;
    }
}