<?php

namespace Stack\Lexer\Reader;

use Stack\Lexer\LexerException;
use Stack\Lexer\Token;

class NumberReader extends Reader
{
	public function read(string $start): Token
	{
		$match = $start;
		$token = new Token(Token::UNKNOWN, "", $this->lexer->getLocation());

		//Matches regex -?[0-9]+(\.[0-9]+)?(-?e[0-9]+)?

		//Account for leading -
		if($start == '-')
		{
			//A digit following means a number, otherwise a minus sign
			$c = $this->readDigit();

			if($c === '')
			{
				$token->type = Token::MINUS;
				$token->value = '-';
				return $token;
			}

			else
			{
				$match .= $c;
			}
		}

		//Look for optional digits
		while(($c = $this->readDigit()) !== '')
		{
			$match .= $c;
		}

		//Look for decimal point
		$c = $this->lexer->readCharacter();
		$decimalsRead = 0;

		if($c == '.')
		{
			$match .= $c;

			//Look for decimal digits
			while(($c = $this->readDigit()) !== '')
			{
				$decimalsRead++;
				$match .= $c;
			}

			if(!$decimalsRead)
			{
				throw new LexerException("Invalid decimal format: A digit must follow the decimal point.");
			}

			//Read next character for exponent
			$c = $this->lexer->readCharacter();
		}

		$exponentsRead = 0;
		if($c == 'e')
		{
			$match .= $c;
			//Look for exponent digits

			$c = $this->lexer->readCharacter();
			if($c == '-')
			{
				$match .= $c;
			}

			else
			{
				$this->lexer->unreadCharacter($c);
			}

			while(($c = $this->readDigit()) !== '')
			{
				$exponentsRead++;
				$match .= $c;
			}

			if(!$exponentsRead)
			{
				throw new LexerException("Invalid decimal format: A digit must follow the exponent sign.");
			}
		}

		else
		{
			$this->lexer->unreadCharacter($c);
		}

		//Convert string to float
		$token->type = Token::NUM;
		$token->value = floatval($match);
		return $token;
	}

	public function readDigit(): string
	{
		$c = $this->lexer->readCharacter();

		if(ctype_digit($c))
		{
			return $c;
		}

		else
		{
			$this->lexer->unreadCharacter($c);
			return '';
		}
	}
}