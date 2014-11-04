<?php

function get_domain_name($url){
	$domain	=	parse_url($url);
	return $domain['host'];
}

?>
<?php

	error_reporting(0);
	set_time_limit(0);
	$domain = "";
	$domain	= trim($_POST["domain"]);	
	
	if($_POST){	
		
		if(substr($domain, 0, 7) == "http://")	
			$domain	=	substr_replace($domain, "", 0, 7);
		$url	=	"http://" . $domain;
		$domain	=	str_replace("www.","",get_domain_name($url));
		include ("screenshot.php");
		echo $domain;
	}
?>
