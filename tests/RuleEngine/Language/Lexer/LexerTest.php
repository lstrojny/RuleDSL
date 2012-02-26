<?php
namespace RuleEngine\Language\Lexer;

class LexerTest extends \PHPUnit_Framework_TestCase
{
    public function test_T_END()
    {
        $lexer = new Lexer('', new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(1, $tokens);
        $this->assertSame(
            [
                'value' => '',
                'line'  => 1,
                'type'  => Grammar::T_END,
                'start' => 0,
                'end'   => 0,
            ],
            $tokens[0]
        );
    }

    public function test_T_WHITESPACE_T_END()
    {
        $lexer = new Lexer(' ', new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(2, $tokens);
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 0,
                'end'   => 1,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => '',
                'line'  => 1,
                'type'  => Grammar::T_END,
                'start' => 1,
                'end'   => 1,
            ],
            $tokens[1]
        );
    }

    public function test_T_BOOLEAN()
    {
        $lexer = new Lexer("RETURN TRUE\nFALSE", new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(6, $tokens);
        $this->assertSame(
            [
                'value' => 'RETURN',
                'line'  => 1,
                'type'  => Grammar::T_RETURN,
                'start' => 0,
                'end'   => 6,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 6,
                'end'   => 7,
            ],
            $tokens[1]
        );
        $this->assertSame(
            [
                'value' => 'TRUE',
                'line'  => 1,
                'type'  => Grammar::T_BOOLEAN,
                'start' => 7,
                'end'   => 11,
            ],
            $tokens[2]
        );
        $this->assertSame(
            array(
                'value' => "\n",
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 11,
                'end'   => 12,
            ),
            $tokens[3]
        );
        $this->assertSame(
            [
                'value' => 'FALSE',
                'line'  => 2,
                'type'  => Grammar::T_BOOLEAN,
                'start' => 12,
                'end'   => 17,
            ],
            $tokens[4]
        );
        $this->assertSame(
            [
                'value' => '',
                'line'  => 2,
                'type'  => Grammar::T_END,
                'start' => 17,
                'end'   => 17,
            ],
            $tokens[5]
        );
    }

    public function test_T_QUANTIFIER_T_MATCH()
    {
        $lexer = new Lexer("ANY\nALL\nMATCH", new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(6, $tokens);
        $this->assertSame(
            [
                'value' => 'ANY',
                'line'  => 1,
                'type'  => Grammar::T_QUANTIFIER,
                'start' => 0,
                'end'   => 3,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => "\n",
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 3,
                'end'   => 4,
            ],
            $tokens[1]
        );
        $this->assertSame(
            [
                'value' => 'ALL',
                'line'  => 2,
                'type'  => Grammar::T_QUANTIFIER,
                'start' => 4,
                'end'   => 7,
            ],
            $tokens[2]
        );
        $this->assertSame(
            [
                'value' => "\n",
                'line'  => 2,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 7,
                'end'   => 8,
            ],
            $tokens[3]
        );
        $this->assertSame(
            array(
                'value' => 'MATCH',
                'line'  => 3,
                'type'  => Grammar::T_MATCH,
                'start' => 8,
                'end'   => 13,
            ),
            $tokens[4]
        );
        $this->assertSame(
            array(
                'value' => '',
                'line'  => 3,
                'type'  => Grammar::T_END,
                'start' => 13,
                'end'   => 13,
            ),
            $tokens[5]
        );
    }

    public function test_T_IF()
    {
        $lexer = new Lexer("IF WHEN", new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(4, $tokens);
        $this->assertSame(
            [
                'value' => 'IF',
                'line'  => 1,
                'type'  => Grammar::T_IF,
                'start' => 0,
                'end'   => 2,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 2,
                'end'   => 3,
            ],
            $tokens[1]
        );
        $this->assertSame(
            [
                'value' => 'WHEN',
                'line'  => 1,
                'type'  => Grammar::T_IF,
                'start' => 3,
                'end'   => 7,
            ],
            $tokens[2]
        );
        $this->assertSame(
            [
                'value' => '',
                'line'  => 1,
                'type'  => Grammar::T_END,
                'start' => 7,
                'end'   => 7,
            ],
            $tokens[3]
        );
    }

    public function test_T_OF()
    {
        $lexer = new Lexer('PROPERTY_NAME OF OBJECT_NAME', new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(6, $tokens);
        $this->assertSame(
            [
                'value' => 'PROPERTY_NAME',
                'line'  => 1,
                'type'  => Grammar::T_STRING,
                'start' => 0,
                'end'   => 13,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 13,
                'end'   => 14,
            ],
            $tokens[1]
        );
        $this->assertSame(
            [
                'value' => 'OF',
                'line'  => 1,
                'type'  => Grammar::T_OF,
                'start' => 14,
                'end'   => 16,
            ],
            $tokens[2]
        );
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 16,
                'end'   => 17,
            ],
            $tokens[3]
        );
        $this->assertSame(
            [
                'value' => 'OBJECT_NAME',
                'line'  => 1,
                'type'  => Grammar::T_STRING,
                'start' => 17,
                'end'   => 28,
            ],
            $tokens[4]
        );
        $this->assertSame(
            [
                'value' => '',
                'line'  => 1,
                'type'  => Grammar::T_END,
                'start' => 28,
                'end'   => 28,
            ],
            $tokens[5]
        );
    }

    public function test_T_IS()
    {
        $lexer = new Lexer("IS SOMETHING", new Grammar());
        $tokens = $lexer->scan();
        $tokens = $tokens->toArray();
        $this->assertCount(4, $tokens);
        $this->assertSame(
            [
                'value' => 'IS',
                'line'  => 1,
                'type'  => Grammar::T_IS,
                'start' => 0,
                'end'   => 2,
            ],
            $tokens[0]
        );
        $this->assertSame(
            [
                'value' => ' ',
                'line'  => 1,
                'type'  => Grammar::T_WHITESPACE,
                'start' => 2,
                'end'   => 3,
            ],
            $tokens[1]
        );
        $this->assertSame(
            [
                'value' => 'SOMETHING',
                'line'  => 1,
                'type'  => Grammar::T_STRING,
                'start' => 3,
                'end'   => 12,
            ],
            $tokens[2]
        );
        $this->assertSame(
            [
                'value' => '',
                'line'  => 1,
                'type'  => Grammar::T_END,
                'start' => 12,
                'end'   => 12,
            ],
            $tokens[3]
        );
    }
}