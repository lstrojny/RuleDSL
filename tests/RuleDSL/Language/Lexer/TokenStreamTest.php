<?php
namespace RuleDSL\Language\Lexer;

class TokenStreamTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->grammar = $this->getMockBuilder('RuleDSL\Language\Lexer\Grammar')
                              ->getMock();
        $this->grammar->expects($this->any())
                       ->method('getTokenName')
                       ->will($this->returnArgument(0));
    }

    public function testNextToken()
    {
        $stream = new TokenStream([['type' => 'test'], ['type' => 'foo']], $this->grammar);

        $this->assertTrue($stream->next());
        $this->assertSame(
            ['type' => 'test'],
            $stream->getCurrentToken()
        );

        $this->assertTrue($stream->next());
        $this->assertSame(
            ['type' => 'foo'],
            $stream->getCurrentToken()
        );

        $this->assertFalse($stream->next());
    }

    public function testNextTokenWithSkip()
    {
        $stream = new TokenStream(
            [
                ['type' => 'T_FOO', 'value' => 'FOO'],
                ['type' => 'T_TEST', 'value' => 'TEST'],
                ['type' => 'T_WHITESPACE', 'value' => ' '],
                ['type' => 'T_END', 'value' => '']
            ],
            $this->grammar
        );
        $stream->next(['T_FOO']);
        $this->assertSame(['type' => 'T_TEST', 'value' => 'TEST'], $stream->getCurrentToken());
        $this->assertSame([['type' => 'T_FOO', 'value' => 'FOO']], $stream->getSkippedTokens());
        $stream->next(['T_WHITESPACE']);
        $this->assertSame(['type' => 'T_END', 'value' => ''], $stream->getCurrentToken());
        $this->assertSame([['type' => 'T_WHITESPACE', 'value' => ' ']], $stream->getSkippedTokens());
    }

    public function testNextSkipEnd()
    {
        $stream = new TokenStream(
            [
                ['type' => 'T_END', 'value' => '']
            ],
            $this->grammar
        );
        $this->assertFalse($stream->next(['T_END']));
    }

    public function testCapture()
    {
        $stream = new TokenStream(
            [['type' => 'test'], ['type' => 'foo'], ['type' => 'foo'], ['type' => 'end']],
            $this->grammar
        );

        $stream->next();

        $this->assertSame(
            [
                ['type' => 'test'],
                ['type' => 'foo'],
                ['type' => 'foo'],
            ],
            $stream->captureNext(['test', 'foo'], ['type' => 'end'])
        );

        $this->assertSame(
            ['type' => 'end'],
            $stream->getCurrentToken()
        );
    }

    public function testCaptureThrowsExceptionIfNotFound()
    {
        $stream = new TokenStream(
            [
                ['type' => 'T_TEST', 'value' => 'TEST'],
                ['type' => 'T_FOO', 'line' => 1, 'start' => 2, 'end' => 3, 'value' => 'FOO'],
                ['type' => 'T_FOO', 'value' => 'FOO2'],
                ['type' => 'T_END', 'value' => 'END']
            ],
            $this->grammar
        );
        $stream->next();

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_TEST, got "FOO" (T_FOO) at position 2 - 3, line 1 near "TESTFOOFOO2END"'
        );

        $stream->captureNext(['T_TEST'], ['type' => 'T_END']);
    }

    public function testLookAhead()
    {
        $stream = new TokenStream(
            [
                ['type' => 'T_TEST', 'value' => 'TEST'],
                ['type' => 'T_FOO', 'value' => 'FOO'],
                ['type' => 'T_BAR', 'value' => 'BAR'],
                ['type' => 'T_END', 'value' => 'END']
            ],
            $this->grammar
        );
        $stream->next();

        $this->assertTrue($stream->lookAhead(['T_BAR'], ['T_FOO']));
        $this->assertFalse($stream->lookAhead(['T_BAR'], []));
        $this->assertTrue($stream->lookAhead(['T_FOO'], []));
    }

    public function testAssertToken()
    {
        $stream = new TokenStream(
            [
                ['type' => 'T_TEST', 'value' => 'TEST', 'start' => 0, 'end' => 4, 'line' => 1]
            ],
            $this->grammar
        );
        $stream->next();

        $this->assertSame(['type' => 'T_TEST', 'value' => 'TEST', 'start' => 0, 'end' => 4, 'line' => 1], $stream->assert(['T_TEST']));

        $this->setExpectedException(
            'RuleDSL\Language\Lexer\UnexpectedTokenException',
            'Expected T_FOO, got "TEST" (T_TEST) at position 0 - 4, line 1 near "TEST"'
        );
        $stream->assert(['T_FOO']);
    }
}