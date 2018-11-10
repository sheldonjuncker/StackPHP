<?php


namespace Stack\Test\Lexer;

use Stack\Lexer\Token;

/**
 * Tests all basic lexer functionality.
 *
 * Class GeneralUnitTest
 * @package Stack\Test\Lexer
 */
class GeneralUnitTest extends LexerTest
{
	public function testLexer()
	{
		$code = '
package Animal;

#
# The cat animal, a strangely demonic and small breed of dog.
#
class Cat : Animal
{
	public sleep()
	{
		this.sleeping = 1;
		this.asyncDelay({
			this.sleeping = 0;
		}, 60 * 60 * 23.9);
	}
	
	public Int isSleeping()
	{
		return this.sleeping;
	}
} 

Cat trouble = new Cat();
trouble.sleep();

for(Int i=0; i<100; i++)
{
	print (i * 10 - i);
}

String str = "Hello, world!";
while(str.length())
{
	Char c = str.pop();
	if(c - 100 > c)
	{
		print "ha";
	}
}

print "Cat is sleeping: " + trouble.isSleeping + ".";
		';
		$this->assertAllCodeToTokens([
			$code => [
				//package Animal;
				Token::PACKAGE,
				Token::TYPE,
				Token::SEMI,

				//comments
				Token::COMMENT,
				Token::COMMENT,
				Token::COMMENT,

				//class Cat : Animal {
				Token::T_CLASS,
				Token::TYPE,
				Token::COLON,
				Token::TYPE,
				Token::LBRACE,

				//public sleep(){
				Token::PUBLIC,
				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::LBRACE,

				//this.sleeping = 1;
				Token::ID,
				Token::DOT,
				Token::ID,
				Token::EQ,
				Token::NUM,
				Token::SEMI,

				/*
				 this.asyncDelay({
					this.sleeping = 0;
				}, 60 * 60 * 23.9);
				}
				 */
				Token::ID,
				Token::DOT,
				Token::ID, //25
				Token::LPAREN,
				Token::LBRACE,

				Token::ID,
				Token::DOT,
				Token::ID,
				Token::EQ,
				Token::NUM,
				Token::SEMI,

				Token::RBRACE,
				Token::COMMA,
				Token::NUM,
				Token::TIMES,
				Token::NUM,
				Token::TIMES,
				Token::NUM,
				Token::RPAREN,
				Token::SEMI,
				Token::RBRACE,

				/*
				 public Int isSleeping(){
					return this.sleeping;
				}
				}
				 */
				Token::PUBLIC,
				Token::TYPE,
				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::LBRACE,
				Token::RETURN, //50
				Token::ID,
				Token::DOT,
				Token::ID,
				Token::SEMI,
				Token::RBRACE,
				Token::RBRACE,

				//Cat trouble = new Cat();
				//trouble.sleep();
				Token::TYPE,
				Token::ID,
				Token::EQ,
				Token::NEW,
				Token::TYPE,
				Token::LPAREN,
				Token::RPAREN,
				Token::SEMI,

				Token::ID,
				Token::DOT,
				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::SEMI,

				/*
				 for(Int i=0; i<100; i++)
				{
					print (i * 10 - i);
				}
				 */
				Token::FOR,
				Token::LPAREN,
				Token::TYPE,
				Token::ID,
				Token::EQ, //75
				Token::NUM,
				Token::SEMI,
				Token::ID,
				Token::LT,
				Token::NUM,
				Token::SEMI,
				Token::ID,
				Token::PLUS,
				Token::PLUS,
				Token::RPAREN,
				Token::LBRACE,

				Token::ID,
				Token::LPAREN,
				Token::ID,
				Token::TIMES,
				Token::NUM,
				Token::MINUS,
				Token::ID,
				Token::RPAREN,
				Token::SEMI,

				Token::RBRACE,

				//String str = "Hello, world!";
				Token::TYPE,
				Token::ID,
				Token::EQ,
				Token::STRING, //100
				Token::SEMI,

				/*
				 while(str.length())
				{
					Char c = str.pop();
					if(c - 100 > c)
					{
						print "ha";
					}
				}
				 */
				Token::WHILE,
				Token::LPAREN,
				Token::ID,
				Token::DOT,
				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::RPAREN,
				Token::LBRACE,

				Token::TYPE,
				Token::ID,
				Token::EQ,
				Token::ID,
				Token::DOT,
				Token::ID,
				Token::LPAREN,
				Token::RPAREN,
				Token::SEMI,

				Token::IF,
				Token::LPAREN,
				Token::ID,
				Token::MINUS,
				Token::NUM,
				Token::GT, //125
				Token::ID,
				Token::RPAREN,
				Token::LBRACE,
				Token::ID,
				Token::STRING,
				Token::SEMI,
				Token::RBRACE,

				Token::RBRACE,

				//print "Cat is sleeping: " + trouble.isSleeping + ".";
				Token::ID,
				Token::STRING,
				Token::PLUS,
				Token::ID,
				Token::DOT,
				Token::ID,
				Token::PLUS,
				Token::STRING,
				Token::SEMI
			]
		]);
	}
}