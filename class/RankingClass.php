<?php
	class RankingClass extends Thread
	{
		public $traffic;
		public $alexa_rank;
		public $alexa_class;
		public $page_rank;
		public $pr_class;
		public $domain;
		public $url;
		public $output;
		
		
		public function __construct($domain,$url)
		{
			$this->domain = $domain;
			$this->url = $url;
		}
		
		public function run() 
		{
			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
			$txt = "John Doe\n";
			fwrite($myfile, $txt);
			$txt = "Jane Doe\n";
			fwrite($myfile, $txt);
			fclose($myfile);
			
			
			//$this->traffic = $this->getTraffic($this->domain);
			$this->alexa_rank =  $this->getAlexaRank($this->url);
			$this->alexa_class = $this->getAlexaRankCLass($this->alexa_rank);
			$this->page_rank = $this->getPageRank($this->url);
			$this->pr_class = $this->getPageRankClass($this->page_rank);
			
			
			$ouput = '
						<h2>Visitors</h2>	
                        <div class="report_section" id="section_visitors">
                            <div class="report_section_inner">
								<div class="criterion">
                                    <div class="criterion_value">
                                        <h4>Traffic estimation</h4>                            
	                                        <div>About '.$this->traffic.' Unique visitors/month	</div>
											<div class="score_label"></div>
											<a href="#" class="advice_toggle advice_open">Show advice</a>
									</div>
									<div class="criterion_info_advice">
                                        <div class="criterion_advice">
                                            <div>
                                                <p>You can use several different tools to estimate web traffic: <a rel="nofollow" href="https://www.google.com/adplanner/#siteSearch?identifier='.$this->domain.'" target="_blank">Google&trade; Ad Planner</a>, <a rel="nofollow" href="http://trends.google.com/websites?q='.$this->domain.'" target="_blank">Google&trade; Trends</a>, and <a rel="nofollow" href="http://www.alexa.com/siteinfo/'.$this->domain.'" target="_blank">Alexa</a>.</p>
                                                <p>Nevertheless, your analytics will provide the accurate traffic data.</p>																				 											</div>
                                        </div>
									</div>
								</div>
								<div class="'.$this->alexa_class.'">
									<div class="criterion_value">
										<h4>Alexa rank</h4>
										<div>
											<p>'.number_format($this->alexa_rank).'</p>	
										</div>
										<div class="score_label"></div>
										<a href="#" class="advice_toggle advice_open">Show advice</a>
									</div>
									<div class="criterion_info_advice">
										<div class="criterion_info">
											<div class="criterion_importance level2">
												<div class="icons"></div>
												<div class="text">High impact</div>
											</div>
										</div>
										<div class="criterion_advice">
											<div>
                                                <p>A low rank means that your website gets lots of visitors.</p><p>Your <a rel="nofollow" href="http://www.alexa.com/siteinfo/'.$this->domain.'" target="_blank">Alexa Rank</a> is a good estimate of worldwide traffic to your website, although it is <a rel="nofollow" href="http://blog.alexa.com/2009/08/alexa-101-anatomy-of-traffic-rank-graph.html" target="_blank">not 100% accurate</a>.</p><p>Reviewing the <a rel="nofollow" href="http://www.alexa.com/topsites/countries" target="_blank">most visited websites</a> by country can give you valuable insights.</p><p><a rel="nofollow" href="http://www.quantcast.com/'.$this->domain.'" target="_blank">Quantcast</a> provides similar services.</p>																			 											</div>
										</div>
									</div>
								</div>
                                                                
								<div class="'.$this->pr_class.'">
                                    <div class="criterion_value">
                                    <h4>Page rank</h4>
                                    <div>'.$this->page_rank.'</div>
									<div class="score_label"></div>
									<a href="#" class="advice_toggle advice_open">Show advice</a>
								</div>
								<div class="criterion_info_advice">
									<div class="criterion_info">
                                        <div class="criterion_importance level2">
                                            <div class="icons"></div>
                                            <div class="text">High impact</div>
                                        </div>
									</div>
                                    <div class="criterion_advice">
                                        <div>
                                            <p>PageRank is a way to determine the relevance or importance of the websites/webpages and it is numerically represented from 0 to 10 largely based on number and quality of the backlinks.</p>														
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
			';
			
			$this->output = $ouput;
			
			
			
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