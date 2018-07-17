<?php

namespace Stack\Lexer;

class Lexer
{
	//@var resource $stream The stream containing the input text.
	protected $stream;

	/*
	 * @param $stream Must be a valid stream resource.
	 */
	public function __construct($stream)
	{
		$resourceType = get_resource_type($stream);
		if($resourceType !== "stream")
		{
			throw new LexerException("Invalid resource type: $resourceType.");
		}
	}

	public function lex(): ?Token
	{
		feof();
		return new Token();
	}
}