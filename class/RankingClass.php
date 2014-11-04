<?php
	class RankingClass extends Thread
	{
		public $traffic;
		public $alexa_rank;
		public $alexa_class;
		public $page_rank;
		public $pr_class;
		
		public function __construct($domain,$url)
		{
			$this->domain = $domain;
			$this->url = $url;
		}
		
		public function run() 
		{
			//$this->traffic = $this->getTraffic($this->domain);
			$this->alexa_rank =  $this->getAlexaRank($this->url);
			$this->alexa_class = $this->getAlexaRankCLass($this->alexa_rank);
			$this->page_rank = $this->getPageRank($this->url);
			$this->pr_class = $this->getPageRankClass($this->page_rank);
		}
		
		public function getTraffic($domain)
		{
			$compete_keys	=	array(
						'78ee1baeab42a4e76ec68651be8138ea',
						'6e20d25dd3e3c26046b60c62f0f421d8',
						'6b02d40725e841e6f2c2cba5a660e2fe',
						'1b46a6c0375651f3a3d20b249a253265',
						'feadf7fbf3ccbc22abae498defffd1e0'
					);	
							
			$random_key 		= array_rand($compete_keys);
			$compete_key 		= $compete_keys[$random_key];
			
			$data5 = file_get_contents("http://apps.compete.com/sites/$domain/trended/vis/?apikey=$compete_key");
			$data5 = json_decode($data5);
			$traffic = $data5->data->trends->vis[0]->value;
			if($traffic == "") 
				$traffic = "0";
			$traffic = number_format($traffic);
			if($traffic == "" || $traffic == "0") 
				$traffic = "N/A";
			return $traffic;	
		}
		
		public function getAlexaRank($domain)
		{
			$remote_url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url='.trim($domain);
			$search_for = '<POPULARITY URL';
			if ($handle = @fopen($remote_url, "r")) 
			{
				while (!feof($handle)) 
				{
					$part .= fread($handle, 100);
					$pos = strpos($part, $search_for);
					if ($pos === false)
					continue;
					else
					break;
				}
				$part .= fread($handle, 100);
				fclose($handle);
			}
			$str = explode($search_for, $part);
			$str = array_shift(explode('"/>', $str[1]));
			$str = explode('TEXT="', $str);			
			$alexa_rank =  str_replace('" SOURCE="panel',"",$str[1]);
			$alexa_rank = str_replace(",","",trim($alexa_rank));
			
			if($alexa_rank == "") 
				$alexa_rank = "N/A";
			return $alexa_rank;
		}
		
		public function getAlexaRankCLass($alexa_rank)
		{
			if($alexa_rank > "0" && $alexa_rank < "10000")
			{
				$overall_score = $overall_score + 4.0;
				$alexa_class = "criterion good";
			}
			elseif ($alexa_rank > "10000" && $alexa_rank < "1000000")
			{
				$overall_score = $overall_score + 3.0;
				$alexa_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 0.7;
				$alexa_class = "criterion bad";				
			}
			return $alexa_class;
		}
		
		//get google pagerank
		public function getPageRank($url) 
		{
			$query="http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".CheckHash(HashURL($url)). "&features=Rank&q=info:".$url."&num=100&filter=0";	
			$data=file_get_contents_curl($query);
			//print_r($data);
			$pos = strpos($data, "Rank_");
			if($pos === false)
			{}
			else
				$pagerank = substr($data, $pos + 9);
			
			$page_rank = str_replace(",","",trim($page_rank));							
			if($page_rank == "") 
				$page_rank = "N/A";
				
			return $pagerank;
		}
		
		public function getPageRankClass($page_rank) 
		{
			if($page_rank == "N/A" || $page_rank < "2")
			{
				$overall_score = $overall_score + 0.7;
				$pr_class = "criterion bad";
			}
			elseif ($page_rank < "5")
			{
				$overall_score = $overall_score + 3.0;
				$pr_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$pr_class = "criterion good";									
			}								
			return $pr_class;
		}
											
	}
?>