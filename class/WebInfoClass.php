<?php
	class WebInfoClass extends AClass
	{	
		public function QueryWhoisServer($whoisserver, $domain) 
		{
			$port = 43;
			$timeout = 10;
			$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);
			if($whoisserver == "whois.verisign-grs.com") 
				$domain = "=".$domain; // whois.verisign-grs.com requires the equals sign ("=") or it returns any result containing the searched string.
			fputs($fp, $domain . "\r\n");
			$out = "";
			while(!feof($fp))
			{
				$out .= fgets($fp);
			}
			fclose($fp);
		
			$res = "";
			if((strpos(strtolower($out), "error") === FALSE) && (strpos(strtolower($out), "not allocated") === FALSE)) 
			{
				$rows = explode("\n", $out);
				foreach($rows as $row) 
				{
					$row = trim($row);
					if(($row != '') && ($row{0} != '#') && ($row{0} != '%')) 
					{
						$res .= $row."\n";
					}
				}
			}
			return $res;
		}		
	
		public function ValidateDomain($domain) 
		{
			if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) 
				return false;
			return $domain;
		}		
			
		public function LookupDomain($domain)
		{
			global $whoisservers;
			$domain_parts = explode(".", $domain);
			$tld = strtolower(array_pop($domain_parts));
			$whoisserver = $whoisservers[$tld];
			if(!$whoisserver) 
			{
				return "Error: No appropriate Whois server found for $domain domain!";
			}
			$result = $this->QueryWhoisServer($whoisserver, $domain);
			if(!$result) 
			{
				return "Error: No results retrieved from $whoisserver server for $domain domain!";
			}
			else 
			{
				while(strpos($result, "Whois Server:") !== FALSE)
				{
					preg_match("/Whois Server: (.*)/", $result, $matches);
					$secondary = $matches[1];
					if($secondary) 
					{
						$result = $this->QueryWhoisServer($secondary, $domain);
						$whoisserver = $secondary;
					}
				}
			}
			return "$domain domain lookup results from $whoisserver server:\n\n" . $result;
		}			
	
		public function ValidateIP($ip) 
		{
			$ipnums = explode(".", $ip);
			if(count($ipnums) != 4) 
				return false;
			foreach($ipnums as $ipnum) 
			{
				if(!is_numeric($ipnum) || ($ipnum > 255))
					return false;				
			}
			return $ip;
		}			
			
		public function LookupIP($ip) 
		{
			$whoisservers = array(
				//"whois.afrinic.net", // Africa - returns timeout error :-(
				"whois.lacnic.net", // Latin America and Caribbean - returns data for ALL locations worldwide :-)
				"whois.apnic.net", // Asia/Pacific only
				"whois.arin.net", // North America only
				"whois.ripe.net" // Europe, Middle East and Central Asia only
			);
			$results = array();
			foreach($whoisservers as $whoisserver) 
			{
				$result = $this->QueryWhoisServer($whoisserver, $ip);
				if($result && !in_array($result, $results)) 
				{
					$results[$whoisserver]= $result;
				}
			}
			$res = "RESULTS FOUND: " . count($results);
			foreach($results as $whoisserver=>$result) {
				$res .= "\n\n-------------\nLookup results for $ip from $whoisserver server:\n\n$result";
			}
			return $res;
		}
		
		public function getLoadTime()
		{
			global $StartTime;
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$Endtime = $time;
			$Total = round($Endtime - $StartTime,4);
			return $Total;
		}
		
		public function getLoadTimeClass($load)
		{
			if($load >= "0" && $load < "5")
			{
				$overall_score = $overall_score + 4.0;
				$load_class = "criterion good";
			}
			elseif ($load > "5" && $load < "10")
			{
				$overall_score = $overall_score + 3.0;
				$load_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 0.7;
				$load_class = "criterion bad";			
			}
			return $load_class;
		}		
	}
?>