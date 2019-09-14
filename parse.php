<?php

namespace Netpeak;


/// Class to parse

class parse
{
	
/// Lets define our vars

private $url;
private $domain;
private $path;
private $htmlString;


/// This function will add http protocol if the SRC PATH contains relative path 
	
	public function url_add_protocol($getPath)
	
		{
			if (!preg_match("~^(?:f|ht)tps?://~i", $getPath)) 
						
				{
					$getPath = "http://".$getPath;
					return $getPath;
				}
					
			
		}
	
/// This function will parse the domain to see the SRC path domain
	
	public function parse_domain($valueImg,$path)
	
		{
			if (!preg_match("~^(?:f|ht)tps?://~i", $valueImg))
				{
					$parse = parse_url($path);
					$pDomain = $parse['host'];
					return $pDomain;
				}
			else
				{
					$parse = parse_url($valueImg);
					$pDomain = $parse['host'];
					return $pDomain;
				}
							
		}

/// This function will get the domain from the input URL incase of a relative SRC PATH

	public function url_get_domain($path)
	
		{	

			$parse = parse_url($path);
			$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
			$protocol .= "://".$parse['host']."/";
			return $protocol;
			
		}

/// This function will remove the dots and slashes of the relative SRC PATH and add HTTP Protocol	

	public function url_remove_dot_segments(string $str) 
	
			
		{	
			if (!preg_match("~^(?:f|ht)tps?://~i", $str))
				{
					$str = str_replace('../', '', $str);
					$str = "http://".$this->domain."/".$str;
					
					
				}
			
			return $str;
		}


//////////////////////////////////////////////////////////////////////////////////////////////////
//									The first function we need									//
//			Function is for getting the data from the input URL	and working on it				//
//////////////////////////////////////////////////////////////////////////////////////////////////

	public function send($url)
	
		{	
					
			$this->url = $url;
			$this->domain = $this->parse_domain("",$this->url);
																	
						// Gets the contents from input URL
						$this->htmlString = file_get_contents($this->url);
						
						/// Here we search for the IMG tag and get the SRC out of it		
						preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i' , $this->htmlString, $matchImg );
						$srcImg = array_pop($matchImg);
						
						/// Here we search for the A Link tag and get the SRC out of it	incase we need it	
						preg_match_all( '/<a.+href=[\'"]([^\'"]+)[\'"].*>/i' , $this->htmlString, $matchLink );
						$srcLink = array_pop($matchLink);
						
						/// Let's open or create the file we need to save report
						$this->file = fopen("reports/{$this->domain}.csv", "w");		
							
							foreach($srcImg as $valueImg)
								
								{					
										$domain = $this->parse_domain($valueImg,$this->url);
										$fullPath = $this->url_remove_dot_segments($valueImg);
										
										$src = $valueImg;
										$contents = array($domain, $fullPath, $src);
										fputcsv($this->file, $contents, ";");
								}
								
						fclose($this->file);
						
						/// If the file is created, edited and closed, let's send a message
						if(!is_resource($this->file))
							
							{
							
								$message = "
				
////////////////////////////////////////////////////////////////////////////////////////////////
	
			Файл отчета был выполнен.

			Расположение файла: reports/{$this->domain}.csv		
				
////////////////////////////////////////////////////////////////////////////////////////////////				
					
								";
					
								echo $message;
							}					
		}
}


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
