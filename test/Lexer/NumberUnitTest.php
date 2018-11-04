<?php

namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class NumberUnitTest extends LexerTest
{
	public function testGoodNumbers()
	{
		$codeToTokenMap = [
			"3" => [
				[Token::NUM, "3"]
			],
			"-3" => [
				[Token::NUM, "-3"]
			],
			"3.14" => [
				[Token::NUM, "3.14"]
			],
			"-3.14" => [
				[Token::NUM, "-3.14"]
			],
			"10e2" => [
				[Token::NUM, "10e2"]
			],
			"-10e-2" => [
				[Token::NUM, "-10e-2"]
			],
			"-3.14e2" => [
				[Token::NUM, "-3.14e2"]
			],
			"3.14e-2" => [
				[Token::NUM, "3.14e-2"]
			],
			"0.00" => [
				[Token::NUM, "0.00"]
			],
			"-0" => [
				[Token::NUM, "-0"]
			],
		];

		$this->assertAllCodeToTokens($codeToTokenMap);
	}

	public function testBadNumbers()
	{
		$codeToTokenMap = [
			"3.14e-3.14" => [
				Token::NUM
			],
			"10e2.5" => [
				Token::NUM
			],
			"foobar" => [
				Token::NUM
			],
			"5 + 5" => [
				Token::NUM,
			],
			"- 3.14" => [
				Token::NUM
			],
			"3.14-" => [
				Token::NUM
			],
			"3.-14" => [
				Token::NUM
			],
			"3." => [
				Token::NUM
			],
			".3" => [
				Token::NUM
			],
		];

		$this->assertAllCodeToTokens($codeToTokenMap, true);
	}
}