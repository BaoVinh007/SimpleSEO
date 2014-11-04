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
		
		$alexa_rank =  $ranking_websiteThread->alexa_rank;
		$alexa_class = $ranking_websiteThread->alexa_class;
		$page_rank = $ranking_websiteThread->page_rank;
		$pr_class = $ranking_websiteThread->pr_class;		
	
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
	
		}); 
    </script>