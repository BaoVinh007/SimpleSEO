            	
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