<?php
namespace RuleEngine\Language\AST;

class RootNode extends AbstractNode
{
    private $returnStatements;

    public function __construct(array $token, array $returnStatements)
    {
        $this->returnStatements = $returnStatements;
    }

    public function getReturnStatements()
    {
        return $this->returnStatements;
    }
}