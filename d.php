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
		include("WebInfoClass.php");
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