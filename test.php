<?php

require_once 'vendor/autoload.php';

$fileHandle = fopen("test.txt", "r");
$lexer = new Stack\Lexer\Lexer($fileHandle);

while($token = $lexer->lex())
{
	print_r($token);
	print "\n";
}