<?php

require_once 'vendor/autoload.php';

$lexer = new Stack\Lexer\Lexer(STDIN);

print get_class($lexer->lex());