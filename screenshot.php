<?php
	
	$url = $domain;

	$url = trim(urldecode($url));
	if ($url == '') {
    	exit();
	}

	if (!stristr($url, 'http://') and !stristr($url, 'https://')) {
    	$url = 'http://' . $url;
	}

	$url_segs = parse_url($url);

	if (!isset($url_segs['host'])) {
    	exit();
	}

	//$here = "www/woorank".dirname(__FILE__) . DIRECTORY_SEPARATOR;
	$here = dirname(__FILE__) . DIRECTORY_SEPARATOR;

	//$bin_files = $here . 'casperjs/batchbin' . DIRECTORY_SEPARATOR;
	$jobs = $here . 'jobs' . DIRECTORY_SEPARATOR;
	$cache = $here . 'cache' . DIRECTORY_SEPARATOR;

	if (!is_dir($jobs)) {
    	mkdir($jobs);
	    file_put_contents($jobs . 'index.php', '<?php exit(); ?>');
	}

	if (!is_dir($cache)) {
    	mkdir($cache);
	    file_put_contents($cache . 'index.php', '<?php exit(); ?>');
	}

	$url = strip_tags($url);
	$url = str_replace(';', '', $url);
	$url = str_replace('"', '', $url);
	$url = str_replace('\'', '/', $url);
	$url = str_replace('<?', '', $url);
	$url = str_replace('<?', '', $url);
	$url = str_replace('\077', ' ', $url);

	//$screen_file = $url_segs['host'] . crc32($url) . '_' . $w . '_' . $h . '.jpg';
	$cache_job = $cache;

	$refresh = false;
	
	$url = escapeshellcmd($url);

	if (!is_file($cache_job) or $refresh == true) {

    	$src = "
			var casper = require('casper').create(),
			viewportSizes = [
				[320,480],    
				[600,1024],
				[1024,768]
			],
			url = casper.cli.args[0],
			//saveDir =	$cache.'';
			saveDir = url.replace(/[^a-zA-Z0-9]/gi, '-').replace(/^https?-+/, '');
		 
			casper.start();
		 
			casper.each(viewportSizes, function(self, viewportSize, i) {
		 
				// set two vars for the viewport height and width as we loop through each item in the viewport array
				var width = viewportSize[0],
					height = viewportSize[1];
			 
				//give some time for the page to load
				casper.wait(500, function() {
			 
					//set the viewport to the desired height and width
					this.viewport(width, height);
			 
					casper.thenOpen(url, function() {
						this.echo('Opening at ' + width);
			 
						//Set up two vars, one for the fullpage save, one for the actual viewport save
						//var FPfilename = saveDir + '/fullpage-' + width + '.png';
						var ACfilename = saveDir + '/' + width + '-' + height + '.png';
			 
						//Capture selector captures the whole body
						//this.captureSelector(FPfilename, 'body');
			 
						//capture snaps a defined selection of the page
						this.capture(ACfilename,{top: 0,left: 0,width: width, height: height});
						this.echo('snapshot taken');
					});
				});
			});
		 
			casper.run(function() {
				this.echo('Finished captures for ' + url).exit();
			});
			
			";
			
			$job_file = $jobs . $url_segs['host'] . crc32($src) . '.js';
			file_put_contents($job_file, $src);
		
			//$exec = $bin_files . 'casperjs ' . $job_file . ' '.$url;
			$exec = 'casperjs ' . $job_file . ' '.$url;
			$escaped_command = escapeshellcmd($exec);
			exec($escaped_command);
		/*	// VInh 
			$file_log = "test.txt";
			// Open the file to get existing content
			$current = file_get_contents($file_log);
			// Append a new person to the file
			$current .= $saveDir;
			// Write the contents back to the file
			file_put_contents($file_log, $current);
			// End---Vinh
		*/	
	}

//------------------End screenshots----------------------------------
?>