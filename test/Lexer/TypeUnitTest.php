<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class TypeUnitTest extends LexerTest
{
	public function testGoodTypes()
	{
		$ids = [
			"F",
			"Int",
			"String",
			"Cat",
			"CatBat",
			"IAmGroot2",
			"WHYNOT",
			"Thisisareallyreall3ylongtypenameandyour1eallysho4uldnotbeallowedtodothisb9utimwaytoolenientinmylexing"
		];

		$codeToTokenMap = [];
		foreach($ids as $id)
		{
			$codeToTokenMap[$id] = [
				[Token::TYPE, $id]
			];
		}

		$this->assertAllCodeToTokens($codeToTokenMap);
	}

	public function testBadTypes()
	{
		$ids = [
			"123",
			"_",
			"Foo_bar",
			"Foo bar",
			"fooBar",
			"foobar"
		];

		$codeToTokenMap = [];
		foreach($ids as $id)
		{
			$codeToTokenMap[$id] = [
				[Token::TYPE, $id]
			];
		}

		$this->assertAllCodeToTokens($codeToTokenMap, true);
	}
}