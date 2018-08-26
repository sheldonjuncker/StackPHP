<?php

namespace Stack\Lexer\Reader;

use Stack\Lexer\Token;

class StringReader extends Reader
{
	public function read(string $start): Token
	{
		$lexer = $this->lexer;
		$token = new Token(Token::STRING, "", $lexer->getLocation());

		while(true)
		{
			$c = $lexer->readCharacter();

			//Done, found end of string
			if($c == '"')
			{
				break;
			}

			//Unexpected EOF
			if($c === '')
			{
				throw new \Exception("Unexpected EOF while lexing string.");
			}

			//Check for escape character
			if($c == "\\")
			{
				//Get the escaped character
				$token->value .= $this->getEscapedCharacter();
			}

			else
			{
				$token->value .= $c;
			}
		}

		return $token;
	}

	public function getEscapedCharacter(): string
	{
		//Get next character
		$c = $this->readCharacter();

		//Standard escape characters
		$normalEscapeCharacters = [
			"a" => chr(7),
			"b" => chr(8),
			"t" => chr(9),
			"n" => chr(10),
			"v" => chr(11),
			"f" => chr(12),
			"r" => chr(13),
			"e" => chr(27)
		];

		if(isset($normalEscapeCharacters[$c]))
		{
			return $normalEscapeCharacters[$c];
		}

		//Octal characters
		if($this->isOctal($c))
		{
			$octal = $c;
			//Get up to 2 more characters if they are digits less than 8
			$c2 = $this->readCharacter();
			if($this->isOctal($c2))
			{
				$octal .= $c2;
				$c3 = $this->readCharacter();
				if($this->isOctal($c3))
				{
					$octal .= $c3;
				}

				else
				{
					$this->lexer->unreadCharacter($c3);
				}
			}

			else
			{
				$this->lexer->unreadCharacter($c2);
			}

			return chr(octdec($octal));
		}

		//Hexadecimal escape characters
		else if($c == "x")
		{
			$c2 = $this->readCharacter();
			if($this->isHex($c2))
			{
				$hex = $c2;
				$c3 = $this->readCharacter();
				if($this->isHex($c3))
				{
					$hex .= $c3;
				}

				else
				{
					$this->lexer->unreadCharacter($c3);
				}

				return chr(hexdec($hex));
			}

			else
			{
				$this->lexer->unreadCharacter($c2);
			}
		}

		//Literal character escape
		return $c;
	}

	/*
	 * Reads a character and throws an exception upon EOF.
	 */
	protected function readCharacter(): string
	{
		$c = $this->lexer->readCharacter();
		if($c === '')
		{
			throw new \Exception("Unexpected EOF while lexing string.");
		}
		return $c;
	}

	/*
	 * Tests to see if a character is octal.
	 */
	public function isOctal(string $c): bool
	{
		return (ord($c) >= 0 + 48) && (ord($c) <= 7 + 48);
	}

	/*
	 * Tests to see if a character is hex.
	 */
	public function isHex(string $c): bool
	{
		return ((ord($c) >= 0 + 48) && (ord($c) <= 9 + 48)) || in_array(strtoupper($c), ["A", "B", "C", "D", "E", "F"]);
	}
}