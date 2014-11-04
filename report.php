<?php

	
	include("AClass.php");
	error_reporting(0);
	set_time_limit(0);

	$aclass = new AClass();
	$root = $aclass->root1;
	//$root = "http://localhost/woorank/";
	//$root = "http://radiusonline.com/woorank/";
	include("functions.php");
	
	$domain	= trim($_GET["domain"]);
	
	$aclass->setDomain($domain);

	if(substr($domain, 0, 7) == "http://")	
		$domain	=	substr_replace($domain, "", 0, 7);

	$url	=	"http://" . $domain;
	$aclass->setUrl($url1);
	$domain	=	get_domain_name($url);
	$domain = str_replace("www.","",$domain);			
	$ip = gethostbyname($domain);
	if($ip == $domain)
	{
		echo "<br /><br /><br /><br /><center><h3>please enter valid domain</h3></center>";
		exit();
	}else
	{
		$_SESSION['encoded_aclass'] = serialize($aclass);
		//include("a.php");-----start-----
		ob_start();		
		
		$encoded_aclass = $_SESSION['encoded_aclass'];	
		$aclass = unserialize($encoded_aclass);
		
		$domain = $aclass->getDomain();
		
		$overall_score = 0;	
		$url	=	"http://" . $domain;

		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
	
		$StartTime = $time;
		$content = file_get_contents($url);
		
		$loc_content = file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=8a0f5b54b5550b30d7088cdc9c4dc95969cb5a6120e5730f577e5a98adf704db&ip=$ip&format=json");
		$loc_content = json_decode($loc_content);
		$result = getUrlData($content);		

		include("class/RankingClass.php");									
		$ranking_website = new RankingClass();
		$traffic = $ranking_website->getTraffic($domain);
		
		$ranking_websiteThread = new RankingClass($domain,$url);
		
		$ranking_websiteThread->start();
		$ranking_websiteThread->join();
						
		include("class/ContentClass.php");
		$contentClass = new ContentClass();		
		$links_str = $contentClass->getPopularPages($content,$domain);
		
		// Define the options
		$options = array('max_redirects' => 0 );
		 
		// Create a context with options for the http stream
		$context = stream_context_create(array('http' => $options));

		$overall_score = number_format($overall_score,1);
		$rating_array = explode(".",$overall_score);
		
		$first_rating = $rating_array[0];
		$second_rating = $rating_array[1];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo $aclass->getDomain() ?> Website Analysis</title>
		<meta name="description" content="Website Analysis &amp; Internet Marketing tips for your website. Optimize your website and find an Internet marketing expert." />
        <link rel="stylesheet" type="text/css" href="<?php echo $aclass->getRoot() ?>/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

        <link href="<?php echo $aclass->getRoot() ?>reset.css" rel="stylesheet" type="text/css"  />
        <link href="<?php echo $aclass->getRoot() ?>common.css" rel="stylesheet" type="text/css"  />
        <link href="<?php echo $aclass->getRoot() ?>home.css" rel="stylesheet" type="text/css"  />
        <link href="<?php echo $aclass->getRoot() ?>report.css" rel="stylesheet" type="text/css"  />
        <link href="<?php echo $aclass->getRoot() ?>layout.css" rel="stylesheet" type="text/css"  />
        <link href="<?php echo $aclass->getRoot() ?>w1.css" rel="stylesheet" type="text/css"  />

	</head>
	<body>
		<div id="home" class="en">
			<div id="top" style="height:50px;">
				<div id="top-nav">
                    <ul id="service" class="nav">
                        <li class="current"></li>
                    </ul>                
                </div>			
            </div>				
			
			<div id="content">			
                <table border="0" height="80%" style="margin-left:140px;">
                    <tr>
                        <td width="40%"><img src="<?php echo $aclass->getRoot() ?>images/seo.jpg" align="bottom" border="0" /></td>
                        <td width="70%" style="padding-left:30px;">		
                            <div id="main_form">
                                <form id="analyze" name="analyze" action="report.php" method="get">
                                    <div>
                                        <input class="text" type="text" id="domain" name="domain" value=""/>
                                        <span class="input_bg">client-domain.com</span>
                                        <input id="submit_btn" type="submit" class="submit" value="SEO Report" />		
                                    </div>
                            
                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
			</div>

		<?php
			include("class/HomeAnalysisClass.php");
			$homeAnalysis = new HomeAnalysisClass();
			
			$result = getUrlData($content);		
			$content = file_get_contents($url);			
			
			$description=$result['metaTags']['description']['value'];
			
			$trim_description =  $homeAnalysis->truncate_string($description,255,"...");
			$description_length = strlen($description);
		
			if ($description_length == "0") 
				$description_length = "No";
	
			$description_class = $homeAnalysis->getDescriptionClass($description_length);
	
			$title=$result['title'];
			$title_length = strlen($title);
			
			if ($title_length == "0") 
				$title_length = "No";
				
			$title_class = $homeAnalysis->getTitleClass($title_length);	
			

		?>	
		<div id="report">
			<div id="report_header" class="hreview">
                <div id="report_header_content">
                    <div id="report_header_content_inner">
                        <h2 class="item">Report for <a href="<?php echo $url ?>" target="_blank"><?php echo $domain ?></a></h2><br />
                        <p id="date">Generated on <?php echo date("F j, Y, g:i a"); ?></p>
                        <div id="description">
						<?php 
							if($description)
							{
								echo $trim_description;
							}else
							{
								echo $title;
							} 
						?>
                        </div>            
                    </div>
					<div id="rank" class="rating">
                        <div class="value"><strong><?php echo $first_rating ?></strong><span>.<?php echo $second_rating ?></span></div>
                        <div id="outof">out of <strong class="best">100</strong></div>
                        <a href="/en/ranking/stats">?</a>
                    </div>
                    <div id="screenshot">
                    <?php
                            //url = 'http://todomvc.com';
                            $new_path = str_replace("http://",'',$url);
                            $new_path = str_replace('.','-',$new_path);
                        
                        ?>
                    <img border="0" width="100" height="100" alt = "screenshot_desktop" src="<?php echo $aclass->getRoot().$new_path."/600-1024.png"; ?>"/>
                    <!--
                    <img border="0" width="100" height="100" alt = "screenshot_desktop" src="<?php //echo $aclass->getRoot().$new_path."/600-1024.png"; ?>"/>
                    <img src="http://immediatenet.com/t/m?Size=1024x768&URL=<?php //echo $domain; ?>" border="0" />
                    -->
                    </div>
        <!--		<div id="report_info">
                    <div id="report_info_inner">This is the of <strong></strong>! The average rank so far is <strong>43.1</strong>!</div>
                    </div>
        -->		
				</div>
                <div id="report_header_actions">            
                    <div id="report_header_actions_inner">
                        <div id="report_header_actions_left">
                        </div>
                        <div id="report_header_actions_right">
                        
                        </div>
                    </div>
            
                </div>
                
            </div>

            <div id="report_content">                
                <div id="report_nav">                    
                    <div id="report_quick_access">
                        <ul>
                            <li><a href="#main_section_visitors">&rsaquo; Visitors</a></li>
                    
                            <li><a href="#main_section_content">&rsaquo; Content</a></li>
                            <li>
                                <a href="#main_section_seo_on_site">&rsaquo; In-Site SEO</a>
                                <ul>
                                    <li><a href="#section_home_analysis">Home analysis</a></li>
                                    <li><a href="#section_inside_analysis">Inside analysis</a></li>
                    
                                    <li><a href="#section_website_compliance">Website compliance</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#main_section_seo_off_site">&rsaquo; Off-Site SEO</a>
                                <ul>
                                    <li><a href="#section_popularity">Popularity</a></li>
                    
                                    <li><a href="#section_directories">Directories</a></li>
                                    <li><a href="#section_social_media">Social Media</a></li>
                                </ul>
                            </li>
                            <li><a href="#main_section_usability">&rsaquo; Usability</a></li>
                            <li>
                                <a href="#main_section_website_informations">&rsaquo; Website informations</a>
                    
                                <ul>
                                    <li><a href="#section_server">Server</a></li>
                                    <li><a href="#section_domain">Domain</a></li>
                                    <li><a href="#section_related">Related websites</a></li>
                                    <li><a href="#section_other_facts">Other facts</a></li>
                                </ul>
                            </li>                    
                        </ul>
                    </div>		
				</div>

                <div id="report_results">
                    <div class="report_main_section" id="main_section_screenshots">
                        <h2>Screenshots</h2>
                        
                        <div class="report_section" id="section_screenshots">
                            Desktop
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Tablet
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Mobile
                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" name="pdf_export" id="pdf_export" class="btn btn-default" >PDF Export</button>
                            <br /> <br />
                          <!--  
                            <img width="100" height="width="100"" name="screenshot_view" id="screenshot_view" alt = "screenshot" src="<?php //echo $aclass->getRoot().$new_path."/1024-768.png"; ?>"/>-->
                            <img width="100" height="100" name="screenshot_desktop" id="screenshot_desktop" alt = "screenshot_desktop" src="<?php echo $aclass->getRoot().$new_path."/1024-768.png"; ?>"/>
                            &nbsp;&nbsp;
                            <img width="100" height="100" name="screenshot_tablet" id="screenshot_tablet" alt = "screenshot_tablet" src="<?php echo $aclass->getRoot().$new_path."/600-1024.png"; ?>"/>
                            &nbsp;&nbsp;
                            <img width="100" height="100" name="screenshot_mobile" id="screenshot_mobile" alt = "screenshot_mobile" src="<?php echo $aclass->getRoot().$new_path."/320-480.png"; ?>"/>            	
                        </div>
			        </div>
			        <br />
                    
					<div class="report_main_section" id="main_section_visitors">		
                    	<?php
							echo $ranking_websiteThread->output;
							
							$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
							$txt = $ranking_websiteThread->output;
							fwrite($myfile, $txt);
							
							fclose($myfile);
							
						?>
						<!--
                        	Put output of thread1 here:
                            	- just read a txt file and paste it here
                            
                        -->
                    </div>	
                </div>

			<?php

				$indexed_pages = $contentClass->GoogleIP($domain);
				$index_class = $contentClass->GoogleIPCLass($indexed_pages);							

			?>
			<div class="report_main_section" id="main_section_content">		
				<h2>Content</h2>	
                <div class="report_section" id="section_content">
                    <div class="report_section_inner">
						<div class="<?php echo $index_class ?>">
                            <div class="criterion_value">
                                <h4>Indexed pages</h4>
                                <div><?php echo number_format($indexed_pages) ?></div>
                                <div class="score_label"></div>
                                <a href="#" class="advice_toggle advice_open">Show advice</a>
                            </div>
							<div class="criterion_info_advice">
								<div class="criterion_info">
									<div class="criterion_importance level2">
										<div class="icons"></div>
										<div class="text">High impact</div>
									</div>
									<div class="criterion_solvability level2">
										<div class="icons"></div>
										<div class="text">Difficult to solve</div>
									</div>
								</div>
								<div class="criterion_advice">
									<div>
										<p>This is the number of pages on your website that are <a rel="nofollow" href="http://www.google.com/search?q=site:<?php echo $domain ?>" target="_blank">indexed by Google&trade;</a>.</p><p>The more pages that search engines index, the better.</p><p>A low number (relative to the total number of pages/URLs on your website) probably indicates that your internal link architecture needs improvement and is preventing Search engines from crawling all pages on your website.</p><p>Resource: Check <a rel="nofollow" href="http://www.copyscape.com/" target="_blank">here</a> to see if your content has been plagiarized. Then make test with specified keywords in the search engine to analyze your relative position for these keywords.</p>														
                                    </div>
                                </div>
                            </div>
						</div>
                        <div class="criterion">
	                        <div class="criterion_value">
                            <h4>Popular pages</h4>
    	                        <div>                                                                       
                                    <ul><?php echo $links_str ?><ul>	
								</div>
								<div class="score_label"></div>
								<a href="#" class="advice_toggle advice_open">Show advice</a>
							</div>
							<div class="criterion_info_advice">
								<div class="criterion_advice">
									<div>
										<p>This lists the popular pages on your website.</p>														
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
   			<?php
				$domain_explode = explode(".",$domain);
				$domain_length = strlen($domain_explode[0]);
			?>
            <div class="report_main_section" id="main_section_seo_on_site">       
                <h2>In-Site SEO</h2>
                <div class="report_section" id="section_home_analysis">
                    <h3>Home analysis</h3>
                    <div class="report_section_inner">
						<div class="criterion">
							<div class="criterion_value">
								<h4>URL</h4>
								<div>
									<p><a href="<?php echo $url ?>" target="_blank"><?php echo $url ?></a></p>
									<p><strong>Length</strong>: <?php echo $domain_length ?> characters</p>
								</div>
								<div class="score_label"></div>
									<a href="#" class="advice_toggle advice_open">Show advice</a>
							</div>
							<div class="criterion_info_advice">
								<div class="criterion_advice">
									<div>
										<p>Keep your URLs short. If possible, avoid long domain names.</p><p>A descriptive URL is better recognized by search engines. A user should be able to look at the address bar and make an accurate guess about the content of the page before reaching it (e.g., http://www.mysite.com/en/products).</p><p>Keep in mind that URLs are an important part of a comprehensive <a rel="nofollow" href="http://www.seomoz.org/blog/11-best-practices-for-urls" target="_blank">SEO strategy</a>.</p><p>Use clean URLs to <a rel="nofollow" href="http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=147959" target="_blank">make your site more "crawlable" by Google&trade;</a>.</p><p>Resource: Search for a good domain name <a rel="nofollow" href="http://instantdomainsearch.com/" target="_blank">here</a>. If no good names are available, consider a second hand domain from <a rel="nofollow" href="http://sedo.com" target="_blank">Sedo</a>.</p><p>To prevent brand theft, you might consider trademarking your domain name.</p>														
                                    </div>
                                </div>
                            </div>
						</div>                       
                       
                        <div class="<?php echo $title_class ?>">
                            <div class="criterion_value">
                                <h4>Title</h4>
	                            <div>
									<p><?php echo $title ?></p>
									<p><strong>Length</strong>: <?php echo $title_length ?> characters</p>	
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
									<div class="criterion_solvability level1">
                                        <div class="icons"></div>
                                        <div class="text">Easy to solve</div>
									</div>
								</div>
								<div class="criterion_advice">
									<div>
										<p>Ideally, your title should contain between 10 and 70 characters (spaces included).</p><p>Make sure your title is explicit and contains your <a rel="nofollow" href="http://blog.magnoliasoft.net/2009/01/title-tag-best-practices-10-step-guide.html" target="_blank">most important keywords</a>.</p><p>Be sure that each page has a unique title.</p><p>Resource: Use this <a rel="nofollow" href="http://www.seomofo.com/snippet-optimizer.html" target="_blank">snippet-optimizer</a> to see how your titles and descriptions will look in Google&trade search results.</p>				
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="<?php echo $description_class ?>">
                            <div class="criterion_value">
                                <h4>Meta description</h4>
                                <div>
									<p><?php echo $description ?></p>
									<p><strong>Length</strong>: <?php echo $description_length ?> characters</p>	
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
                                    <div class="criterion_solvability level1">
										<div class="icons"></div>
											<div class="text">Easy to solve</div>
										</div>
									</div>
									<div class="criterion_advice">
										<div>
											<p><a rel="nofollow" href="http://en.wikipedia.org/wiki/Meta_element#The_description_attribute" target="_blank">Meta descriptions</a> allow you to influence how your web pages are described and displayed in search results.</p>																<p>Your <a rel="nofollow" href="http://en.wikipedia.org/wiki/Meta_element#The_description_attribute" target="_blank">meta description</a> contains between 70 and 160 characters, which is great.</p><p>Ensure that your meta description is explicit and contains your <a rel="nofollow" href="http://googlewebmastercentral.blogspot.com/2007/09/improve-snippets-with-meta-description.html" target="_blank">most important keywords</a>.</p><p>Also be sure that each page has a unique meta description.</p>									
                                        </div>
									</div>
								</div>
							</div>
                            
                            <?php
								
								$keywords=$result['metaTags']['keywords']['value'];
								$keywords_length = strlen($keywords);
									
								if ($keywords_length == "0") 
									$keywords_length = "No";

								$keywords_class = $homeAnalysis->getKeywordsClass($keywords_length);
						
							?>
                            
							<div class="<?php echo $keywords_class ?>">
								<div class="criterion_value">
                                    <h4>Meta keywords</h4>
                                    <div>
										<p><?php echo $keywords ?></p>
										<p><strong>Length</strong>: <?php echo $keywords_length ?> characters</p>	
									</div>
									<div class="score_label"></div>
										<a href="#" class="advice_toggle advice_open">Show advice</a>
								</div>
								<div class="criterion_info_advice">
									<div class="criterion_advice">
										<div>
											<p><a rel="nofollow" href="http://en.wikipedia.org/wiki/Meta_element#The_keywords_attribute" target="_blank">Meta keywords</a> is used to indicate keywords that are supposedly relevant to your website's content. However, because search engine spammers have abused this tag, it provides little to no benefit to your search rankings.</p>														
                                        </div>
                                    </div>
                                </div>
							</div>
                           	<?php
								$h1 = 0;
								$h2 = 0;
								$h3 = 0;
								$h4 = 0;
								$h5 = 0;
								$h6 = 0;
								
								preg_match_all('/<h([1-6])(.*?)<\/(h[1-6])>/is',$content,$h_tags);
								
								foreach ($h_tags[3] as $h){
								
									$h = strtolower($h);
									if($h == "h1") 
										$h1++;
									if($h == "h2") 
										$h2++;
									if($h == "h3") 
										$h3++;
									if($h == "h4") 
										$h4++;
									if($h == "h5") 
										$h5++;
									if($h == "h6") 
										$h6++;
								}
								
								$h_class = $homeAnalysis->getHeadClass($h1, $h2, $h3);		
							
							?>
							<div class="<?php echo $h_class ?>">
								<div class="criterion_value">
									<h4>Headings</h4>
									<div>
										<table id="headings_table">
                                            <tr>
                                                <th>H1</th>
                                                <th>H2</th>
                                                <th>H3</th>                                        
                                                <th>H4</th>
                                                <th>H5</th>
                                                <th>H6</th>
                                            </tr>
                                            <tr>
                                                <td class="center"><?php echo $h1 ?></td>
                                                <td class="center"><?php echo $h2 ?></td>                                        
                                                <td class="center"><?php echo $h3 ?></td>
                                                <td class="center"><?php echo $h4 ?></td>
                                                <td class="center"><?php echo $h5 ?></td>
                                                <td class="center"><?php echo $h6 ?></td>
                                            </tr>
                                        </table>
									</div>
                                    <div class="score_label"></div>
                                    <a href="#" class="advice_toggle advice_open">Show advice</a>
                                </div>
								<div class="criterion_info_advice">
									<div class="criterion_info">
                                        <div class="criterion_importance level1">
                                            <div class="icons"></div>
                                            <div class="text">Low impact</div>
                                        </div>
										<div class="criterion_solvability level1">
                                            <div class="icons"></div>
                                            <div class="text">Easy to solve</div>
                                        </div>
									</div>
									<div class="criterion_advice">
										<div>
											<p>Use your keywords in the headings. Make sure the first level (&lt;H1&gt;) includes your most important keywords.</p><p>For greater <a rel="nofollow" href="http://en.wikipedia.org/wiki/Search_engine_optimization" target="_blank">SEO</a>, only use one &lt;H1&gt; title per page.</p>									</div>
                                    </div>
                                </div>
							</div>
							<?php
							
								$homeAnalysisThread = new HomeAnalysisClass($content);
								$homeAnalysisThread->start();
								$homeAnalysisThread->join();																
								
								
								$total_images = $homeAnalysisThread->total_images;															
								$total_alt = $homeAnalysisThread->getNumberOfImageTags($content);
								
								$missing_alt = $total_images - $total_alt;
									
								$images_class = $homeAnalysisThread->getImageClass($total_images);			
							
							?>
                            <div class="<?php echo $images_class ?>">
                                <div class="criterion_value">
                                    <h4>Images</h4>
                                    <div>
										<p>We found <strong><?php echo $total_images ?></strong> images on this website.</p>
										<p><strong><?php echo $missing_alt ?></strong> alt attributes are empty or missing!</p>
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
										<div class="criterion_solvability level1">
                                            <div class="icons"></div>
                                            <div class="text">Easy to solve</div>
										</div>
									</div>
									<div class="criterion_advice">
										<div>
											<p>Alternative text (<a rel="nofollow" href="http://en.wikipedia.org/wiki/Alt_attribute" target="_blank">the alt attribute</a>) is missing for several images. Add alternative text so that search engines can better understand the content of your images.</p><p>Remember that search engine crawlers cannot actually "see" images. The alternative text attribute allows you to assign a specific description to each image.</p><p>Alternative text describes your images so they can appear in <a rel="nofollow" href="http://images.google.com/" target="_blank">Google&trade Images</a> search results.</p><p>Check the images on your website and make sure <a rel="nofollow" href="http://www.webcredible.co.uk/user-friendly-resources/web-accessibility/image-alt-text.shtml" target="_blank">effective ALT text</a> is specified for each image.</p><p><a rel="nofollow" href="http://www.seosmarty.com/image-seo/" target="_blank">Click here</a> to find out how to optimize images for search engines.</p><p>Restrict the number and size of images to optimize your website's page load times.</p><p>Resource: Use the <a rel="nofollow" href="http://www.archive.org/" target="_blank">Wayback Machine</a> to review the design of any website in the past.</p>									
                                        </div>
                                    </div>
                                </div>
							</div>
                            <?php
							
								function _getCodeRatio($contents)
								{
									$text	=	strip_tags($contents);
									$ratio	=	sprintf("%01.2f", strlen($contents) / strlen($text));
									return $ratio;
								}
															
								$code_ratio = $homeAnalysisThread->code_ratio;
								$code_class = $homeAnalysisThread->code_class;

							?>                 
                            
                            <div class="<?php echo $code_class ?>">
                                <div class="criterion_value">
                                    <h4>Text/HTML ratio</h4>
                                    <div>
										<p class="ratio"><?php echo $code_ratio ?>% <span>(<a href="#" id="view_text_link">view text</a>)</span></p>	
	                                    <div id="view_text_content"><?php echo $homeAnalysisThread->webpage2text; ?></div>							
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
									<div class="criterion_solvability level2">
                                        <div class="icons"></div>
                                        <div class="text">Difficult to solve</div>
									</div>
								</div>
								<div class="criterion_advice">
									<div>
										<p>If Your website's ratio of text to HTML code is below 15%, that means that your website probably needs more text content.</p><p>Improve your SEO by adding more relevant text to your pages and increasing your <a rel="nofollow" href="http://www.keyworddensity.com/" target="_blank">keyword density</a>.</p>									
                                    </div>
                                </div>
                            </div>
						</div>
                        <?php
							$frames = $homeAnalysisThread->frames;
							$frames_class = $homeAnalysisThread->frames_class;	
									
						?>
						<div class="<?php echo $frames_class ?>">
                            <div class="criterion_value">
                                <h4>Frames</h4>
                                <div>
									<em><?php echo $frames ?></em>	
								</div>
								<div class="score_label"></div>
								<a href="#" class="advice_toggle advice_open">Show advice</a>
							</div>
							<div class="criterion_info_advice">
								<div class="criterion_info">
                                    <div class="criterion_importance level1">
                                        <div class="icons"></div>
										<div class="text">Low impact</div>
									</div>
									<div class="criterion_solvability level2">
										<div class="icons"></div>
										<div class="text">Difficult to solve</div>
									</div>
								</div>
								<div class="criterion_advice">
									<div>
										<p>Frames can cause problems for search engines because they don't correspond to the <a rel="nofollow" href="http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=34445" target="_blank">conceptual model of the web</a>. Avoid frames whenever possible.</p>									
                                    </div>
                                </div>
                            </div>
						</div>
                        <?php
							$flash = $homeAnalysisThread->flash;
							$flash_class = $homeAnalysisThread->flash_class;							
						?>
                        
						<div class="<?php echo $flash_class ?>">
							<div class="criterion_value">	
                                <h4>Flash</h4>
                                <div>
									<em><?php echo $flash ?></em>	
								</div>
							<div class="score_label"></div>
							<a href="#" class="advice_toggle advice_open">Show advice</a>
						</div>
						<div class="criterion_info_advice">
							<div class="criterion_info">
                                <div class="criterion_importance level1">
                                    <div class="icons"></div>
                                    <div class="text">Low impact</div>
                                </div>
                                <div class="criterion_solvability level2">
                                    <div class="icons"></div>
                                    <div class="text">Difficult to solve</div>
                                </div>
							</div>
							<div class="criterion_advice">
								<div>
									<p>Flash should only be used for specific enhancements. Avoid full Flash websites to maximize SEO.</p><p>Although Flash content often looks nicer, it cannot be indexed by search engines (however <a rel="nofollow" href="http://www.beussery.com/blog/index.php/2008/10/google-flash-seo/" target="_blank">this may change</a> in the near future).</p><p>This advice also applies to <a rel="nofollow" href="http://en.wikipedia.org/wiki/Ajax_(programming)" target="_blank">AJAX</a> (however <a rel="nofollow" href="http://googlewebmastercentral.blogspot.com/2009/10/proposal-for-making-ajax-crawlable.html" target="_blank">this may also change</a> in the near future).</p>									
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
            <?php
				// include("a.php");// -----end------
				
				// include("b.php");// -----start----
			?>        	
			<div class="report_section" id="section_inside_analysis">
				<h3>Inside analysis</h3>
				<div class="report_section_inner">
					<div class="criterion">
						<div class="criterion_value">
							<h4>Inside pages analysis</h4>
							<div>									
								<p class="titles">&nbsp;</p>
    
                                <table>	
                                    <tr>
                                        <th style="width:40%">Title</th>
                                        <th style="width:40%">Meta description</th>
                                        <!--th style="width:15%">Google PageRank</th-->
                                        <th style="width:20%">Text/HTML ratio</th>
                                    </tr>                                    
                                        <?php 
										                                        
                                            $page1_content = file_get_contents($internal_pages[5]);
                                            $page2_content = file_get_contents($internal_pages[6]);
                                            $page3_content = file_get_contents($internal_pages[7]);											
											
											function _getMetaTitle($content){
												$pattern = "|<[\s]*title[\s]*>([^<]+)<[\s]*/[\s]*title[\s]*>|Ui";
												$resTitle = preg_match_all($pattern, $content, $match);
												$title=$match[1][0];
												return $title;
											}
											
											function _getMetaDescription($content) {
												$urldata = getUrlData($content);
												return $urldata['metaTags']['description']['value'];
											}
                                
                                        ?>
                                    <tr>	
                                        <td><a href="<?php echo $internal_pages[1]; ?>" target="_blank"><?php echo _getMetaTitle($page1_content); ?></a></td>                                        <td><?php echo _getMetaDescription($page1_content); ?></td>
                                        <!--td class="center"></td-->
                                        <td class="center"><?php echo _getCodeRatio($page1_content); ?>%</td>
                                    </tr>
                                    <tr>	
                                        <td><a href="<?php echo $internal_pages[2]; ?>" target="_blank"><?php echo _getMetaTitle($page2_content); ?></a></td>
                                        <td><?php echo _getMetaDescription($page2_content); ?></td>                                
                                        <!--td class="center"></td-->
                                        <td class="center"><?php echo _getCodeRatio($page2_content); ?>%</td>
                                    </tr>
                                    <tr>	
                                        <td><a href="<?php echo $internal_pages[3]; ?>" target="_blank"><?php echo _getMetaTitle($page3_content); ?></a></td>
                                        <td><?php echo _getMetaDescription($page3_content); ?></td>
                                        <!--td class="center"></td-->
                                        <td class="center"><?php echo _getCodeRatio($page3_content); ?>%</td>                                
                                    </tr>
                                </table>
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
								<div class="criterion_solvability level1">
									<div class="icons"></div>
									<div class="text">Easy to solve</div>
								</div>
							</div>
                            <div class="criterion_advice">
			                    <div>
									<p>Use <a rel="nofollow" href="http://www.google.com/webmasters/" target="_blank">Google&trade; Webmaster Tool</a> to improve the way search engines index your website.</p>									
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>    
            
            <?php
		//include("b.php");-------end
		//include("c.php");-------start
		?>
        			<?php
				include("class/ComplianceClass.php");
				
				$complianceClass = new ComplianceClass();
				$canonical = $complianceClass->getCanonical($domain, $context);
				// Pass the options to file_get_contents. The second argument is whether to use the include path,
				//$canonical = file_get_contents("http://www.$domain", false, $context);
				$can_class = $complianceClass->getCanonicalClass($canonical);
				$can = $complianceClass->getCan($canonical);				

			?>
			<div class="report_section" id="section_website_compliance">
				<h3>Website compliance</h3>
				<div class="report_section_inner">
					<div class="<?php echo $can_class; ?>">
						<div class="criterion_value">
							<h4>www resolve</h4>
							<div><?php echo $can; ?></div>
							<div class="score_label"></div>
							<a href="#" class="advice_toggle advice_open">Show advice</a>
						</div>
						<div class="criterion_info_advice">
							<div class="criterion_info">
                            <div class="criterion_importance level2">
                                <div class="icons"></div>
                                <div class="text">High impact</div>
                            </div>
                            <div class="criterion_solvability level1">
								<div class="icons"></div>	
								<div class="text">Easy to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p><a rel="nofollow" href="http://www.google.com/support/webmasters/bin/answer.py?answer=44231" target="_blank">Redirecting requests</a> from a non-preferred hostname is important because search engines consider URLs with and without "www" as two different websites.</p><p>Once your preferred domain is set, use a <a rel="nofollow" href="http://www.google.com/support/webmasters/bin/answer.py?answer=93633" target="_blank">301 redirect</a> for all traffic to your non-preferred domain.</p>									
                            </div>
                        </div>
                    </div>
				</div>
                <?php
					$robot_txt = $complianceClass->getRobotTxt($url);
					$robot_class = $complianceClass->getRobotClass($robot_txt);
					$robot = $complianceClass->getRobot($robot_txt);
				?>
                
				<div class="<?php echo $robot_class; ?>">
                    <div class="criterion_value">
                        <h4>robots.txt</h4>
                        <div><?php echo $robot; ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level2">
								<div class="icons"></div>
								<div class="text">High impact</div>
							</div>
							<div class="criterion_solvability level1">
								<div class="icons"></div>
								<div class="text">Easy to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>A <a rel="nofollow" href="http://www.robotstxt.org/orig.html" target="_blank">robots.txt file</a> allows you to restrict the access of search engine robots that crawl the web, and it can prevent these robots from accessing specific directories and pages. It also specifies where the XML sitemap file is located.</p><p><a rel="nofollow" href="http://tool.motoricerca.info/robots-checker.phtml" target="_blank">Click here</a> to check your robots.txt file for syntax errors.</p>									
                            </div>
						</div>
					</div>
				</div>
                <?php
					$sitemap_xml = $complianceClass->getXMLSiteMap($url);
					$sitemap = $complianceClass->getSiteMap($sitemap_xml);
					$sitemap_class = $complianceClass->getXMLSiteMapClass($sitemap_xml);
				?>
                
                <div class="<?php echo $sitemap_class; ?>">
                    <div class="criterion_value">
                        <h4>XML Sitemaps</h4>
	                    <div>									
                            <ul>				

	                            <li><?php echo $sitemap; ?></li>				
                            </ul>	
						</div>
						<div class="score_label"></div>
                        <a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
                            <div class="criterion_importance level1">
                                <div class="icons"></div>
								<div class="text">Low impact</div>
							</div>
							<div class="criterion_solvability level1">
                                <div class="icons"></div>
                                <div class="text">Easy to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>A sitemap lists URLs that are available for crawling and can include additional information like last update, frequency of changes, and importance. This allows search engines to crawl the site more intelligently.</p><p>Despite sporadic <a rel="nofollow" href="http://www.seomoz.org/blog/expert-advice-on-google-sitemaps-verify-but-dont-submit" target="_blank">debates</a> regarding this issue, we recommend that you submit an XML sitemap to <a rel="nofollow" href="http://www.google.com/webmasters/tools/" target="_blank">Google&trade; Webmasters Tools</a> and to <a rel="nofollow" href="https://siteexplorer.search.yahoo.com/submit" target="_blank">Yahoo Site Explorer</a>.</p>									
                            </div>
                        </div>
                    </div>
				</div>
                <?php

					$lang = $complianceClass->getLang($content);
					if($lang == "") 
						$lang = "Missing";
					$lang_class = $complianceClass->getLangClass($lang);
					
				?>
                
                <div class="<?php echo $lang_class; ?>">
                    <div class="criterion_value">
                        <h4>Language</h4>
                        <div>                                            
							<ul>				
								<li><strong>Declared</strong>: <em><?php echo $lang; ?></em></li>				
							</ul>	
						</div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level1">
								<div class="icons"></div>
								<div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level1">
							<div class="icons"></div>
							<div class="text">Easy to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>Tips for multilingual websites:</p><ul><li>Define the language of the content in each page's HTML code.</li><li>Specify the language code in the URL as well (e.g., "mywebsite.com/fr/mycontent.html").</li><li>Check out <a rel="nofollow" href="http://www.toprankblog.com/2009/10/top-10-pitfalls-of-international-seo/" target="_blank">these tips for building a multilingual website</a>.</li></ul>									
                        </div>
					</div>
				</div>
			</div>
            <?php
				
				$doctype = $complianceClass->getDoctype($content);;				
				
			?>
            
			<div class="criterion">
				<div class="criterion_value">
					<h4>Doctype</h4>
					<div><?php echo $doctype; ?></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_advice">
						<div>
							<p>Declaring a <a rel="nofollow" href="http://www.w3schools.com/tags/tag_DOCTYPE.asp" target="_blank">doctype</a> helps web browsers to render content correctly.</p>														
                        </div>
                    </div>
                </div>
			</div>
            <?php
				
				$encoding = $complianceClass->getEncoding($content);
				
				if($encoding == "") 
					$encoding = "Missing";
				$encoding_class	= $complianceClass->getEncodingClass($encoding);

			?>
            
			<div class="<?php echo $encoding_class; ?>">
                <div class="criterion_value">
                    <h4>Encoding</h4>
                    <div><?php echo $encoding; ?></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
						<div class="criterion_importance level1">
                            <div class="icons"></div>
                            <div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level1">
							<div class="icons"></div>
							<div class="text">Easy to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>Specifying language/character encoding can prevent problems with the rendering of <a rel="nofollow" href="http://en.wikipedia.org/wiki/Character_encoding" target="_blank">special characters</a>.</p>									
                        </div>
					</div>
				</div>
			</div>
            <?php

				$analytics  = $complianceClass->getGoogleAnalytic($content);								
				$analytics_class  = $complianceClass->getGoogleAnalyticClass($analytics);
				
			?>
            
            <div class="<?php echo $analytics_class; ?>">
                <div class="criterion_value">  
                    <h4>Google Analytics</h4>
                    <div><em><?php echo $analytics; ?></em></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
                        <div class="criterion_importance level1">
                            <div class="icons"></div>
							<div class="text">Low impact</div>
                        </div>
                        <div class="criterion_solvability level1">
                            <div class="icons"></div>
                            <div class="text">Easy to solve</div>
                        </div>
					</div>
					<div class="criterion_advice">
                        <div>
                            <p>Be sure to leverage <a rel="nofollow" href="http://analytics.blogspot.com/2009/02/urban-apparel-and-advanced-segments.html" target="_blank">its full potential</a>.</p>								
                        </div>
                    </div>
                </div>
            </div>

            <?php
				$w3c_content = $popularityClassThread->w3c_content;				
				$validity = $complianceClass->getValidity($w3c_content);
				$validity_class = $complianceClass->getValidityClass($validity);
			?>
            
            <div class="<?php echo $validity_class; ?>">
                <div class="criterion_value">
                    <h4>W3C validity</h4>
                    <div><?php echo $validity; ?></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
                        <div class="criterion_importance level1">
                            <div class="icons"></div>
                            <div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level2">
							<div class="icons"></div>
							<div class="text">Difficult to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>Use valid markup that contains no errors. Syntax errors can make your page difficult for search engines to index.</p><p>To fix the detected errors, run the <a rel="nofollow" href="http://validator.w3.org/check?uri=<?php echo $url ?>" target="_blank">W3C validation service</a>.</p><p><a rel="nofollow" href="http://www.w3.org/Consortium/" target="_blank">W3C</a> is a consortium that sets the web standards.</p>									
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
		
</div>
	<?php
		
		include("class/PopularityClass.php");					
		$popularityClass = new PopularityClass();							
		$popularityClassThread = new PopularityClass($domain, $url);
		$popularityClassThread->start();
		$popularityClassThread->join();							
		$crawl = $popularityClassThread->crawl;
		$crawl_class = $popularityClassThread->crawl_class;
		
	?>
	<div class="report_main_section" id="main_section_seo_off_site">	
        <h2>Off-Site SEO</h2>	
        <div class="report_section" id="section_popularity">
            <h3>Popularity</h3>
            <div class="report_section_inner">
				<div class="<?php echo $crawl_class; ?>">
					<div class="criterion_value">
						<h4>Google last crawl date</h4>
							<div><em><?php echo $crawl; ?></em></div>
							<div class="score_label"></div>
							<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_advice">
							<div>
								<p>Google&trade; periodically crawls websites looking for new and updated content. In general, you want Google&trade; to crawl your site as often as possible so your new content shows up in search results.</p><p>Click <a rel="nofollow" href="http://www.google.com/search?q=cache:<?php echo $domain; ?>&hl=en" target="_blank">here</a> to ensure your website's content and links have been indexed by Google&trade;.</p><p>If <a rel="nofollow" href="http://www.google.com/search?q=cache:<?php echo $domain; ?>&hl=en&strip=1" target="_blank">Google's&trade; cache</a> of your website lacks text or links, there's probably a programming problem.</p>														
                            </div>
                        </div>
                    </div>
				</div>
                <?php
					
					$backlinks =  $popularityClassThread->backlinks;							
					$backlinks_class = $popularityClassThread->backlinks_class;

				?>
                
				<div class="<?php echo $backlinks_class; ?>">
                    <div class="criterion_value">
                        <h4>Backlinks</h4>
                        <div><?php echo number_format($backlinks); ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level2">
								<div class="icons"></div>
								<div class="text">High impact</div>
							</div>
							<div class="criterion_solvability level2">
								<div class="icons"></div>
								<div class="text">Difficult to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>Backlinks <a rel="nofollow" href="http://www.google.com/search?q=%22<?php echo $domain; ?>%22" target="_blank">links that point to your website</a> from other websites. It's like a popularity rating for your website.</p><p>Since this factor is <a rel="nofollow" href="http://www.seomoz.org/article/search-ranking-factors" target="_blank">crucial to SEO</a>, you should have <a rel="nofollow" href="http://www.seounique.com/blog/anchor-text-optimization/" target="_blank">a strategy</a> to improve the quantity and quality of backlinks.</p>															
                            </div>
                        </div>
                    </div>
				</div>
                <?php
					
					$edu = $popularityClassThread->edu;		
					$edu_class = $popularityClassThread->edu_class;					
				?>
                <div class="<?php echo $edu_class; ?>">
                    <div class="criterion_value">
                        <h4>.edu backlinks</h4>
                        <div><?php echo $edu; ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level2">
								<div class="icons"></div>
								<div class="text">High impact</div>
							</div>
							<div class="criterion_solvability level2">
								<div class="icons"></div>
								<div class="text">Difficult to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>Because they are strictly reserved for educational institutions, .edu domains are considered authority sites.</p><p>Links to your website from <a rel="nofollow" href="http://search.yahoo.com/search?p=site:.edu+linkdomain:<?php echo $domain; ?>" target="_blank">.edu domains</a> have <a rel="nofollow" href="http://www.articlesbase.com/link-popularity-articles/backlink-edu-power-with-links-and-guest-posts-1321865.html" target="_blank">stronger SEO impact</a>.</p>														
                            </div>
                        </div>
                    </div>
				</div>
                <?php			
									
					$gov = $popularityClassThread->gov;						
					$gov_class = $popularityClassThread->gov_class;
					
				?>
				<div class="<?php echo $gov_class; ?>">
                <div class="criterion_value">
                    <h4>.gov backlinks</h4>
                    <div><?php echo $gov; ?></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
                        <div class="criterion_importance level2">
                            <div class="icons"></div>
                            <div class="text">High impact</div>
						</div>
						<div class="criterion_solvability level2">
							<div class="icons"></div>
							<div class="text">Difficult to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>Because they are strictly reserved for government institutions, .gov domains are considered authority sites.</p><p>Links to your website from <a rel="nofollow" href="http://search.yahoo.com/search?p=site:.gov+linkdomain:<?php echo $domain; ?>" target="_blank">.gov domains</a> domains have <a rel="nofollow" href="http://www.webseo.com.au/blog/search-engine-optimization-tips/dofollow-gov-edu-approved-backlinks/" target="_blank">stronger SEO impact</a>.</p>														
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    
    
    <?php
		//include("class/DirectoriesClass.php");	
		//$directoriesClass = new DirectoriesClass();
		
		$dmoz = $popularityClassThread->dmoz;
		$dmoz_class = $popularityClassThread->dmoz_class;
		
	?>
	<div class="report_section" id="section_directories">
        <h3>Directories</h3>
        <div class="report_section_inner">
	        <div class="<?php echo $dmoz_class ?>">
				<div class="criterion_value">
                    <h4>DMOZ</h4>
                    <div><em><?php echo $dmoz ?></em></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
						<div class="criterion_importance level2">
                            <div class="icons"></div>
                            <div class="text">High impact</div>
                        </div>
						<div class="criterion_solvability level1">
							<div class="icons"></div>
							<div class="text">Easy to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p><a rel="nofollow" href="http://search.dmoz.org/cgi-bin/search?search=<?php echo $domain ?>" target="_blank">DMOZ</a>, a multilingual <a rel="nofollow" href="http://en.wikipedia.org/wiki/Dmoz" target="_blank">open content directory</a> constructed and maintained by a comty of volunteer editors.</p><p><a rel="nofollow" href="http://www.dmoz.org/add.html" target="_blank">Submitting your website</a> is important because search engines take DMOZ into account.</p>									
                        </div>
					</div>
				</div>
			</div>
            <?php
			
				$yahoodir = $popularityClassThread->yahoodir;
				$yahoodir_class = $popularityClassThread->yahoodir_class;
					
			?>
			<div class="<?php echo $yahoodir_class ?>">
                <div class="criterion_value">
                    <h4>Yahoo! Directory</h4>
                    <div><em><?php echo $yahoodir ?></em></div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
                        <div class="criterion_importance level1">
                            <div class="icons"></div>
                            <div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level1">
							<div class="icons"></div>
							<div class="text">Easy to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>The <a rel="nofollow" href="http://en.wikipedia.org/wiki/Yahoo%21_Directory" target="_blank">Yahoo! Directory</a> is a web directory which rivals DMOZ.</p><p>It offers two options for suggesting websites for possible listing: "<a rel="nofollow" href="http://help.yahoo.com/l/us/yahoo/helpcentral/" target="_blank">Standard</a>" (which is free) and a <a rel="nofollow" href="https://ecom.yahoo.com/dir/submit/intro/" target="_blank">paid submission</a> process that offers expedited review.</p>									
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
	<?php
		
		include("class/SocialMediaClass.php");		
				
		$social_network = new SocialMediaClass();	
		$fb = $social_network->getFBlikes($url);
		$fb_likes = $fb['likes'];
		$fb_like_class = $social_network->getFBLikeCLass($fb_likes);
			
	?>
	<div class="report_section" id="section_social_media">
        <h3>Social Media</h3>
        <div id="donutchart" style="width: 500px; height: 250px;"></div>
        <div class="report_section_inner">
			<div class="<?php echo $fb_like_class; ?>">
				<div class="criterion_value">
                    <h4>Facebook Likes</h4>
                    <div>
						<?php echo number_format( $fb_likes );?>	
					</div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
						<div class="criterion_importance level1">
							<div class="icons"></div>
							<div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level2">
							<div class="icons"></div>
							<div class="text">Difficult to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>"Like" is a way to give positive feedback or to connect with things you care about on Facebook. You can like content that your friends post to give them feedback or like a Page that you want to connect with on Facebook. You can also connect to content and Pages through social plugins or advertisements on and off Facebook. </p>														</div>
						</div>
					</div>
				</div>

                <?php
					//$googleplus1 = get_google_plus1_count($url."/");		
					$googleplus1 = $social_network->getGooglePlus($url."/");		
					$googleplus1_class = $social_network->getGooglePlusCLass($googleplus1);
					
				?>
				<div class="<?php echo $googleplus1_class ?>">
                    <div class="criterion_value">
                        <h4>Google +1s</h4>
                        <div><?php echo number_format($googleplus1); ?>	</div>
					<div class="score_label"></div>
					<a href="#" class="advice_toggle advice_open">Show advice</a>
				</div>
				<div class="criterion_info_advice">
					<div class="criterion_info">
						<div class="criterion_importance level1">
							<div class="icons"></div>
							<div class="text">Low impact</div>
						</div>
						<div class="criterion_solvability level2">
							<div class="icons"></div>
							<div class="text">Difficult to solve</div>
						</div>
					</div>
					<div class="criterion_advice">
						<div>
							<p>The +1 button lets you start great conversations. When you click +1 you're publicly recommending pages across the web. You can also use +1 to share with the right circles on Google+.</p>														</div>
						</div>
					</div>
				</div>

                <?php
					//$tweet = get_tweets_count($url);
					$tweet = $social_network->getTweets($url);
					$tweet_class = $social_network->getTweetsClass($tweet);
				?>
                
                <div class="<?php echo $tweet_class ?>">
                    <div class="criterion_value">
                        <h4>Tweets</h4>
                        <div><?php echo number_format($tweet); ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level1">
                                <div class="icons"></div>
                                <div class="text">Low impact</div>
							</div>
							<div class="criterion_solvability level2">
                                <div class="icons"></div>
                                <div class="text">Difficult to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>Click <a rel="nofollow" href="http://backtweets.com/search?q=<?php echo $domain ?>" target="_blank">here</a> to browse the most recent tweets related to your website.</p><p>Monitoring recent tweets tells you what people are saying about your website in real-time.</p><p>Resource: Monitor your brand in Twitter using tools like <a rel="nofollow" href="http://topsy.com/" target="_blank">Topsy</a>.</p>														
                            </div>
                        </div>
                    </div>
                </div>
                <?php
				
					$wiki = $social_network->getWikipedia($domain);
					$wiki_class = $social_network->getWikipediaClass($wiki);
					
				?>
                
                <div class="<?php echo $wiki_class ?>">
                    <div class="criterion_value">
                        <h4>Wikipedia backlinks</h4>
                        <div><?php echo number_format($wiki); ?></div>
                        <div class="score_label"></div>
                        <a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
							<div class="criterion_importance level2">
                                <div class="icons"></div>
                                <div class="text">High impact</div>
							</div>
							<div class="criterion_solvability level2">
                                <div class="icons"></div>
                                <div class="text">Difficult to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>References to your website were <a rel="nofollow" href="http://search.yahoo.com/search?p=site:wikipedia.org+linkdomain:<?php echo $domain ?>" target="_blank">found on Wikipedia</a>.</p><p>Since Wikipedia is currently the world's largest wiki, you should consider <a rel="nofollow" href="http://www.wikihow.com/Write-a-Wikipedia-Article" target="_blank">contributing a Wikipedia article</a> about your website.</p>									
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>   
   	
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" language="javascript" runat="server">

        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Social Media'],
                ['Facebook Likes', <?php echo intval($fb_likes);?>],
                ['Google Plus',    <?php echo intval($googleplus1);?>],
                ['Twitter',        <?php echo intval($tweet);?>],
                ['Wikipedia',      <?php echo intval($wiki);?>]
            ]);
            
            var options = {
                title: 'Social Media',
                pieHole: 0.4,
            };
        
            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>
		<?php
		//include("c.php");-------end
		//include("d.php");-------start
		?>
        
        <div class="report_main_section" id="main_section_seo_recommendation">
        <h2>SEO Recommendation</h2>    
        <div class="report_section" id="section_seo_recommendation">
			<h4>
	           	<button id="btn_high_priority_SEOdescriptions" >HIGH PRIORITY</button>
               	<button id="btn_medium_priority_SEOdescriptions" >MEDIUM PRIORITY</button>
               	<button id="btn_low_priority_SEOdescriptions" >LOW PRIORITY</button>
            </h4>
            <p id="high_priority_SEOdescriptions" >
            	We found some dynamic urls on your site. Unless they are needed for tracking purposes, we recommend to remove them.
                <br/>
            	You should avoid having more than 100 links on any given page.
            </p>
    
            <p id="medium_priority_SEOdescriptions" style="display: none"> 
            	We've noticed that your &lt; meta content=&quot;description&quot; &gt; tag is too long. It's best to keep your meta description between 150 and 160 characters.
                <br/>
            	We found links with the rel=&quot;nofollow&quot; attribute on them, these links don't influence the target's Page Rank.
                <br/>
                Title tags are an important on-page factor for SEO. Your title tags should contain fewer than 70 characters. This is the limit Google displays in search results.
                <br/>
                Create more content rich pages, as it will help you rank for more long tail keywords.                
            </p>
            
            <p id="low_priority_SEOdescriptions" style="display: none">
            	We've noticed that you have &lt;H&gt; heading tags that are too long or too short. Its best to keep your heading tags between 15 and 65 characters.
                <br/>
            	We found &lt;image&gt; tags that don't include the alt attribute. Since search engines can't see the image, including an alt tag helps search engines know what you are showing on your site.
                <br/>
            	The majority of the keywords are not found in the site title. Make sure you place your most descriptive keywords in the site title.
				<br/>
                The majority of the keywords are not found in the meta description. Make sure you place your most descriptive keywords in the meta description.                <br/>                
                The majority of the keywords are not found in heading tags. Make sure you place your most descriptive keywords in heading tags.
            </p>
        </div>
    </div>
    
   	<div class="report_main_section" id="main_section_speed_recommendation">
        <h2>Speed Recommendation</h2>    
        <div class="report_section" id="section_speed_recommendation">
			<h4>
	           	<button id="btn_high_priority_descriptions" >HIGH PRIORITY</button>
               	<button id="btn_medium_priority_descriptions" >MEDIUM PRIORITY</button>
               	<button id="btn_low_priority_descriptions" >LOW PRIORITY</button>
            </h4>
            <p id="high_priority_descriptions" >
            	Look to combine all scripts into a single script, and similarly combining all CSS into a single stylesheet. This is the key to faster pages. 
            	<a href="https://developer.yahoo.com/performance/rules.html#num_http">Learn more</a>
                <br/>
            	Minify JavaScript and CSS. Minification is the practice of removing unnecessary characters from code to reduce its size thereby improving load times. 
            	<a href="https://developer.yahoo.com/performance/rules.html#minify">Learn more</a>
            </p>
    
            <p id="medium_priority_descriptions" style="display: none">
            	Reducing the number of unique hostnames has the potential to reduce the amount of parallel downloading that takes place in the page. Avoiding DNS lookups cuts response times.
            	<a href="https://developer.yahoo.com/performance/rules.html#cns_lookups">Learn more</a>
                <br/>
            	Browsers download no more than two components in parallel per hostname. While a script is downloading, however, the browser won't start any other downloads which will slow down your site load. 
            	<a href="https://developer.yahoo.com/performance/rules.html#js_bottom">Learn more</a>
            </p>
            
            <p id="low_priority_descriptions" style="display: none">
            	Compress components with gzip, this generally reduces the response size by about 70%. 
            	<a href="https://developer.yahoo.com/performance/rules.html#gzip">Learn more</a>
                <br/>
            	Configure entity tags (ETags). Entity tags (ETags) are a mechanism that web servers and browsers use to determine whether the component in the browser's cache matches the one on the origin server.  
            	<a href="https://developer.yahoo.com/performance/rules.html#etags">Learn more</a>
                <br/>
            	Reduce the number of DOM elements. A complex page means more content to download and it also means slower DOM access in JavaScript.   
            	<a href="https://developer.yahoo.com/performance/rules.html#min_dom">Learn more</a>
            </p>
        </div>
    </div>
    
    <?php
		$favicon = file_get_contents($url."/favicon.ico");
			
		if($favicon != "") 
			$favicon = "<img src='$url/favicon.ico' border='0' height='16' width='16' />";
		else $favicon = "No";
			
		if($favicon == "No"){			
			$favicon_class = "criterion bad";	
		}else{				
			$favicon_class = "criterion good";				
		}		
	?>
    
	<div class="report_main_section" id="main_section_usability">
        <h2>Usability</h2>    
        <div class="report_section" id="section_usability">
            <div class="report_section_inner">
                <div class="<?php echo $favicon_class ?>">
                    <div class="criterion_value">
                        <h4>Favicon</h4>
                        <div><?php echo $favicon ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
                            <div class="criterion_importance level2">
                                <div class="icons"></div>
                                <div class="text">High impact</div>   
                            </div>
							<div class="criterion_solvability level1">
                                <div class="icons"></div>
                                <div class="text">Easy to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
                            <div>
                                <p><a rel="nofollow" href="http://en.wikipedia.org/wiki/Favicon" target="_blank">favicon</a>, is great to have on website. Make sure favicon is <a rel="nofollow" href="http://www.howtojoomla.net/2007050865/how-tos/miscellaneous/how-to-change-your-favicon" target="_blank">consistent with your brand</a>.</p><p>Resource: Check out this <a rel="nofollow" href="http://www.youtube.com/watch?v=0KDjjePkd2U" target="_blank">amazing idea</a> for improving user experience with a special favicon.</p>									
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
		include("class/WebInfoClass.php");
		$webInfoClass = new WebInfoClass();

		$load = $webInfoClass->getLoadTime();
		$load_class = $webInfoClass->getLoadTimeClass($load);
	?>
	<div class="report_main_section" id="main_section_website_informations">
        <h2>Website informations</h2>
        <div class="report_section" id="section_server">
            <h3>Server</h3>
            <div class="report_section_inner">
	            <div class="<?php echo $load_class ?>">
 				   <div class="criterion_value">
                       <h4>Load time</h4>
                       <div><?php echo $load ?> second(s)</div>
                        <div class="score_label"></div>
                        <a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_info">
                            <div class="criterion_importance level2">
                                <div class="icons"></div>
                                <div class="text">High impact</div>
                            </div>
							<div class="criterion_solvability level2">
                                <div class="icons"></div>
                                <div class="text">Difficult to solve</div>
							</div>
						</div>
						<div class="criterion_advice">
							<div>
								<p>Your website is fast. Well done.</p><p>Site speed is becoming an <a rel="nofollow" href="http://searchengineland.com/site-speed-googles-next-ranking-factor-29793" target="_blank">important factor</a> for ranking high in Google&trade; search results</p><p>Resource: Check out <a rel="nofollow" href="http://code.google.com/speed/articles/" target="_blank">these other tips</a> to make your website run faster.</p><p>Resource: Monitor your server and receive SMS alerts when your website is down with <a rel="nofollow" href="http://www.google.com/search?q=Web+Monitoring+services" target="_blank">Web Monitoring</a> Services.</p>									
                            </div>
                        </div>
                    </div>
				</div>
				<div class="criterion">
					<div class="criterion_value">
                        <h4>IP</h4>
                        <div><?php echo $ip ?></div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_advice">
							<div>
								<p>Your server's IP address <a rel="nofollow" href="http://www.ocsblog.com/2009/10/seo-dedicated-ip-myth-still-around/" target="_blank">has no impact</a> on your SEO.</p><p>Use <a rel="nofollow" href="http://www.robtex.com/dns/<?php echo $domain ?>.html" target="_blank">Robtex</a> and <a rel="nofollow" href="http://www.dnsstuff.com/tools/" target="_blank">DNSstuff</a> for comprehensive reports on your domain name server.</p>														
                            </div>
						</div>
					</div>
				</div>
                <?php
					
					$city = $loc_content->cityName;
					$region = $loc_content->regionName;
					$country = $loc_content->countryName;
					$latitude = $loc_content->latitude;
					$longitude = $loc_content->longitude;				
				?>
				<div class="criterion">
					<div class="criterion_value">
                        <h4>Location</h4>
                        <div>
							<p class="country">		
								<span><?php echo $city ?>, <?php echo $region ?>, <?php echo $country ?></span>
							</p>
							<p><img src="http://maps.google.com/maps/api/staticmap?center=<?php echo $latitude ?>,<?php echo $longitude ?>&amp;zoom=4&amp;size=400x300&amp;maptype=roadmap&amp;markers=color:blue|label:S|<?php echo $latitude ?>,<?php echo $longitude ?>&amp;sensor=false&amp;key=ABQIAAAAXuX847HLKfJC60JtneDOUhQ8oGF9gkOSJpYWLmRvGTmYZugFaxRX7q0DDCWBSdfC1tIHIXIZqTPM-A" alt="" /></p>
						</div>
						<div class="score_label"></div>
						<a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_advice">
							<div>
								<p>To improve your website's responsiveness, locate your servers close to your main markets.</p><p>Click <a rel="nofollow" href="http://network-tools.com/default.asp?prog=ping&host=%s" target="_blank">here</a> to test your website speed. Everything is OK if the average time over 10 pings is less than 300ms.</p>														
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
        
		<?php
			if($webInfoClass->ValidateIP($domain)) {
				$whois = $webInfoClass->LookupIP($domain);
			}
			elseif($webInfoClass->ValidateDomain($domain)) {
				$whois = $webInfoClass->LookupDomain($domain);
			}
			else{ 
				$whois = "Not Found";		
			}		
		?>	
		<div class="report_section" id="section_domain">
			<h3>Domain</h3>
			<div class="report_section_inner">
				<div class="criterion">
                    <div class="criterion_value">
                        <h4>Whois</h4>          
                        <div>
                            <pre>
                            <?php echo trim($whois) ?>
                            </pre>							
                        </div>
                        <div class="score_label"></div>
                        <a href="#" class="advice_toggle advice_open">Show advice</a>
					</div>
					<div class="criterion_info_advice">
						<div class="criterion_advice">
							<div>
								<p>Old domains are ranked higher by search engines and yield better SEO results.</p><p>Google&trade; temporarily reduces the page rank of new domains, placing them into a "<a rel="nofollow" href="http://en.wikipedia.org/wiki/Sandbox_Effect" target="_blank">sandbox</a>".</p><p>Using a domain that has been registered for many years can mitigate this effect.</p><p>Consider these <a rel="nofollow" href="http://www.redflymarketing.com/blog/the-5-easiest-ways-to-get-search-engines-to-trust-you/" target="_blank">Whois tips</a> to further improve your SEO.</p>														
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div id="message" class="msg_widget">
	<h4><a href="#" target="_blank"></a></h4>
	<a href="#" class="close">Close</a>
