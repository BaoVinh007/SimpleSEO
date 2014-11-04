<?php
	class ComplianceClass extends AClass
	{	
		function getValidity($w3c_content)
		{
			preg_match('/<td colspan="2" class="invalid">(.*?)<\/td>/is',$w3c_content,$validity);
				
			if($validity[1] == "") 
				$validity = "Valid";
			else 
				$validity= "Invalid (".trim($validity[1]).")";		
			return $validity;	
		}
		
		function getValidityClass($validity)
		{
			if($validity == "Valid")
			{
				$overall_score = $overall_score + 4.0;
				$validity_class = "criterion good";
	
			}else
			{
				$overall_score = $overall_score + 0.9;
				$validity_class = "criterion bad";					
			}	
			return $validity_class;	
		}

		public function getGoogleAnalytic($content)
		{
			preg_match('/(\'|")UA-([0-9]+)-([0-9]{1,3})(\'|")/is',$content,$analytics);
			if($analytics[2] == "") 
				$analytics = "No";
			else 
				$analytics= "Yes";
			return $analytics;
		}
	
		public function getGoogleAnalyticClass($analytics)
		{
			if($analytics == "No")					
				$analytics_class = "criterion bad";
			else
				$analytics_class = "criterion good";						
			return $analytics_class;
		}
	
		public function getEncoding($content)
		{
			preg_match('/charset=(.*?)"/is',$content,$encoding);
			if($encoding[1] == "") 
				preg_match('/charset="(.*?)"/is',$content,$encoding);
			$encoding = $encoding[1];
			return $encoding;
		}
		
		public function getEncodingClass($encoding)
		{
			if($encoding == "Missing"){
				$overall_score = $overall_score + 0.9;
				$encoding_class = "criterion bad";	
			}else{
				$overall_score = $overall_score + 4.0;
				$encoding_class = "criterion good";			
			}
			return $encoding_class;
		}
		
		public function getDoctype($content)
		{
			preg_match('#<!DOCTYPE HTML PUBLIC "-//W3C//DTD (.*?)//#is',$content,$doctype);
			if($doctype[1] == "") 
				preg_match('#<!DOCTYPE (.*?)>#is',$content,$doctype);
			$doctype = $doctype[1];
			if($doctype == "html") 
				$doctype = "HTML 5";
			if($doctype == "") 
				$doctype = "Missing";
				
			return $doctype;
		}		

		public function getLang($content)
		{
			preg_match('/lang="(.*?)"/is',$content,$lang);
			$lang = $lang[1];	
			return $lang;
		}
		
		public function getLangClass($lang)
		{
			if($lang == "Missing"){			
				$lang_class = "criterion bad";
			}else{			
				$lang_class = "criterion good";			
			}	
			return $lang_class;
		}
	
		public function getXMLSiteMap($url)
		{
			$_headers	=	@get_headers("$url/sitemap.xml", 1);
			if (preg_match('/^HTTP\/\d\.\d\s+(200|301|302)/', $_headers[0]))
			   $sitemap_xml = "1"; 
			else
				$sitemap_xml = "";
			
			return $sitemap_xml;	
		}
		
		public function getSiteMap($sitemap_xml)
		{
			if($sitemap_xml != "")
			{
				$overall_score = $overall_score + 4.0;
				$sitemap = "$url/sitemap.xml";
				
			}else
			{
				$overall_score = $overall_score + 0.9;
				$sitemap = "No sitemap.xml Found";			
			}
			return $sitemap;
		}
		
		public function getXMLSiteMapClass($sitemap_xml)
		{
			if($sitemap_xml != "")
			{
				$overall_score = $overall_score + 4.0;
				$sitemap_class = "criterion good";
			}else
			{
				$overall_score = $overall_score + 0.9;
				$sitemap_class = "criterion bad";
			}
			return $sitemap_class;
		}
		
		public function getRobotTxt($url)
		{
			$robots_txt = @file_get_contents("$url/robots.txt");
			return $robots_txt;	
		}
		
		public function getRobot($robots_txt)
		{
			if($robots_txt != "")			
				$robot = "$url/robots.txt";			
			else							
				$robot = "No robots.txt Found";										
			return $robot;
		}
		
		public function getRobotClass($robots_txt)
		{
			if($robots_txt != "")
			{
				$robot_class = "criterion good";
			}else
			{				
				$robot_class = "criterion bad";
			}
			return $robot_class;
		}
		
		public function getCanonical($domain, $context)
		{
			$canonical = file_get_contents("http://www.$domain", false, $context);
			return $canonical;	
		}
		
		public function getCanonicalClass($canonical)
		{
			if($canonical != "")
			{
				$overall_score = $overall_score + 4.0;
				$can_class = "criterion good";
				
			}else
			{
				$overall_score = $overall_score + 1.6;
				$can_class = "criterion average";			
			}
			return $can_class;
		}
		
		public function getCan($canonical)
		{
			if($canonical != "")
			{
				$overall_score = $overall_score + 4.0;
				$can = "Good. Your website without www redirect to www (or the opposite)";
				
			}else
			{
				$overall_score = $overall_score + 1.6;
				$can = "Be careful! Your website without www doesn't redirect to www (or the opposite). It's duplicate content!";							
				
			}
			return $can;
		}
		
	}
?>