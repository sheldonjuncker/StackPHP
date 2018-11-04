<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class KeywordUnitTest extends LexerTest
{
	public function testGoodKeywords()
	{
		$keywords = Token::getKeywords();
		$codeToTokenMap = [];
		foreach($keywords as $keywordValue => $tokenType)
		{
			$codeToTokenMap[$keywordValue] = [
				[$tokenType, $keywordValue]
			];
		}
		$this->assertAllCodeToTokens($codeToTokenMap);
	}

	public function testBadKeywords()
	{
		$keywords = Token::getKeywords();
		$keywords = array_merge([
			"else",
			"blah else",
			"Blah",
			"123",
			"lots of crap"
		], $keywords);
		$codeToTokenMap = [];
		foreach($keywords as $keywordValue => $tokenType)
		{
			$keywordValue = ucfirst($keywordValue);

			$codeToTokenMap[$keywordValue] = [
				[$tokenType, $keywordValue]
			];
		}
		$this->assertAllCodeToTokens($codeToTokenMap, true);
	}
}