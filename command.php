<?php

/// Ozgur Murat Tunca 2019
namespace Netpeak;

/// Let's require what we require
require_once __DIR__ . "/vendor/autoload.php";

/// Our command class
class commandParse

{

/// Function to work with input options. 

	function inputOpts(array $inputParams)
	
		{
			
			switch (key($inputParams)) 
				{
					case "parse":
					$url = $this->checkUrl($inputParams["parse"]);
					$url = $this->checkValid($url);
					$parser = new parse();
					$parser->send($url);
					break;
					
					case "report":
					$domain = $this->checkUrl($inputParams["report"]);
					$domain = $this->checkValid($domain);
					$parser = new report();
					$parser->report($domain);
					break;
				
					case "help" || "h":
					default:
					echo $this->help("");
				}
		
		}

/// Function for help and error messages

	function help($message)
		{
			if($message) 
				{ 
					echo $message; 
					die; 
				}
			
			$message = "
			
////////////////////////////////////////////////////////////////////////////////////////////////
	
	ИНСТРУКЦИИ :
	
	php command.php [--help] [--parse=example.com] [--report=example.com]

	ДОСТУПНЫЕ КОМАНДЫ :
	
	--parse		-		Запускает парсер, принимает обязательный параметр url (как с протоколом, так и без).
	--report	-		Выводит в консоль результаты анализа для домена
	--help		-		Выводит список команд с пояснениями
	
	ПРИМЕР :
	
	php command.php --parse=example.com	или	php parse.php --parse example.com
	php command.php --report=example.com	или	php command.php --report example.com
	php command.php --help		
		
////////////////////////////////////////////////////////////////////////////////////////////////		
			
			";
			echo $message;
			die;
			
		}
		
/// Function to check the input URL , add protocol if needed

	function checkUrl($url)
	
		{
			 	return strpos($url, "http") === false ? "http://{$url}" : $url;
		}
		
	function checkValid($url)
	
		{
				
				$message = "
			
////////////////////////////////////////////////////////////////////////////////////////////////
				
				{$url}
				Пожалуйста, введите действительный URL!
					
////////////////////////////////////////////////////////////////////////////////////////////////

				"; 
				
				return filter_var($url, FILTER_VALIDATE_URL) === FALSE ? $this->help($message) : $url;
					
		}
	
}

/// the input options  array

$shortopts  = "";
$shortopts .= "p:";		// Required
$shortopts .= "r:";		// Required
$shortopts .= "h::";	// Optional

$longopts  = array(
    "parse:",    	 	// Required
    "report:",    	 	// Required
    "help::",			// Optional
    );
	

	
$inputParams = getopt($shortopts, $longopts);
$inputVar = new commandParse();
$inputVar->inputOpts($inputParams);


