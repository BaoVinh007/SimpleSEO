			<?php
				include("ComplianceClass.php");
				
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
				
				function get_web_page( $url )
				{		
					$proxies_array	=	array(
												'173.208.91.117:3128',
												'173.208.39.106:3128',
												'173.234.57.8:3128',
												'173.234.250.161:3128',
												'173.234.181.127:3128',
												'173.208.91.201:3128',
												'173.234.57.249:3128'
											);
											
					$random_key 		= array_rand($proxies_array);
					$random_proxy 		= $proxies_array[$random_key];					
											
					$useragents_array	=	array(
												'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.11) Gecko Kazehakase/0.5.4 Debian/0.5.4-2.1ubuntu3',
												'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080311 (Debian-1.8.1.13+nobinonly-0ubuntu1) Kazehakase/0.5.2',
												'Mozilla/5.0 (X11; Linux x86_64; U;) Gecko/20060207 Kazehakase/0.3.5 Debian/0.3.5-1',
												'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)',
												'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.1.4) Gecko/20070511 K-Meleon/1.1',
												'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 K-MeleonCCFME 0.09',
												'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.8.0.7) Gecko/20060917 K-Meleon/1.02',
												'Mozilla/5.0 (Windows; U; Win 9x 4.90; en-US; rv:1.7.5) Gecko/20041220 K-Meleon/0.9',
												'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2',
												'Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8.2',
												'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.5) Gecko/20031016 K-Meleon/0.8',
												'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2b) Gecko/20021016 K-Meleon 0.7',
												'Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:0.9.5) Gecko/20011011',
												'Mozilla/5.0(Windows;N;Win98;m18)Gecko/20010124',
												'Mozilla/5.0 (compatible; Konqueror/4.0; Linux) KHTML/4.0.5 (like Gecko)',
												'Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)',
												'Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)',
												'Mozilla/5.0 (compatible; Konqueror/3.5; GNU/kFreeBSD) KHTML/3.5.9 (like Gecko) (Debian)',
												'Mozilla/5.0 (compatible; Konqueror/3.5; Darwin) KHTML/3.5.6 (like Gecko)'
											);
											
					$random_key 		= array_rand($useragents_array);
					$random_useragent 	= $useragents_array[$random_key];							
				
																	
					$options = array(
							CURLOPT_RETURNTRANSFER 	=> true,     			// return web page
							CURLOPT_HEADER         	=> false,    			// don't return headers
							//CURLOPT_PROXY 			=> $random_proxy,     		// the HTTP proxy to tunnel request through
							//CURLOPT_HTTPPROXYTUNNEL => 1,    				// tunnel through a given HTTP proxy			
							CURLOPT_FOLLOWLOCATION 	=> true,     			// follow redirects
							CURLOPT_ENCODING       	=> "",       			// handle compressed
							CURLOPT_USERAGENT      	=> $random_useragent, 	// who am i
							CURLOPT_AUTOREFERER    	=> true,     			// set referer on redirect
							CURLOPT_CONNECTTIMEOUT 	=> 120,      			// timeout on connect
							CURLOPT_TIMEOUT        	=> 20,      			// timeout on response
							CURLOPT_MAXREDIRS      	=> 10,       			// stop after 10 redirects
						);
					
					$ch      = curl_init( $url );
					curl_setopt_array( $ch, $options );
					$content = curl_exec( $ch );
					curl_close( $ch );
						
					return $content;
				}				
				
			?>
            <?php
				$w3c_content = get_web_page("http://validator.w3.org/check?uri=$domain");				
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
		
		include("PopularityClass.php");					
		$popularityClass = new PopularityClass();							

		$crawl = $popularityClass->getCrawlContent($url);		
		$crawl_class = $popularityClass->getCrawlContentClass($crawl);
		
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
					
					$backlinks =  $popularityClass->GoogleBL($domain);							
					if($backlinks == "") 
						$backlinks = "0";
					$backlinks_class = $popularityClass->GoogleBLClass($backlinks);

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
					
					$edu = $popularityClass->GoogleEDU($domain);	
					if($edu == "") 
						$edu = "0";		
					$edu_class = $popularityClass->GoogleEDUClass($edu);					
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
									
					$gov = $popularityClass->GoogleGOV($domain);	
					if($gov == "") 
						$gov = "0";						
					$gov_class = $popularityClass->GoogleGOVClass($gov);
					
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
		include("DirectoriesClass.php");	
		$directoriesClass = new DirectoriesClass();
		
		$dmoz = $directoriesClass->get_dmoz($domain);
		$dmoz_class = $directoriesClass->get_dmozClass($dmoz);
		
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
			
				$yahoodir = $directoriesClass->get_yahoodir($domain);
				$yahoodir_class = $directoriesClass->get_yahoodirClass($yahoodir);
					
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
		
		include("SocialMediaClass.php");		
		
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