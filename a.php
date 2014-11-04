<?php		
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

		include("RankingClass.php");									
		$ranking_website = new RankingClass();
		$traffic = $ranking_website->getTraffic($domain);

		include("ContentClass.php");
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
			include("HomeAnalysisClass.php");
			$homeAnalysis = new HomeAnalysisClass();

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
						<h2>Visitors</h2>	
                        <div class="report_section" id="section_visitors">
                            <div class="report_section_inner">
								<div class="criterion">
                                    <div class="criterion_value">
                                        <h4>Traffic estimation</h4>                            
	                                        <div>About <?php echo $traffic ?> Unique visitors/month	</div>
											<div class="score_label"></div>
											<a href="#" class="advice_toggle advice_open">Show advice</a>
									</div>
									<div class="criterion_info_advice">
                                        <div class="criterion_advice">
                                            <div>
                                                <p>You can use several different tools to estimate web traffic: <a rel="nofollow" href="https://www.google.com/adplanner/#siteSearch?identifier=<?php echo $domain ?>" target="_blank">Google&trade; Ad Planner</a>, <a rel="nofollow" href="http://trends.google.com/websites?q=<?php echo $domain ?>" target="_blank">Google&trade; Trends</a>, and <a rel="nofollow" href="http://www.alexa.com/siteinfo/<?php echo $domain ?>" target="_blank">Alexa</a>.</p>
                                                <p>Nevertheless, your analytics will provide the accurate traffic data.</p>																				 											</div>
                                        </div>
									</div>
								</div>
                                <?php
									
									$alexa_rank =  $ranking_website->getAlexaRank($url);
									$alexa_class = $ranking_website->getAlexaRankCLass($alexa_rank);

								?>
                                
								<div class="<?php echo $alexa_class ?>">
									<div class="criterion_value">
										<h4>Alexa rank</h4>
										<div>
											<p><?php echo number_format($alexa_rank) ?></p>	
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
                                                <p>A low rank means that your website gets lots of visitors.</p><p>Your <a rel="nofollow" href="http://www.alexa.com/siteinfo/<?php echo $domain ?>" target="_blank">Alexa Rank</a> is a good estimate of worldwide traffic to your website, although it is <a rel="nofollow" href="http://blog.alexa.com/2009/08/alexa-101-anatomy-of-traffic-rank-graph.html" target="_blank">not 100% accurate</a>.</p><p>Reviewing the <a rel="nofollow" href="http://www.alexa.com/topsites/countries" target="_blank">most visited websites</a> by country can give you valuable insights.</p><p><a rel="nofollow" href="http://www.quantcast.com/<?php echo $domain ?>" target="_blank">Quantcast</a> provides similar services.</p>																			 											</div>
										</div>
									</div>
								</div>
                                
                                <?php
									$page_rank = $ranking_website->getPageRank($url);
									$pr_class = $ranking_website->getPageRankClass($page_rank);
									
								?>
                                
								<div class="<?php echo $pr_class ?>">
                                    <div class="criterion_value">
                                    <h4>Page rank</h4>
                                    <div><?php echo $page_rank ?></div>
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
																
								$total_images = $homeAnalysis->getNumberOfImages($content);															
								$total_alt = $homeAnalysis->getNumberOfImageTags($content);
								
								$missing_alt = $total_images - $total_alt;
									
								$images_class = $homeAnalysis->getImageClass($total_images);			
							
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
							
								$code_ratio = $homeAnalysis->getCodeRatio($content);		
								$code_class = $homeAnalysis->getCodeRatioClass($code_ratio);														

							?>                 
                            
                            <div class="<?php echo $code_class ?>">
                                <div class="criterion_value">
                                    <h4>Text/HTML ratio</h4>
                                    <div>
										<p class="ratio"><?php echo $code_ratio ?>% <span>(<a href="#" id="view_text_link">view text</a>)</span></p>	
	                                    <div id="view_text_content"><?php echo $homeAnalysis->webpage2txt($content) ?></div>							
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
							$frames = $homeAnalysis->getFrame($content);
							$frames_class = $homeAnalysis->getFrameClass($frames);	
									
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
							$flash = $homeAnalysis->getFlash($content);
							$flash_class = $homeAnalysis->getFlashClass($flash);							
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