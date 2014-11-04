<?php
	$root = "http://localhost/woorankV2/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Website Analysis</title>
		<meta name="description" content="Website Analysis &amp; Internet Marketing tips for your website. Optimize your website and find an Internet marketing expert." />

        <link href="reset.css" rel="stylesheet" type="text/css"  />
        <link href="common.css" rel="stylesheet" type="text/css"  />
        <link href="home.css" rel="stylesheet" type="text/css"  />
        <link href="w1.css" rel="stylesheet" type="text/css"  />
	</head>
	<body>
		<div id="home" class="en">
			<div id="top">
				<div id="top-nav">
					<ul id="service" class="nav">
						<li class="current"  ></li>
					</ul>
				</div>
           	</div>				
			
			<div id="content">
				<h1>Website Analysis</h1>

				<div id="main_form">
					<form id="analyze" name="analyze" action="report.php" method="get">
					<div>
                        <input class="text" type="text" id="domain" name="domain" value=""/>                        
                        <span class="input_bg">client-domain.com</span>
                        <input id="submit_btn" type="submit" class="submit" value="SEO Report" />		
					</div>
					</form>
				</div>
				<h2 style="font-size: 18px;">Recently Searched Websites</h2><br />
					<ul>
						<?php
						
							$files = glob("sites/" . "*.html");
							$files = array_combine($files, array_map("filemtime", $files));
							arsort($files);
							foreach ($files as $filename => $timevalue) {
								echo "<li style='font-size: 14px; display:inline-block; margin-left:10px; line-height:24px;'><a href='".$root.$filename."' target='_blank'>".str_replace(array(".html","sites/"),"",$filename)."</a></li>";
							}
                        ?>
                    </ul>			
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
    		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">

			$(document).ready(function(){
				$("#analyze").submit(function() {
					$.ajax({
						type: "POST",
						url: "ajax.php",
						data: $("#analyze").serialize(),

						beforeSend: function(XMLHttpRequest){
							var overlay = $('<div id="overlay"></div>');
							overlay.appendTo(document.body);
							//$("#overlayimage").css("display", "block");
						},

						success: function(data){				
							location = "<?php echo $root ?>www/"+data;				
						}
					});
			
					return false;
				});
				
			}); 
		</script>

</html>



