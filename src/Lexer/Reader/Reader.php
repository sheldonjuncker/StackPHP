<?php

namespace Stack\Lexer\Reader;
use Stack\Lexer\Lexer;
use Stack\Lexer\Token;

abstract class Reader
{
	protected $lexer;

	public static function create(Lexer $lexer): Reader
	{
		return new static($lexer);
	}

	public function __construct(Lexer $lexer)
	{
		$this->lexer = $lexer;
	}

	abstract public function read(): Token;
}