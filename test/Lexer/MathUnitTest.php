<?php

namespace Stack\Test\Lexer;

use Stack\LExer\Token;

class MathUnitTest extends LexerTest
{
	public function testOperators()
	{
		$codeToTokenMap = [
			"5 + 5" => [
				[Token::NUM, "5"],
				Token::PLUS,
				[Token::NUM, "5"]
			],
			"5 - 5" => [
				[Token::NUM, "5"],
				Token::MINUS,
				[Token::NUM, "5"]
			],
			"5 * 5" => [
				[Token::NUM, "5"],
				Token::TIMES,
				[Token::NUM, "5"]
			],
			"5 / 5" => [
				[Token::NUM, "5"],
				Token::DIVIDE,
				[Token::NUM, "5"]
			],
			"5 % 5" => [
				[Token::NUM, "5"],
				Token::MOD,
				[Token::NUM, "5"]
			],
			"(5+10) / (10e2 * 3) - (-10.25 % 3)" => [
				Token::LPAREN,
				[Token::NUM, "5"],
				Token::PLUS,
				[Token::NUM, "10"],
				Token::RPAREN,

				Token::DIVIDE,

				Token::LPAREN,
				[Token::NUM, "10e2"],
				Token::TIMES,
				[Token::NUM, "3"],
				Token::RPAREN,

				Token::MINUS,

				Token::LPAREN,
				[Token::NUM, "-10.25"],
				Token::MOD,
				[Token::NUM, "3"],
				Token::RPAREN
			],
		];

		$this->assertAllCodeToTokens($codeToTokenMap);
	}
}