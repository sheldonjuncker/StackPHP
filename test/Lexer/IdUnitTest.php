<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class IdUnitTest extends LexerTest
{
	public function testGoodIds()
	{
		$ids = [
			"f",
			"foobar",
			"foobar123",
			"foo1bar2",
			"fooBar",
			"fooBar123",
			"f01ooB02aR1994",
			"thisisareallyreall3ylongvariablenameandyour1eallysho4uldnotbeallowedtodothisb9utimwaytoolenientinmylexing"
		];

		$codeToTokenMap = [];
		foreach($ids as $id)
		{
			$codeToTokenMap[$id] = [
				[Token::ID, $id]
			];
		}

		$this->assertAllCodeToTokens($codeToTokenMap);
	}

	public function testBadIds()
	{
		$ids = [
			"123",
			"_",
			"foo_bar",
			"Foo",
			"FooBar",
			"foo bar"
		];

		$codeToTokenMap = [];
		foreach($ids as $id)
		{
			$codeToTokenMap[$id] = [
				[Token::ID, $id]
			];
		}

		$this->assertAllCodeToTokens($codeToTokenMap, true);
	}
}