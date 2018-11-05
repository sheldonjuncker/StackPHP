<?php

namespace Stack\Test\Lexer;
use Stack\Lexer\Token;

/**
 * Tests the basic operators needed for working with objects.
 *
 * Class ObjectUnitTest
 * @package Stack\Test\Lexer
 */
class ObjectUnitTest extends LexerTest
{
	public function testObjectCreation()
	{
		$this->assertAllCodeToTokens([
			'String str = new String("Hello, world!");' => [
				[Token::TYPE, "String"],
				[Token::ID, "str"],
				Token::EQ,
				Token::NEW,
				[Token::TYPE, "String"],
				Token::LPAREN,
				Token::STRING,
				Token::RPAREN,
				Token::SEMI
			]
		]);
	}

	public function testObjectAccessAndAssign()
	{
		$this->assertAllCodeToTokens([
			'cat.mood = moodList.getType("angry");' => [
				[Token::ID, "cat"],
				Token::DOT,
				[Token::ID, "mood"],
				Token::EQ,
				[Token::ID, "moodList"],
				Token::DOT,
				[Token::ID, "getType"],
				Token::LPAREN,
				Token::STRING,
				Token::RPAREN,
				Token::SEMI
			]
		]);
	}
}