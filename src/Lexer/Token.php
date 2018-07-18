<?php

namespace Stack\Lexer;

/*
 * A token, with a type, value, and location.
 */
class Token
{
	#Token Types

	//Literals
	const ID = 0;
	const NUM = 1;
	const STRING = 2;
	const TYPE = 40;

	#Keywords

	//Control structure keywords
	const IF = 3;
	const ELSE = 4;
	const FOR = 5;
	const BREAK = 6;
	const CONTINUE = 7;

	//Function keywords
	const FUNCTION = 8;
	const RETURN = 9;

	//Class keywords
	const T_CLASS = 10;
	const PUBLIC = 11;
	const PROTECTED = 12;
	const PRIVATE = 13;
	const PACKAGE = 14;
	const NEW = 21;

	#Operators

	//Mathematical Operators
	const PLUS = 15;
	const MINUS = 16;
	const TIMES = 17;
	const DIVIDE = 19;
	const MOD = 20;

	//Object operators
	const DOT = 22;

	//Logical operators
	const AND = 23;
	const OR = 24;
	const XOR = 25;
	const NOT = 31;

	//Comparison operators
	const EQ = 26; //==
	const GT = 27;
	const LT = 28;
	const GTE = 29;
	const LTE = 30;
	const NE = 32;

	//Other operators
	const ASSIGN = 33;

	#Open/Close Tokens
	const LBRACE = 34;
	const RBRACE = 35;
	const LPAREN = 36;
	const RPAREN = 37;
	const LBRACKET = 38;
	const RBRACKET = 39;

	#Colons
	const SEMI = 41;
	const COLON = 42;

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

	/*
	 * Gets all of the keyword tokens.
	 */
	public function getKeywords(): array
	{
		return [
			self::IF,
			self::ELSE,
			self::FOR,
			self::BREAK,
			self::CONTINUE,
			self::FUNCTION,
			self::RETURN,
			self::T_CLASS,
			self::PUBLIC,
			self::PROTECTED,
			self::PRIVATE,
			self::PACKAGE,
			self::NEW
		];
	}
}