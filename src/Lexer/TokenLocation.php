<?php


namespace Stack\Lexer;

/*
 * The location of a token within the source stream.
 */
class TokenLocation
{
	//@var int The line number.
	public $line;

	//@var int The starting character position.
	public $row;

	public function __construct(int $line, int $row)
	{
		$this->line = $line;
		$this->row = $row;
	}
}