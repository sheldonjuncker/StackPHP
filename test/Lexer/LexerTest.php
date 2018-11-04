<?php


namespace Stack\Test\Lexer;


class LexerTest extends \PHPUnit\Framework\TestCase
{
	public function lexText(string $test): array
	{
		$tmpFile = tmpfile();
		fwrite($tmpFile, $test);
		rewind($tmpFile);

		$lexer = new \Stack\Lexer\Lexer($tmpFile);
		return $lexer->lexAll();
	}

	public function assertTokenTypesAndValues(array $tokensRead, array $typesAndValuesExpected)
	{
		$this->assertEquals(count($typesAndValuesExpected), count($tokensRead), 'Mismatch in expected and actual token count.');

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

			$this->assertEquals($type, $token->type);
			if($value !== NULL)
			{
				$this->assertEquals($value, $token->value);
			}
		}
	}
}