<?php

namespace Netpeak;

class report
{

private $domain;

/// Our function to get the data from the File
    public function report($domain)
	
		{
			
			$report = parse_url($domain);
			$this->domain = $report['host'];
			
			$this->checkFile();
			
			$fileHandle = fopen("reports/{$this->domain}.csv", "r");
			
				while (($data = fgetcsv($fileHandle, 0, ";")) !== FALSE) 
				
					{
						for ($c = 0; $c < count($data); $c++) 
							{
								echo $data[$c] . PHP_EOL;
							}
						echo "_______________________________________________________________". PHP_EOL;
					}		
				
			fclose($fileHandle);
		}

/// This function is checking the existence of the file, if not write a message
	 public function checkFile()
		
		{
			if (!is_readable("reports/{$this->domain}.csv")) 
				
				{
					die("
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	Не удается найти отчет по домену: {$this->domain}. 
	
	Пожалуйста, используйте команду 'parse' для подготовки отчета, 'help' для списка команд с пояснениями
	
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			" . PHP_EOL);
				echo $this->help("");
				}
				
		}
	





}