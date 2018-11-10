<?php

require_once __DIR__ . '/../vendor/autoload.php';

array_shift($argv);


if(empty($argv))
{
	showUsage();
}

$inputFile = array_shift($argv);
if(!file_exists($inputFile))
{
	die("File $inputFile does not exist.");
}

if(!is_readable($inputFile))
{
	die("File $inputFile is not readable.");
}

$outputFile = "";
$outputFormat = "json";
$verbose = false;

while($argv)
{
	$arg = array_shift($argv);

	switch($arg)
	{
		case "-o":
			$outputFile = array_shift($argv);
			if(!$outputFile)
			{
				print "Missing output file argument.\n";
				showUsage();
			}
			break;
		case "-f":
			$outputFormat = array_shift($argv);
			if($outputFormat != "php" && $outputFormat != "json")
			{
				if($outputFormat)
				{
					print "Invalid output format of {$outputFormat}.\n";
				}
				else
				{
					print "Missing output format.\n";
				}
				showUsage();
			}
			break;
		case "-v":
			$verbose = true;
			break;
		default:
			print "Invalid flag/argument $arg.\n";
			showUsage();
	}
}

$fileHandle = fopen($inputFile, "r");

if(!$fileHandle)
{
	print "Failed to open file $inputFile.\n";
}
else
{
	$error = false;
	try
	{
		$lexer = new \Stack\Lexer\Lexer($fileHandle);

		if($verbose || $outputFile)
		{
			$tokens = $lexer->lexAll();
			if($verbose)
			{
				print_r($tokens);
			}
			if($outputFile)
			{
				if($outputFormat == "json")
				{
					$outputData = json_encode($tokens, JSON_PRETTY_PRINT);
				}
				else
				{
					$outputData = serialize($tokens);
				}
				if(file_put_contents($outputFile, $outputData) === false)
				{
					$error = true;
					print "Failed to write output file.\n";
				}
			}
		}
		else
		{
			while($lexer->lex());
		}
	}
	catch(Exception $e)
	{
		$error = true;
		print $e->getMessage() . "\n";
	}

	if(!$error)
	{
		print "OK";
		return 0;
	}
	else
	{
		return 1;
	}
}

function showUsage()
{
	print "Usage:\nlex input.file -o output.file -v -f json\n";
	print "\t-o Output file\n";
	print "\t-f Output format json|php\n";
	print "\t-v Verbose (displays tokens)\n";
	exit;
}