<?php

namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

/**
 * Tests punctuation characters.
 * This includes both .,;: but also [](){} as these are also only used for
 * separating things more or less.
 *
 * Class PunctuationUnitTest
 * @package Stack\Test\Lexer
 */
class PunctuationUnitTest extends LexerTest
{
	public function testPunctuation()
	{
		$this->assertAllCodeToTokens([
			". , ; : ( ) [ ] { } .,;:5([]){5+5}" => [
				Token::DOT,
				Token::COMMA,
				Token::SEMI,
				Token::COLON,
				Token::LPAREN,
				Token::RPAREN,
				Token::LBRACKET,
				Token::RBRACKET,
				Token::LBRACE,
				Token::RBRACE,

				Token::DOT,
				Token::COMMA,
				Token::SEMI,
				Token::COLON,
				Token::NUM,
				Token::LPAREN,
				Token::LBRACKET,
				Token::RBRACKET,
				Token::RPAREN,
				Token::LBRACE,
				Token::NUM,
				Token::PLUS,
				Token::NUM,
				Token::RBRACE,
			]
		]);
	}
}