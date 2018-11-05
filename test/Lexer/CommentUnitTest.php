<?php

namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

class CommentUnitTest extends LexerTest
{
	public function testComments()
	{
		$code = "
			#I'm a single line comment.
			#I'm another
			
			functionCall(); # I'm calling a function here
			
			##
			# Multiline comment.
			##
		";

		$this->assertAllCodeToTokens([
			$code => [
				Token::COMMENT,
				Token::COMMENT,

				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::SEMI,
				Token::COMMENT,

				Token::COMMENT,
				Token::COMMENT,
				Token::COMMENT
			]
		]);
	}
}