<?php
	class ContentClass extends AClass
	{		
		public function GoogleIP($domain)
		{
			$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=site:".$domain."&filter=0&key=ABQIAAAAFKVsUTREbJ0drrYvTbZdHxReVx4SDhSo8vxayOty1oc4sCTPwhRhnrFjuQwqg1KEGUT6ikNVA8E4oQ&userip=USERS-IP-ADDRESS";
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
		
		public function GoogleIPCLass($indexed_pages)
		{
			if($indexed_pages == "") 
				$indexed_pages = "0";		
			if($indexed_pages >= "0" && $indexed_pages < "100")
			{
				$overall_score = $overall_score + 0.7;
				$index_class = "criterion bad";
			}
			elseif ($indexed_pages > "100" && $indexed_pages < "1000")
			{
				$overall_score = $overall_score + 3.0;
				$index_class = "criterion average";
			}else
			{
				$overall_score = $overall_score + 4.0;
				$index_class = "criterion good";			
			}
			return $index_class;
		}
		
		public function getPopularPages($content, $domain)
		{
			$internal_links = 0;
			$external_links = 0;
			$links_str	=	"";
			$internal_pages = array();
			preg_match_all('/<a(.*?)<\/a>/is',$content,$page_links);
			foreach ($page_links[0] as $key => $link){			
				preg_match('/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/is',$link,$anchor_link);
				if(trim(strip_tags($anchor_link[2])) != ""){
					if(substr($anchor_link[2], 0, 7) != "http://"){
						if(strip_tags($anchor_link[4])) 
							$internal_pages[] = $url.$anchor_link[2];
						if(strip_tags($anchor_link[4])) $links_str .= '<li><strong><a href="'.$url.$anchor_link[2].'" target="_blank">'.strip_tags($anchor_link[4]).'</a></strong></li>';
							$internal_links++;
						
					}else{
						preg_match("/$domain/is",$anchor_link[2],$check_link);
						
						if ($check_link[0]) {
							if(strip_tags($anchor_link[4])) $internal_pages[] = $anchor_link[2];
							if(strip_tags($anchor_link[4])) $links_str .= '<li><strong><a href="'.$anchor_link[2].'" target="_blank">'.strip_tags($anchor_link[4]).'</a></strong></li>';
							$internal_links++;
						}else{
							//$links_str .= '<a href="'.$anchor_link[2].'" target="_blank">'.strip_tags($anchor_link[4]).'</a><br />';						
							$external_links++;					
						}				
					}
				}
				if($key > 9) break;
			}	
			return $links_str;
		}
					
	}
?>