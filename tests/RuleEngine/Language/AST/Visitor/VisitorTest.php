<?php
namespace RuleEngine\Language\AST\Visitor;

use RuleEngine\Language\Lexer\Lexer;
use RuleEngine\Language\AST;
use RuleEngine\Language\Lexer\Grammar;

class TestVisitor implements VisitorInterface
{
    private $visits = [];

    private $grammar;

    public function __construct(Grammar $grammar)
    {
        $this->grammar = $grammar;
    }

    public function visitBooleanExpression(AST\BooleanExpression $booleanExpression)
    {
        $this->track(__FUNCTION__, $booleanExpression);
    }

    public function visitQuantifierStatement(AST\QuantifierStatement $quantifierStatement)
    {
        $this->track(__FUNCTION__, $quantifierStatement);
    }

    public function visitReturnStatement(AST\ReturnStatement $returnStatement)
    {
        $this->track(__FUNCTION__, $returnStatement);
    }

    public function visitRootNode(AST\RootNode $rootNode)
    {
        $this->track(__FUNCTION__, 'rootNode');
    }

    public function visitRuleStatement(AST\RuleStatement $ruleStatement)
    {
        $this->track(__FUNCTION__, $ruleStatement);
    }

    public function visitVariableExpression(AST\VariableExpression $expression)
    {
        $this->track(__FUNCTION__, $expression);
    }

    public function visitToken(array $token)
    {
        $this->track(__FUNCTION__, $token);
    }

    public function visitExtraToken(array $token)
    {
        $this->track(__FUNCTION__, $token);
    }

    public function visitIfStatement(AST\IfStatement $ifStatement)
    {
        $this->track(__FUNCTION__, $ifStatement);
    }

    public function getVisits()
    {
        return $this->visits;
    }

    private function track($methodName, $client)
    {
        if (is_object($client)) {
            $name = get_class($client);
            $name = substr($name, strrpos($name, '\\') + 1);
        } elseif (is_array($client)) {
            $name = [$client['value'], $this->grammar->getTokenName($client['type'])];
        }
        $this->visits[] = [$methodName, $name];
    }
}

class VisitorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $grammar = new Grammar();
        $this->visitor = new TestVisitor($grammar);
        $this->lexer = new Lexer('', $grammar);
    }

    public function testVisitBooleanExpression()
    {
        $exp = new AST\BooleanExpression($this->lexer->getToken(['TRUE', 10]));
        $exp->accept($this->visitor);
        $this->assertSame(
            [
                ['visitBooleanExpression', 'BooleanExpression'],
                ['visitToken', ['TRUE', 'T_BOOLEAN']],
            ],
            $this->visitor->getVisits()
        );
    }

    public function testVisitBooleanExpressionWithExtraSpace()
    {
        $exp = new AST\BooleanExpression($this->lexer->getToken(['TRUE', 10]));
        $exp->addExtraToken($this->lexer->getToken([' ', 14]));
        $exp->addExtraToken($this->lexer->getToken(["\n", 15]));
        $exp->accept($this->visitor);
        $this->assertSame(
            [
                ['visitBooleanExpression', 'BooleanExpression'],
                ['visitToken', ['TRUE', 'T_BOOLEAN']],
                ['visitExtraToken', [' ', 'T_WHITESPACE']],
                ['visitExtraToken', ["\n", 'T_WHITESPACE']],
            ],
            $this->visitor->getVisits()
        );
    }

    public function testVisitQuantifierStatement()
    {
        $exp = new AST\QuantifierStatement(
            $this->lexer->getToken(['ANY', 1]),
            new AST\IfStatement($this->lexer->getToken(['IF', 2]))
        );
        $exp->addExtraToken($this->lexer->getToken([' ', 5]));
        $exp->accept($this->visitor);
        $this->assertSame(
            [
                ['visitQuantifierStatement', 'QuantifierStatement'],
                ['visitIfStatement', 'IfStatement'],
                ['visitToken', ['IF', 'T_IF']],
                ['visitToken', ['ANY', 'T_QUANTIFIER']],
                ['visitExtraToken', [' ', 'T_WHITESPACE']],
            ],
            $this->visitor->getVisits()
        );
    }
}