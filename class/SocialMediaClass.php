<?php
	class SocialMediaClass extends AClass
	{
		public $fb_likes;
		public $fb_like_class;

		public function __construct($url)
		{
			$this->url = $url;
		}
		
		public function run() 
		{
			//$social_network = new SocialMediaClass();	
			$fb = $this->getFBlikes($this->url);		
			$this->fb_likes = $fb['likes'];
			$this->fb_like_class = $this->getFBLikeCLass($this->fb_likes);
		}			
			
		public function getFBlikes($url)
		{
			$en_link = urlencode($url);
			
			$data 	= file_get_contents("http://api.facebook.com/method/links.getStats?format=json&urls=$en_link");
			$json 	= json_decode($data);
			$like_count = $json[0]->like_count;
			$share_count = $json[0]->share_count;
			$comment_count = $json[0]->comment_count;
			$total_count = $json[0]->total_count;
			$click_count = $json[0]->click_count;				
			$fb = array(
				'likes' => $like_count,
				'shares' => $share_count,
				'comments' => $comment_count,
				'total' => $total_count,
				'clicks' => $click_count                
			);
			return $fb;
		}
		
		public function getFBLikeCLass($fb_likes)
		{
			if($fb_likes >= "0" && $fb_likes < "5")
			{
				$overall_score = $overall_score + 0.7;
				$fb_likes_class = "criterion bad";
			}
			elseif ($fb_likes > "5" && $fb_likes < "50")
			{
				$overall_score = $overall_score + 3.0;
				$fb_likes_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$fb_likes_class = "criterion good";			
			}
			return $fb_likes_class;
		}
		
		public function getGooglePlus($url)
		{
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_HTTPHEADER      => array('Content-type: application/json'),
				CURLOPT_POST            => true,
				CURLOPT_POSTFIELDS      => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_URL             => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ'
			));
			$res = curl_exec($ch);
			curl_close($ch);
		 
			if($res)
			{
				$json = json_decode($res,true);
				$count = @$json[0]['result']['metadata']['globalCounts']['count'];
				if($count) 
					return $count;
				else
					return "0";
		    }
		  return "0";
		}	
		
		public function getGooglePlusCLass($googleplus1)
		{
			if($googleplus1 >= "0" && $googleplus1 < "5")
			{
				$overall_score = $overall_score + 0.7;
				$googleplus1_class = "criterion bad";
			}
			elseif ($googleplus1 > "5" && $googleplus1 < "50")
			{
				$overall_score = $overall_score + 3.0;
				$googleplus1_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$googleplus1_class = "criterion good";			
			}
			return $googleplus1_class;
		}
		
		public function getTweets($url)
		{
			$en_link = urlencode($url);						
			$json_t = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$en_link);
			$data_t = json_decode($json_t);
			$tweets = $data_t->count;
		 	$tweet = str_replace(",","",trim($tweet));		
							
			if($tweets) 
				return $tweets;
			else
				return "0";				
		}
		
		public function getTweetsClass($tweet)
		{
			if($tweet >= "0" && $tweet < "5")
			{
				$overall_score = $overall_score + 0.7;
				$tweet_class = "criterion bad";
			}
			elseif ($tweet > "5" && $tweet < "50")
			{
				$overall_score = $overall_score + 3.0;
				$tweet_class = "criterion average";
			}else{
				$overall_score = $overall_score + 4.0;
				$tweet_class = "criterion good";			
			}			
			
			return $tweet_class;
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
		
		public function getWikipedia($url)
		{
			$wiki_content =	$this->get_web_page("http://en.wikipedia.org/w/index.php?title=Special%3ASearch&search=$url");
			preg_match('/Results <b>(.*?)<\/b> of <b>(.*?)<\/b>/is',$wiki_content,$wiki);
			$wiki = str_replace(",","",trim($wiki[2]));
			if($wiki == "") 
				return "0";			
			else
				return $wiki;	
		}		
		
		public function getWikipediaClass($wiki)
		{
			if($wiki >= "0" && $wiki < "5"){
				$overall_score = $overall_score + 0.7;
				$wiki_class = "criterion bad";
			}
			elseif ($wiki > "5" && $wiki < "50")
			{
				$overall_score = $overall_score + 3.0;
				$wiki_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$wiki_class = "criterion good";			
			}		
			return $wiki_class;		
		}		
		
	}
?>