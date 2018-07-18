<?php

require_once 'vendor/autoload.php';

$lexer = new Stack\Lexer\Lexer(STDIN);

while($token = $lexer->lex())
{
	print_r($token);
	print "\n";
}