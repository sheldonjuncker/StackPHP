<?php

namespace Stack\Lexer;

/*
 * A token, with a type, value, and location.
 */
class Token
{
	//@var int The type of the token (ID, NUM, etc.)
	public $type;

	//@var string The textual representation of the read token.
	public $value;

	//@var TokenLocation Represents the location of the token within the input stream.
	public $location;

	/*
	 * Constructs a new token.
	 */
	public function __construct(int $type, string $value, TokenLocation $location)
	{
		$this->type = $type;
		$this->value = $value;
		$this->location = $location;
	}
}