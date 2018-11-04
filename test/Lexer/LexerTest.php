<?php


namespace Stack\Test\Lexer;


use Stack\Lexer\LexerException;

abstract class LexerTest extends \PHPUnit\Framework\TestCase
{
	public function lexText(string $test): array
	{
		$tmpFile = tmpfile();
		fwrite($tmpFile, $test);
		rewind($tmpFile);

		$lexer = new \Stack\Lexer\Lexer($tmpFile);
		return $lexer->lexAll();
	}

	public function assertTokenTypesAndValues(array $tokensRead, array $typesAndValuesExpected, bool $expectingFailure = false)
	{
		if(!$expectingFailure)
		{
			$this->assertEquals(count($typesAndValuesExpected), count($tokensRead), 'Mismatch in expected and actual token count.');
		}
		else
		{
			if(count($typesAndValuesExpected) !== count($tokensRead))
			{
				$this->assertTrue(true);
				return;
			}
		}

		while($token = array_shift($tokensRead))
		{
			$typeAndValue = array_shift($typesAndValuesExpected);
			if(is_array($typeAndValue))
			{
				$type = $typeAndValue[0];
				$value = $typeAndValue[1];
			}
			else
			{
				$type = $typeAndValue;
				$value = NULL;
			}

			if(!$expectingFailure)
			{
				$this->assertEquals($type, $token->type);
				if($value !== NULL)
				{
					$this->assertEquals($value, $token->value);
				}
			}
			else
			{
				$success = $type == $token->type;
				if($value !== NULL)
				{
					$success = $success && $value == $token->value;
				}

				if($success)
				{
					$this->assertTrue(false);
				}
			}
		}
	}

	public function assertAllCodeToTokens(array $codeToTokenMap, bool $expectingFailure = false)
	{
		foreach($codeToTokenMap as $code => $tokensExpected)
		{
			if(strlen($code) > 1024)
			{
				$codeDisplay = substr($code, 0, 1024) . "...";
			}
			else
			{
				$codeDisplay = $code;
			}

			print "\nTesting code:\n";
			print $codeDisplay . "\n";

			try
			{
				$tokensRead = $this->lexText($code);
			}
			catch(LexerException $e)
			{
				if($expectingFailure)
				{
					continue;
				}
			}

			$this->assertTokenTypesAndValues($tokensRead, $tokensExpected, $expectingFailure);
		}
	}
}