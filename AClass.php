<?php

session_start();

class AClass  
{
	public $root1 = "http://localhost/simpleseo/";
	
	public $domain1;
	
	public $url1;
	
	public $overscore1;
	
	public function __construct(){
		$domain1 = "";
		$url1 = "";
	}
	
	public function getRoot()
	{
		return $this->root1;	
	}
	
	public function getDomain()
	{
		return $this->domain1;	
	}
	
	public function getUrl()
	{
		return $this->url1;	
	}
	
	public function getOverScore()
	{
		return $this->overscore1;	
	}	
	
	public function setOverScore($s)
	{
		$this->overscore1 = $s;	
	}	
	
	public function setDomain($d)
	{
		$this->domain1 = $d;	
	}
	
	public function setUrl($u)
	{
		$this->url1 = $u;	
	}
	
	/*
	public function run(){
		if($this->arg){
			//printf("\nHello %s\n", $this->arg);
			$para = explode("&",$this->arg);
					
			$file = $para[0];
			$domain	= $para[1];
			$url = $para[2];
			$domain	=	get_domain_name($url);
			$domain = str_replace("www.","",$domain);			
			$ip = gethostbyname($domain);						
			include($file);
		}
	}
	*/
}
?>