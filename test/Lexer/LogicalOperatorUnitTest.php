<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

/**
 * Test for the logical and comparison operators.
 * Basically the operators that result in booleans.
 *
 * Class LogicalOperatorUnitTest
 * @package Stack\Test\Lexer
 */
class LogicalOperatorUnitTest extends LexerTest
{
	public function testLogicalOperators()
	{
		$this->assertAllCodeToTokens([
			"& | ^ ! && || ^^ !5! ? ??" => [
				Token::AND,
				Token::OR,
				Token::XOR,
				Token::NOT,

				Token::AND,
				Token::AND,

				Token::OR,
				Token::OR,

				Token::XOR,
				Token::XOR,

				Token::NOT,
				Token::NUM,
				Token::NOT,

				Token::QMARK,
				Token::QMARK,
				Token::QMARK
			]
		]);
	}

	public function testComparisonOperators()
	{
		$this->assertAllCodeToTokens([
			"= <5> >= <= == !=" => [
				Token::EQ,

				Token::LT,
				Token::NUM,
				Token::GT,

				Token::GT,
				Token::EQ,

				Token::LT,
				Token::EQ,

				Token::EQ,
				Token::EQ,

				Token::NOT,
				Token::EQ
			]
		]);
	}
}