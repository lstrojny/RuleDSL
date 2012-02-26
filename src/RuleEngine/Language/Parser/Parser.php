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

        while ($this->tokenStream->next([GrammarInterface::T_WHITESPACE])) {
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
        $booleanExpression = $this->booleanExpression();


        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $booleanExpression->addExtraTokens($this->tokenStream->getSkippedTokens());

        $quantifierStatement = $this->quantifierStatement();

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ruleStatement = $this->ruleStatement();
        $quantifierStatement->addExtraTokens($this->tokenStream->getSkippedTokens());

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
        $booleanToken = $this->tokenStream->assert([GrammarInterface::T_BOOLEAN]);

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
        $ifToken = $this->tokenStream->assert([GrammarInterface::T_IF]);
        $ifStatement = new AST\IfStatement($ifToken);

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ifStatement->addExtraTokens($this->tokenStream->getSkippedTokens());

        $quantifierToken = $this->tokenStream->assert([GrammarInterface::T_QUANTIFIER]);

        $quantifierStatement = new AST\QuantifierStatement($quantifierToken, $ifStatement);

        /** Find optional T_MATCH token */
        if ($this->tokenStream->lookAhead([GrammarInterface::T_MATCH], [GrammarInterface::T_WHITESPACE])) {
            $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
            $quantifierStatement->addExtraTokens($this->tokenStream->getSkippedTokens());
            $quantifierStatement->addExtraToken($this->tokenStream->getCurrentToken());
        }

        return $quantifierStatement;
    }

    private function ruleStatement()
    {
        $ifToken = $this->tokenStream->assert([GrammarInterface::T_IF]);

        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $ruleStatement = new AST\RuleStatement($ifToken, $this->genericExpression());
        $ruleStatement->addExtraTokens($this->tokenStream->getSkippedTokens());

        return $ruleStatement;
    }

    private function genericExpression()
    {
        $this->tokenStream->assert([GrammarInterface::T_BOOLEAN, GrammarInterface::T_STRING]);

        if ($this->tokenStream->getCurrentToken('type') === GrammarInterface::T_BOOLEAN) {
            return new AST\GenericExpression($this->booleanExpression());
        }

        return new AST\GenericExpression($this->variableExpression());
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
        $this->tokenStream->next([GrammarInterface::T_WHITESPACE]);
        $this->tokenStream->assert([GrammarInterface::T_STRING]);
    }
}