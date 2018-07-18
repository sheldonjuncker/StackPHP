<?php

namespace Stack\Lexer;

class Lexer
{
	//@var resource $stream The stream containing the input text.
	protected $stream;

	//@var TokenLocation $location The location in the input stream.
	protected $location;

	/*
	 * @param $stream Must be a valid stream resource.
	 */
	public function __construct($stream)
	{
		$this->location = new TokenLocation(1, 0);

		$resourceType = get_resource_type($stream);
		if($resourceType !== "stream")
		{
			throw new LexerException("Invalid resource type: $resourceType.");
		}
	}

	/*
	 * Gets the current location in the input stream.
	 */
	public function getLocation(): TokenLocation
	{
		return $this->location;
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
		//Keep track of the starting location of the token
		$startLocation = clone $this->getLocation();

		//TODO: Does not account for leading whitespace
		$c = $this->readCharacter();

		switch(true)
		{
			case ctype_digit($c):
				return $this->readNumber();
				break;

			case ctype_alpha($c):
				return $this->readIdentifier();
				break;

			case $c == '"' || $c == '\'':
				return $this->readString();
				break;

			default:
				throw new LexerException("Unexpected character: $c.");
		}
	}

	protected function readCharacter(): string
	{
		$c = fgetc($this->stream);

		if($c == "\n")
		{
			$this->getLocation()->line++;
			$this->getLocation()->row = 0;
		}

		else
		{
			$this->getLocation()->row++;
		}

		return $c;
	}

	public function getIgnoredCharacters(): string
	{
		return " \t\r\n";
	}
}