</div>
</div>

<a href="#top" id="back_to_top">Back to top</a>

		</div>			
			
			<div id="footer">
				<ul class="nav">
                    <li><strong>Website Analysis</strong> &copy; 2011</li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Ranking</a></li>
                
                    <li><a href="#">Widgets</a></li>
                    <li class="faq_link"><a href="#">FAQ</a></li>	
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Terms of Service</a></li>
				</ul>
           </div>
		</div>
		<div id="overlayimage" style="display:none;"><img src="<?php echo $root; ?>images/loader.gif" /></div>
	</body>
</html>
		
		<?php
		
		$content = ob_get_flush();
		file_put_contents("sites/{$domain}.html", $content);		
	}
?>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
    </style>    
    
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $root ?>/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
    <script type="text/javascript" src="<?php echo $root ?>report.js" ></script>

       
    <script type="text/javascript">
    
		$(document).ready(function(){
			
			$("#analyze").submit(function() {
				$.ajax({
					type: "POST",
					url: "<?php echo $root ?>ajax.php",
					data: $("#analyze").serialize(),
					/*
						beforeSend: function(XMLHttpRequest){
							var overlay = $('<div id="overlay"></div>');
							overlay.appendTo(document.body);
							//$("#overlayimage").css("display", "block");
						},
						*/
					success: function(data){				
						location = "<?php echo $root ?>www/"+data;				
					}
				});
		
				return false;
			});			
			//PDF EXPORT
			$("#pdf_export").click(function() {
				//alert('<?php  echo $root;?>');
				$.ajax({
					type: "POST",
					url: "<?php echo $root ?>pdf_export.php",
					data: {url:'<?php  echo $url;?>'},
					
					success: function(data){
						location = "<?php echo $root ?>pdf_download.php?file_name="+data;												
					}
				});
		
				return false;
			});
			
			//SCREENSHOT SECTION			
			$("#screenshot_desktop").click(function() {
				var desktop = $("#screenshot_desktop").attr("src");	
				$.fancybox.open(desktop);
			});
			
			$("#screenshot_tablet").click(function() {
				var tablet = $("#screenshot_tablet").attr("src");		
				$.fancybox.open(tablet);
			});
			
			$("#screenshot_mobile").click(function() {
				var mobile = $("#screenshot_mobile").attr("src");		
				$.fancybox.open(mobile);
			});
		
			// SEO Descriptions
			$("#btn_high_priority_SEOdescriptions").click(function() {
				$("#high_priority_SEOdescriptions").attr("style","display:block");		
				$("#medium_priority_SEOdescriptions").attr("style","display:none");		
				$("#low_priority_SEOdescriptions").attr("style","display:none");		
			});
			
			$("#btn_medium_priority_SEOdescriptions").click(function() {
				$("#high_priority_SEOdescriptions").attr("style","display:none");		
				$("#medium_priority_SEOdescriptions").attr("style","display:block");		
				$("#low_priority_SEOdescriptions").attr("style","display:none");		
			});
			
			$("#btn_low_priority_SEOdescriptions").click(function() {
				$("#high_priority_SEOdescriptions").attr("style","display:none");		
				$("#medium_priority_SEOdescriptions").attr("style","display:none");		
				$("#low_priority_SEOdescriptions").attr("style","display:block");		
			});
			
			// Speed Descriptions
			$("#btn_high_priority_descriptions").click(function() {
				$("#high_priority_descriptions").attr("style","display:block");		
				$("#medium_priority_descriptions").attr("style","display:none");		
				$("#low_priority_descriptions").attr("style","display:none");		
			});
			
			$("#btn_medium_priority_descriptions").click(function() {
				$("#high_priority_descriptions").attr("style","display:none");		
				$("#medium_priority_descriptions").attr("style","display:block");		
				$("#low_priority_descriptions").attr("style","display:none");		
			});
			
			$("#btn_low_priority_descriptions").click(function() {
				$("#high_priority_descriptions").attr("style","display:none");		
				$("#medium_priority_descriptions").attr("style","display:none");		
				$("#low_priority_descriptions").attr("style","display:block");		
			});
		
		}); 
    </script>