<?php
	class HomeAnalysisClass extends Thread
	{
		public $total_images;
		public $total_alt;		
		public $images_class;	
		public $code_ratio;		
		public $code_class;		
		public $frames;		
		public $frames_class;		
		public $flash;		
		public $flash_class;		
		public $webpage2text;		
		
		public function __construct($content)
		{
			//$this->domain = $domain;
			$this->content = $content;
		}
		
		public function run() 
		{
			$this->total_images = $this->getNumberOfImages($this->content);															
			$this->total_alt = $this->getNumberOfImageTags($this->content);
			$this->images_class = $this->getImageClass($this->total_images);		
			$this->code_ratio = $this->getCodeRatio($this->content);		
			$this->code_class = $this->getCodeRatioClass($this->code_ratio);	
			$this->frames = $this->getFrame($this->content);
			$this->frames_class = $this->getFrameClass($this->frames);			
			$this->webpage2text = $this->webpage2txt($this->content);
			$this->flash = $this->getFlash($this->content);
			$this->flash_class = $this->getFlashClass($this->flash);							
		}
		
		function getUrlData($contents)
		{
			$result = false;
		
			if (isset($contents) && is_string($contents))
			{
				$title = null;
				$metaTags = null;
		
				preg_match('/<title>([^>]*)<\/title>/si', $contents, $match );
		
				if (isset($match) && is_array($match) && count($match) > 0)
				{
					$title = strip_tags($match[1]);
				}
		
				preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' .'[lang="]*[^>"]*["]*'.'[\s]*content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
				if (isset($match) && is_array($match) && count($match) == 3)
				{
					$originals = $match[0];
					$names = $match[1];
					$values = $match[2];
		
					if (count($originals) == count($names) && count($names) == count($values))
					{
						$metaTags = array();
		
						for ($i=0, $limiti=count($names); $i < $limiti; $i++)
						{
							$metaname=strtolower($names[$i]);
							$metaname=str_replace("'",'',$metaname);
							$metaname=str_replace("/",'',$metaname);
							$metaTags[$metaname] = array (
							'html' => htmlentities($originals[$i]),
							'value' => $values[$i]
							);
						}
					}
				}
				if(sizeof($metaTags)==0) {
					preg_match_all('/<[\s]*meta[\s]*content="?' . '([^>"]*)"?[\s]*' .'[lang="]*[^>"]*["]*'.'[\s]*name="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
		
					if (isset($match) && is_array($match) && count($match) == 3)
					{
						$originals = $match[0];
						$names = $match[2];
						$values = $match[1];
		
						if (count($originals) == count($names) && count($names) == count($values))
						{
							$metaTags = array();
		
							for ($i=0, $limiti=count($names); $i < $limiti; $i++)
							{
								$metaname=strtolower($names[$i]);
								$metaname=str_replace("'",'',$metaname);
								$metaname=str_replace("/",'',$metaname);
								$metaTags[$metaname] = array (
									'html' => htmlentities($originals[$i]),
									'value' => $values[$i]
								);
							}
						}
					}
				}
		
				$result = array (
					'title' => $title,
					'metaTags' => $metaTags
				);
			}
		
			return $result;
		}
		
		public function getFlashClass($flash)
		{
			if($flash == "No")
			{
				$overall_score = $overall_score + 4.0;
				$flash_class = "criterion good";
			}else
			{
				$overall_score = $overall_score + 0.9;
				$flash_class = "criterion bad";			
			}
			return $flash_class;	
		}
		
		public function getFlash($content)
		{
			preg_match('/<(embed|object)/is',$content,$flash);
			if($flash[0] == "") 
				$flash = "No";
			else 
				$flash = "Yes";	
			return $flash;	
		}
		
		public function getFrameClass($frames)
		{
			if($frames == "No")						
				$frames_class = "criterion good";
			else
				$frames_class = "criterion bad";			
			
			return $frames_class;	
		}
		
		public function getFrame($content)
		{
			preg_match('/<(frame|iframe)/is',$content,$frames);
			if($frames[0] == "") 
				$frames = "No";
			else 
				$frames = "Yes";	
			return $frames;	
		}
		
		public function webpage2txt($document) {
									 
			$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
				'@<style[^>]*?>.*?</style>@si',    // Strip style tags properly
				'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				'@<![\s\S]*?–[ \t\n\r]*>@',         // Strip multi-line comments including CDATA
				'/\s{2,}/',
			);
		 
			$text = preg_replace($search, "\n", html_entity_decode($document));
		 
			$pat[0] = "/^\s+/";
			$pat[2] = "/\s+\$/";
			$rep[0] = "";
			$rep[2] = " ";
		 
			$text = preg_replace($pat, $rep, trim($text));
			
			$text = str_replace(array("\n\r", "\r\n", "\n", "\r", "\t"), " ", $text);
			$text = preg_replace('/\W/', ' ', $text);
			$text = preg_replace('/\s+/', ' ', $text);
			return $text;									
		}
 
		
		public function getCodeRatioClass($code_ratio)
		{
			if(ceil($code_ratio) < "5"){
				$overall_score = $overall_score + 0.7;
				$code_class = "criterion bad";
			}
			elseif (ceil($code_ratio) < "15"){
				$overall_score = $overall_score + 3.0;
				$code_class = "criterion average";
			}else{
				$overall_score = $overall_score + 4.0;
				$code_class = "criterion good";			
			}				
		
			return $code_class;
		}
		
		public function getCodeRatio($content)
		{
			$text	=	strip_tags($content);
			$ratio	=	sprintf("%01.2f", strlen($content) / strlen($text));
			return $ratio;
		}
		
		public function getImageClass($total_images)
		{
			if($total_images > "100" || $total_alt < 10){
				$overall_score = $overall_score + 0.7;
				$images_class = "criterion bad";
			}
			elseif ($total_images > "50" || $total_alt < "25"){
				$overall_score = $overall_score + 3.0;
				$images_class = "criterion average";
			}else{
				$overall_score = $overall_score + 4.0;
				$images_class = "criterion good";			
			}
			return $images_class;
		}
		
		public function getNumberOfImageTags($content)
		{
			preg_match_all('/<img(.*?)alt="(.*?)"(.*?)>/is',$content,$alt_images);
			$total_alt = count($alt_images[2]);			
			return $total_alt;
		}
		
		public function getNumberOfImages($content)
		{
			preg_match_all('/<img(.*?)>/is',$content,$page_images);
			$total_images = count($page_images[1]);
			return $total_images;
		}
		
		public function getHeadClass($h1, $h2, $h3)
		{
			if (($h1 < 1 && $h2 < 1 && $h3 < 1) || ($h1 > 2 && $h2 > 2 && $h3 > 5)) {			
				$overall_score = $overall_score + 0.7;
				$h_class = "criterion bad";			
			}elseif(($h1 < 1 && $h2 < 1 && $h3 < 1) || ($h1 > 1 && $h2 > 1 && $h3 > 3)){
				$overall_score = $overall_score + 3.0;
				$h_class = "criterion average";			
			}else{
				$overall_score = $overall_score + 4.0;
				$h_class = "criterion good";			
			}
			return $h_class;
		}	
	
		public function getKeywordsClass($keywords_length)
		{
			if($keywords_length > "200" || $keywords_length < "20")
			{
				$overall_score = $overall_score + 0.7;
				$keywords_class = "criterion bad";
			}
			elseif ($keywords_length > "150" || $keywords_length < "50")
			{
				$overall_score = $overall_score + 3.0;
				$keywords_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$keywords_class = "criterion good";
				
			}								
			return $keywords_class;
		}
		
		public function truncate_string ($string, $maxlength, $extension) 
		{
		   $cutmarker = "**cut_here**";
		   if (strlen($string) > $maxlength) 
		   {
			   $string = wordwrap($string, $maxlength, $cutmarker);
			   $string = explode($cutmarker, $string);
			   $string = $string[0] . $extension;
		   }		
		   return $string;
		}
		
		public function getTitleClass($title_length)
		{
			if($title_length > "100" || $title_length < "10")
			{
				$overall_score = $overall_score + 0.7;
				$title_class = "criterion bad";
			}
			elseif ($title_length > "80" || $title_length < "20")
			{
				$overall_score = $overall_score + 3.0;
				$title_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$title_class = "criterion good";			
			}	
			return $title_class;
		}
		
		public function getDescriptionClass($description_length)
		{
			if($description_length > "255" || $description_length < "50"){
				$overall_score = $overall_score + 0.7;
				$description_class = "criterion bad";
			}
			elseif ($description_length > "200" || $description_length < "100"){
				$overall_score = $overall_score + 3.0;
				$description_class = "criterion average";
			}else{
				$overall_score = $overall_score + 4.0;
				$description_class = "criterion good";			
			}	
			return $description_class;
		}
		
	}
?>