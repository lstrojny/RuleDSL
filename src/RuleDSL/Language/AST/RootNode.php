<?php
namespace RuleDSL\Language\AST;

use RuleDSL\Language\AST\Visitor\VisitorInterface;

class RootNode extends AbstractNode
{
    private $returnStatements;

    public function __construct(array $token, array $returnStatements)
    {
        $this->returnStatements = $returnStatements;
    }

    public function accept(VisitorInterface $visitor)
    {
        foreach ($this->returnStatements as $returnStatement) {
            $returnStatement->accept($visitor);
        }
        $this->acceptDecoratingTokens($visitor);
    }
}