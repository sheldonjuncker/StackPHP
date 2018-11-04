<?php

require_once 'vendor/autoload.php';

$fileHandle = fopen("test.txt", "r");
$lexer = new Stack\Lexer\Lexer($fileHandle);

$tokens = [];
while($tokens[] = $lexer->lex());

$tokensJSON = json_encode($tokens, JSON_PRETTY_PRINT);

print $tokensJSON;