<?php

namespace Stack\Lexer;

/*
 * A token, with a type, value, and location.
 */
class Token
{
	# Token Types

	const UNKNOWN = 'unknown';

	// Literals
	const ID = 'id';
	const NUM = 'num';
	const STRING = 'string';
	const TYPE = 'type';

	#Keywords

	// Control structure keywords
	const IF = 'if';
	const ELSE = 'else';
	const FOR = 'for';
	const BREAK = 'break';
	const CONTINUE = 'continue';

	// Function keywords
	const FUNCTION = 'function';
	const RETURN = 'return';

	// Class keywords
	const T_CLASS = 'class';
	const PUBLIC = 'public';
	const PROTECTED = 'protected';
	const PRIVATE = 'private';
	const PACKAGE = 'package';
	const NEW = 'new';

	# Operators

	// Mathematical Operators
	const PLUS = '+';
	const MINUS = '-';
	const TIMES = '*';
	const DIVIDE = '/';
	const MOD = '%';

	// Object operators
	const DOT = '.';

	// Logical operators
	const AND = 'and';
	const OR = 'or';
	const XOR = 'xor';
	const NOT = 'not';
	const QMARK = '?';

	// Comparison operators
	const EQ = 'eq';
	const GT = '>';
	const LT = '<';

	# Open/Close Tokens
	const LBRACE = '{';
	const RBRACE = '}';
	const LPAREN = '(';
	const RPAREN = ')';
	const LBRACKET = '[';
	const RBRACKET = ']';

	# Colons
	const SEMI = ';';
	const COLON = ':';
	const COMMA = ',';

	# Comments
	const COMMENT = '#';

	//@var string The type of the token (ID, NUM, etc.)
	public $type;

	//@var mixed The textual/numeric representation of the read token.
	public $value;

	//@var TokenLocation Represents the location of the token within the input stream.
	public $location;

	/*
	 * Constructs a new token.
	 */
	public function __construct(string $type, $value, TokenLocation $location)
	{
		$this->type = $type;
		$this->value = $value;
		$this->location = $location;
	}

	/*
	 * Gets all of the keyword tokens.
	 */
	public static function getKeywords(): array
	{
		return [
			"if"		=> self::IF,
			"else"		=> self::ELSE,
			"for"		=> self::FOR,
			"break"		=> self::BREAK,
			"continue"	=> self::CONTINUE,
			"function"	=> self::FUNCTION,
			"return"	=> self::RETURN,
			"class"		=> self::T_CLASS,
			"public"	=> self::PUBLIC,
			"protected"	=> self::PROTECTED,
			"private"	=> self::PRIVATE,
			"package"	=> self::PACKAGE,
			"new"		=> self::NEW
		];
	}

	/*
	 * Maps single characters to token types.
	 * Parser handles all multiple character tokens
	 * where the leading token isn't able to uniquely identify it.
	 */
	public static function getCharacterTokens(): array
	{
		return [
			'+' => self::PLUS,
			'-' => self::MINUS,
			'*' => self::TIMES,
			'/' => self::DIVIDE,
			'%' => self::MOD,
			'.' => self::DOT,
			'&' => self::AND,
			'|' => self::OR,
			'^' => self::XOR,
			'!' => self::NOT,
			'?' => self::QMARK,
			'=' => self::EQ,
			'>' => self::GT,
			'<' => self::LT,
			'{' => self::LBRACE,
			'}' => self::RBRACE,
			'(' => self::LPAREN,
			')' => self::RPAREN,
			'[' => self::LBRACKET,
			']' => self::RBRACKET,
			';' => self::SEMI,
			':' => self::COLON,
			',' => self::COMMA
		];
	}
}