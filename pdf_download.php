<?php
	
	$filename = basename($_GET['file_name']);	
	$name = "pdf/".$filename;
	header('Content-type: application/pdf');
	header('Content-length: '.filesize( $name ));
	header('Content-Disposition: attachment; filename=' . $name );
	header('Cache-control: private');
	readfile($name);
?>