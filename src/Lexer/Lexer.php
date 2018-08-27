<?php

namespace Stack\Lexer;

use Stack\Lexer\Reader\StringReader;
use Stack\Lexer\Reader\NumberReader;

class Lexer
{
	//@var resource $stream The stream containing the input text.
	protected $stream;

	//@var TokenLocation[] $locations The location of every character in the input stream. (TODO: optimize)
	protected $locations = [];

	/*
	 * @param $stream Must be a valid stream resource.
	 */
	public function __construct($stream)
	{
		$this->locations = [new TokenLocation(1, 0)];

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
		return end($this->locations);
	}

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
			$this->pushLocation(new TokenLocation($startLocation->line + 1, 0));
		}

		else
		{
			$this->pushLocation(new TokenLocation($startLocation->line, $startLocation->row + 1));
		}

		return $c;
	}

	/*
	 * Adds to the list of locations.
	 */
	protected function pushLocation(TokenLocation $location)
	{
		array_push($this->locations, $location);
	}

	/*
	 * Restores the last location.
	 */
	public function popLocation()
	{
		array_pop($this->locations);
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

		//Remove last location
		$this->popLocation();
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
		$keyword = strtolower($id);
		if(isset(Token::getKeywords()[$keyword]))
		{
			$tokenType = Token::getKeywords()[$keyword];
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