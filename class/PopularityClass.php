<?php
	class PopularityClass extends Thread
	{	
	
		public $crawl;		
		public $crawl_class;
		public $backlinks;		
		public $backlinks_class;
		public $edu;		
		public $edu_class;	
		public $gov;		
		public $gov_class;	
		public $dmoz;		
		public $dmoz_class;	
		public $yahoodir;		
		public $yahoodir_class;	
		public $w3c_content;		
			
		public function __construct($domain, $url)
		{
			$this->domain = $domain;
			$this->url = $url;
		}
		
		public function run() 
		{
			$this->crawl = $this->getCrawlContent($this->url);		
			$this->crawl_class = $this->getCrawlContentClass($this->crawl);
			$this->backlinks =  $this->GoogleBL($this->domain);
			if($this->backlinks == "") 
				$this->backlinks = "0";
			$this->backlinks_class = $this->GoogleBLClass($this->backlinks);
			
			$this->edu = $this->GoogleEDU($this->domain);	
			if($this->edu == "") 
				$this->edu = "0";		
			$this->edu_class = $this->GoogleEDUClass($this->edu);
			
			$this->gov = $this->GoogleGOV($this->domain);	
			if($this->gov == "") 
				$this->gov = "0";						
			$this->gov_class = $this->GoogleGOVClass($this->gov);
			
			$this->dmoz = $this->get_dmoz($this->domain);
			$this->dmoz_class = $this->get_dmozClass($this->dmoz);
			
			$this->yahoodir = $this->get_yahoodir($this->domain);
			$this->yahoodir_class = $this->get_yahoodirClass($this->yahoodir);
			$this->w3c_content = $this->get_web_page("http://validator.w3.org/check?uri=".$this->domain);
		}
		
		public function get_web_page( $url )
		{		
			$proxies_array	=	array(
										'173.208.91.117:3128',
										'173.208.39.106:3128',
										'173.234.57.8:3128',
										'173.234.250.161:3128',
										'173.234.181.127:3128',
										'173.208.91.201:3128',
										'173.234.57.249:3128'
									);
									
			$random_key 		= array_rand($proxies_array);
			$random_proxy 		= $proxies_array[$random_key];					
									
			$useragents_array	=	array(
										'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.11) Gecko Kazehakase/0.5.4 Debian/0.5.4-2.1ubuntu3',
										'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080311 (Debian-1.8.1.13+nobinonly-0ubuntu1) Kazehakase/0.5.2',
										'Mozilla/5.0 (X11; Linux x86_64; U;) Gecko/20060207 Kazehakase/0.3.5 Debian/0.3.5-1',
										'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)',
										'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.1.4) Gecko/20070511 K-Meleon/1.1',
										'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 K-MeleonCCFME 0.09',
										'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.0.7) Gecko/20060917 K-Meleon/1.02',
										'Mozilla/5.0 (Windows; U; Win 9x 4.90; en-US; rv:1.7.5) Gecko/20041220 K-Meleon/0.9',
										'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2',
										'Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2',
										'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8',
										'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2b) Gecko/20021016 K-Meleon 0.7',
										'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:0.9.5) Gecko/20011011',
										'Mozilla/5.0(Windows;N;Win98;m18)Gecko/20010124',
										'Mozilla/5.0 (compatible; Konqueror/4.0; Linux) KHTML/4.0.5 (like Gecko)',
										'Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)',
										'Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)',
										'Mozilla/5.0 (compatible; Konqueror/3.5; GNU/kFreeBSD) KHTML/3.5.9 (like Gecko) (Debian)',
										'Mozilla/5.0 (compatible; Konqueror/3.5; Darwin) KHTML/3.5.6 (like Gecko)'
									);
									
			$random_key 		= array_rand($useragents_array);
			$random_useragent 	= $useragents_array[$random_key];							
		
															
			$options = array(
					CURLOPT_RETURNTRANSFER 	=> true,     			// return web page
					CURLOPT_HEADER         	=> false,    			// don't return headers
					//CURLOPT_PROXY 			=> $random_proxy,     		// the HTTP proxy to tunnel request through
					//CURLOPT_HTTPPROXYTUNNEL => 1,    				// tunnel through a given HTTP proxy			
					CURLOPT_FOLLOWLOCATION 	=> true,     			// follow redirects
					CURLOPT_ENCODING       	=> "",       			// handle compressed
					CURLOPT_USERAGENT      	=> $random_useragent, 	// who am i
					CURLOPT_AUTOREFERER    	=> true,     			// set referer on redirect
					CURLOPT_CONNECTTIMEOUT 	=> 120,      			// timeout on connect
					CURLOPT_TIMEOUT        	=> 20,      			// timeout on response
					CURLOPT_MAXREDIRS      	=> 10,       			// stop after 10 redirects
				);
			
			$ch      = curl_init( $url );
			curl_setopt_array( $ch, $options );
			$content = curl_exec( $ch );
			curl_close( $ch );
				
			return $content;
		}				
		
		public function get_yahoodir($domain)
		{
			$page = get_web_page("http://dir.search.yahoo.com/search?h=c&p=$domain&ei=utf-8&fr=ush-dir");
			preg_match_all('/<em class="article_hosturl">(.*?)<\/em>/is',$page,$matches);
			if ($matches[0]) 
			{		
				foreach($matches[0] as $match)
				{
					$match = strip_tags($match);
					preg_match("#$domain#is",$match,$found);				
					if($found[0])				
						return "Yes";				
					else
						return "No";
				}
			}else
				return "No";				
		}
		
		public function get_yahoodirClass($yahoodir)
		{
			if($yahoodir == "Yes")
			{
				$overall_score = $overall_score + 4.0;
				$yahoodir_class = "criterion good";
			}else
			{
				$overall_score = $overall_score + 0.9;
				$yahoodir_class = "criterion bad";			
			}
			return $yahoodir_class;
		}	
		
		public function get_dmoz($domain)
		{
			$page = get_web_page("http://search.dmoz.org/search/search?q=$domain");
			preg_match_all('/<div class="ref">(.*?)&nbsp;/is',$page,$matches);
			if ($matches[0]) 
			{			
				foreach($matches[0] as $match)
				{			
					preg_match("#$domain#is",$match,$found);				
					if($found[0])				
						return "Yes";				
					else
						return "No";
					
				}
			}else			
				return "No";
			
		}
		
		public function get_dmozClass($dmoz)
		{
			if($dmoz == "Yes")
			{
				$overall_score = $overall_score + 4.0;
				$dmoz_class = "criterion good";
			}else
			{
				$overall_score = $overall_score + 0.9;
				$dmoz_class = "criterion bad";			
			}
			return $dmoz_class;
		}
	
		public function getCrawlContent($url)
		{
			$crawl_content	=	get_web_page("http://webcache.googleusercontent.com/search?q=cache:$url");
			preg_match('/snapshot of the page as it appeared on (.*?)\./is',$crawl_content,$crawl);
			$crawl = trim($crawl[1]);
			if($crawl == "") 
				$crawl = "Unknown";		
			return $crawl;
		}
		
		public function getCrawlContentClass($crawl)
		{
			if($crawl == "Unknown")						
				$crawl_class = "criterion bad";
			else						
				$crawl_class = "criterion good";									
			return $crawl_class;			
		}
	
		public function GoogleGOV($domain)
		{
			$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=allintext:".$domain."+site:gov&filter=0&key=ABQIAAAAFKVsUTREbJ0drrYvTbZdHxReVx4SDhSo8vxayOty1oc4sCTPwhRhnrFjuQwqg1KEGUT6ikNVA8E4oQ&userip=USERS-IP-ADDRESS";
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_NOBODY, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$json = curl_exec($ch);
			curl_close($ch);
			$data=json_decode($json,true);
			if($data['responseStatus']==200)
				return $data['responseData']['cursor']['estimatedResultCount'];
			else
				return false;
		}					
		
		public function GoogleGOVClass($gov)
		{
			
			if($gov >= "0" && $gov < "5")
			{
				$overall_score = $overall_score + 0.7;
				$gov_class = "criterion bad";
			}
			elseif ($gov > "5" && $gov < "10")
			{
				$overall_score = $overall_score + 3.0;
				$gov_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$gov_class = "criterion good";			
			}				
			return $gov_class;
		}
	
		public function GoogleEDU($domain)
		{
			$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=allintext:".$domain."+site:edu&filter=0&key=ABQIAAAAFKVsUTREbJ0drrYvTbZdHxReVx4SDhSo8vxayOty1oc4sCTPwhRhnrFjuQwqg1KEGUT6ikNVA8E4oQ&userip=USERS-IP-ADDRESS";
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_NOBODY, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$json = curl_exec($ch);
			curl_close($ch);
			$data=json_decode($json,true);
			if($data['responseStatus']==200)
				return $data['responseData']['cursor']['estimatedResultCount'];
			else
				return false;
		}
		
		public function GoogleEDUClass($edu)
		{
		
			if($edu >= "0" && $edu < "5")
			{
				$overall_score = $overall_score + 0.7;
				$edu_class = "criterion bad";
			}
			elseif ($edu > "5" && $edu < "10")
			{
				$overall_score = $overall_score + 3.0;
				$edu_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$edu_class = "criterion good";			
			}		
			return $edu_class;
		}			
		
		public function GoogleBL($domain)
		{
			$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=allintext:".$domain."&filter=0&key=ABQIAAAAFKVsUTREbJ0drrYvTbZdHxReVx4SDhSo8vxayOty1oc4sCTPwhRhnrFjuQwqg1KEGUT6ikNVA8E4oQ&userip=USERS-IP-ADDRESS";
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_NOBODY, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$json = curl_exec($ch);
			curl_close($ch);
			$data=json_decode($json,true);
			if($data['responseStatus']==200)
				return $data['responseData']['cursor']['estimatedResultCount'];
			else
				return false;
		}
		
		public function GoogleBLClass($backlinks)
		{		
				
			if($backlinks >= "0" && $backlinks < "100")
			{
				$overall_score = $overall_score + 0.7;
				$backlinks_class = "criterion bad";
			}
			elseif ($backlinks > "100" && $backlinks < "1000")
			{
				$overall_score = $overall_score + 3.0;
				$backlinks_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$backlinks_class = "criterion good";			
			}
			
			return $backlinks_class;
		}			
		
	}
?>