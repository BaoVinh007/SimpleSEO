<?php
//------------------Begin pdf export----------------------------------
//	$url = $domain;
	include("functions.php");
	$here = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	$url = $_POST['url'];
	$url = trim(urldecode($url));
	if ($url == '') {
 	   exit();
	}

	if (!stristr($url, 'http://') and !stristr($url, 'https://')) {
    	$url = 'http://' . $url;
	}

	$url_segs = parse_url($url);
	$pdf_file = $url_segs['host'] . crc32($url) . '.pdf';
	if (!isset($url_segs['host'])) {
    	exit();
	}
    
	$src = "
			var casper = require('casper').create();
			casper.start();
			
			casper.page.paperSize = {
			  width: '11in',
			  height: '8.5in',
			  orientation: 'landscape',
			  border: '0.4in'
			};
			
			casper.thenOpen('$url', function() {
			  this.capture('pdf/$pdf_file');
			});			
			casper.run();
    ";
	$jobs = $here . 'jobs' . DIRECTORY_SEPARATOR;	
    $job_file = $jobs . $url_segs['host'] . crc32($src) . '_pdf.js';
    file_put_contents($job_file, $src);
	$exec_pdf = 'casperjs ' . $job_file;
 
    $escaped_command_pdf = escapeshellcmd($exec_pdf);
   	exec($escaped_command_pdf);
	echo $pdf_file;

?>