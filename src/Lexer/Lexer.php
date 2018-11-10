<?php

namespace Stack\Lexer;

use Stack\Lexer\Reader\StringReader;
use Stack\Lexer\Reader\NumberReader;

class Lexer
{
	/** @var resource $stream The stream containing the input text. */
	protected $stream;

	/** @var \SplDoublyLinkedList $lineLengths The lengths of lines in the input for backtracking. */
	protected $lineLengths = NULL;

	/** @var TokenLocation $location The current token location. */
	protected $location = NULL;

	/*
	 * @param $stream Must be a valid stream resource.
	 */
	public function __construct($stream)
	{
		$this->location = new TokenLocation(1, 0);
		$this->lineLengths = new \SplDoublyLinkedList();

		$resourceType = get_resource_type($stream);
		if($resourceType !== "stream")
		{
			throw new LexerException("Invalid resource type: $resourceType.");
		}

		$this->stream = $stream;
	}

	/*
	 * Gets the current location in the input stream.
	 */
	public function getLocation(): TokenLocation
	{
		return $this->location;
	}

	/**
	 * Lexes the input and attempts to return a token.
	 *
	 * @return null|Token
	 */
	public function lex(): ?Token
	{
		//End of input
		if(feof($this->stream))
		{
			return NULL;
		}

		else
		{
			return $this->lexToken();
		}
	}

	/**
	 * Attempts to lex all remaining input into tokens.
	 *
	 * @return array
	 */
	public function lexAll(): array
	{
		$tokens = [];
		while($token = $this->lex())
		{
			$tokens[] = $token;
		}
		return $tokens;
	}

	protected function lexToken(): ?Token
	{
		$c = $this->readCharacter();

		if($c === '')
		{
			return NULL;
		}

		switch(true)
		{
			case ctype_digit($c) || $c == '-':
				return $this->readNumber($c);
				break;

			case ctype_alpha($c):
				return $this->readIdentifier($c);
				break;

			case $c == '"':
				return $this->readString($c);
				break;

			case $c == "#":
				return $this->readComment();
				break;

			case isset(Token::getCharacterTokens()[$c]):
				return new Token(Token::getCharacterTokens()[$c], $c, $this->getLocation());
				break;

			case in_array($c, $this->getIgnoredCharacters()):
				return $this->lexToken();
				break;

			default:
				throw new LexerException("Unexpected character: " . ord($c) . ".");
		}
	}

	/*
	 * Reads a character from the input stream
	 * and updates the token location.
	 */
	public function readCharacter(): string
	{
		$startLocation = $this->getLocation();

		$c = fgetc($this->stream);

		//Don't update position for end of input
		if($c === false)
		{
			return '';
		}

		//Update position
		if($c == "\n")
		{
			//Keep track of character count so that we can restore location
			$charactersOnLine = $startLocation->row;
			$this->lineLengths->unshift($charactersOnLine);

			//No more than a 10 line history
			if($this->lineLengths->count() > 10)
			{
				$this->lineLengths->pop();
			}

			$startLocation->row = 0;
			$startLocation->line++;
		}

		else
		{
			$startLocation->row++;
		}

		return $c;
	}

	/*
	 * Unreads a character from the input stream
	 * and updates the token location.
	 */
	public function unreadCharacter(string $c)
	{
		//Can't unread the end of input
		if($c === '')
		{
			return;
		}

		//Go back one character in the input stream
		fseek($this->stream, -1, SEEK_CUR);

		if($c == "\n")
		{
			$charactersOnLine = $this->lineLengths->shift();
			$this->location->line--;
			$this->location->row = $charactersOnLine;
		}
		else
		{
			$this->location->row--;
		}
	}

	protected function getIgnoredCharacters(): array
	{
		return [
			"\t",
			"\r",
			"\n",
			" "
		];
	}

	/**
	 * Reads a comment line.
	 * Comment lines begin with a # and continue
	 * to the end of the line.
	 *
	 * There are no multiline comments.
	 *
	 * @return Token
	 */
	protected function readComment(): Token
	{
		$location = $this->getLocation();

		$comment = "";

		while(($c = $this->readCharacter()) !== "\n")
		{
			if($c === '')
			{
				break;
			}
			$comment .= $c;
		}

		return new Token(Token::COMMENT, $comment, $location);
	}

	/**
	 * Reads a string, starting and ending with a ".
	 * Standard escape sequences are supported.
	 *
	 * @param string $start
	 * @return Token
	 */
	protected function readString(string $start): Token
	{
		return StringReader::create($this)->read($start);
	}

	/*
	 * Reads an identifier or data type.
	 */
	protected function readIdentifier(string $start): Token
	{
		$id = $start;

		$tokenType = Token::ID;
		if(ctype_upper($start))
		{
			$tokenType = Token::TYPE;
		}

		while(true)
		{
			$c = $this->readCharacter();
			if(ctype_alnum($c))
			{
				$id .= $c;
			}

			else
			{
				$this->unreadCharacter($c);
				break;
			}
		}

		//Check for keyword
		if(isset(Token::getKeywords()[$id]))
		{
			$tokenType = Token::getKeywords()[$id];
		}

		return new Token($tokenType, $id, $this->getLocation());
	}

	/*
	 * Reads an integer or decimal.
	 */
	public function readNumber(string $start): Token
	{
		return NumberReader::create($this)->read($start);
	}
}