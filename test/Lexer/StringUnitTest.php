<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class StringUnitTest extends LexerTest
{
	public function testGoodStrings()
	{
		$escapeMappings = [
			"a" => chr(7),
			"b" => chr(8),
			"t" => chr(9),
			"n" => chr(10),
			"v" => chr(11),
			"f" => chr(12),
			"r" => chr(13),
			"e" => chr(27)
		];

		$strings = [
			// Normal
			"I'm a string." => NULL,

			//Empty
			"" => NULL,

			//Quotation
			"\\\"" => "\"",

			//Multiline
			"
			I'm a 
			multi line
			string.
			" => NULL,

			//Octal
			"\\0" => "\0",
			"\\000" => "\000",
			"\\001" => "\001",
			"\\010" => "\010",
			"\\7" => "\7",
			"\\377" => "\377",

			//Hex
			"\\xAA" => hex2bin("AA"),
			"\\x00" => hex2bin("00"),
			"\\xA0" => hex2bin("A0"),
			"\\x0A" => hex2bin("0A"),

			//Non-escapable
			"\\xGA" => "xGA",
			"\q" => "q",

			//Big string
			str_repeat("a", 1024 * 1024) => NULL,
		];

		//Test all escape sequences
		foreach ($escapeMappings as $escapeChar => $ascii) {
			$strings["\\" . $escapeChar] = $ascii;
			}

			$codeToTokenMap = [];
			foreach ($strings as $code => $value) {
				$quotedCode = '"' . $code . '"';

				if ($value === NULL) {
					$value = $code;
				}

				$codeToTokenMap[$quotedCode] = [
					[Token::STRING, $value]
				];
			}

			$this->assertAllCodeToTokens($codeToTokenMap);
	}

	public function testBadStrings()
	{
		$strings = [
			"\"" => NULL,
			"\\" => NULL,
			"\\777" => NULL
		];

		$codeToTokenMap = [];
		foreach ($strings as $code => $value) {
			$quotedCode = '"' . $code . '"';

			if ($value === NULL) {
				$value = $code;
			}

			$codeToTokenMap[$quotedCode] = [
				[Token::STRING, $value]
			];
		}

		$this->assertAllCodeToTokens($codeToTokenMap, true);
	}
}