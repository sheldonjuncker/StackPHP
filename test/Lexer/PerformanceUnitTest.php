<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Lexer;

class PerformanceUnitTest extends LexerTest
{
	public function testPerformance()
	{
		$file = fopen(__DIR__ . "/data/large.test", "r");
		$lexer = new Lexer($file);
		while($lexer->lex());
		$this->assertTrue(true);
	}
}