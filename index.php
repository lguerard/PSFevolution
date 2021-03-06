<?php
	$webShare = exec('/sbin/getcfg SHARE_DEF defWeb -d Qweb -f /etc/config/def_share.info');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Web Server Setup Guide</title>	
	<link rel="shortcut icon" href="images/favicon.ico" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="/v3_menu/css/template_css.css" rel="stylesheet" type="text/css" />
	<link href="/v3_menu/css/s-blue.css" rel="stylesheet" type="text/css" />
	<link href="/v3_menu/css/p-blue.css" rel="stylesheet" type="text/css" />
	<link href="/v3_menu/css/body-beige.css" rel="stylesheet" type="text/css" />
	
	<style type="text/css">
		div.wrapper { margin: 0 auto; width: 870px;}
		td.sidenav { width:200px;}
		body {font-family: Verdana, Tohoma, Arial, Helvetica, sans-serif;padding:0;margin:0;}
	</style>

	<link rel="stylesheet" href="/v3_menu/css/jquery.tabs.css" type="text/css" media="print, projection, screen">
	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="/v3_menu/css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
	<![endif]-->
	  
	<script src="/v3_menu/js/jquery-1.3.2.min.js" type="text/javascript"></script>  
	<script src="/v3_menu/js/jquery-easing.1.2.pack.js" type="text/javascript"></script>
	<script src="/v3_menu/js/jquery-easing-compatibility.1.2.pack.js" type="text/javascript"></script>
	<script src="/v3_menu/js/coda-slider.1.1.1.pack.js" type="text/javascript"></script>
	<script src="/v3_menu/js/main.js" type="text/javascript"></script>

	<script type="text/javascript">
		jQuery(window).bind("load", function() {
		jQuery("div#slider1").codaSlider()
		jQuery("div#slider2").codaSlider()
		});
	</script>
	</head>
	<body id="page_bg" class="f-default" >
		<div id="header">
			<div class="wrapper">
				<div class="shadow-l">
					<div class="shadow-r">
						<a href="/" class="nounder">
						</a>				
						<div id="home-button">
							<script Language="JavaScript">              	
            	document.write('<a href="javascript:MM_goToURL_Port(\'parent\', \'/\', \'<?=exec("/sbin/getcfg SYSTEM \"Web Access Port\" -f /etc/config/uLinux.conf");?>\')"><img src="/v3_menu/images2/home.gif" onMouseDown="MM_swapImage(\'hm\',\'\',\'/v3_menu/images2/home.gif\',1)"  onMouseOver="MM_swapImage(\'hm\',\'\',\'/v3_menu/images2/home.gif\',1)" id="hm" border="0" id="hm" onMouseOut="MM_swapImgRestore()"></a>');
            	</script>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="shadow-l">
			<div class="shadow-r">
			</div>
		</div>	
		<div id="divider">
			<div class="wrapper">
				<div class="shadow-l">
					<div class="shadow-r">
					</div>
				</div>
			</div>
		</div>	
		<div id="mainbody">
			<div class="wrapper">
				<div class="shadow-l">
					<div class="shadow-r">
						<table class="mainbody" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td class="mainbody empty">
									<div class="padding">
										<div class="topadvert">											
											<div style="padding: 0px 0px 25px 0px;">
												<font style="color:#999; font: Tohoma; font-size: 32px;">Guidelines to set up your own websites</font><br /><br />
											</div>
											<p style="color: #999; text-align: justify;">
												You can set up your own website by just uploading your web pages to the share folder <b><?=$webShare?></b>. The web server is optimized for hosting PHP-MySQL-based sites and you can simply upload your web pages to the network share <b><?=$webShare?></b> by the following 2 methods:
											</p>	
											<br />
											<ul class="bullet-f">
												<li><p><a href="#Windows_Mapped_Shares">Windows Mapped Shares</a></p></li>
												<li><p><a href="#FTP_Upload">FTP Upload</a></p></li>
											</ul>	
											<div class="moduletable">
												<center>		
													<div id="container">
														<div id="top-heading">
															<a name="Windows_Mapped_Shares"></a>
													  	<h2>Windows Mapped Shares</h2>
														  <blockquote class="b">
														  	<p>By mapping the network shares (<?=$webShare?>) on the NAS server to a drive folder in Windows and you may refer to the step-by-step guide below.</p>
														  </blockquote>
														</div>
														<div class="slider-wrap">
															<div id="slider1" class="csw">
																<div class="panelContainer">																		
																	<div class="panel" title="Step 1">
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 1 ></b></font> Right click on My Computer and select 'Map Network Drive...'
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/mapped_drive-001.jpg" border="0" hspace="0" vspace="0" alt="" />
																			</p>
																		</div>
																	</div>
																	<div class="panel" title="Step 2">	
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 2 ></b></font> Select a Drive letter that you wish to map <?=$webShare?> to.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/mapped_drive-002.jpg" border="0" hspace="0" vspace="0" alt="" />
																			</p>
																		</div>
																	</div>		
																	<div class="panel" title="Step 3">
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 3 ></b></font> Now type in the username and Password to grant access on the <?=$webShare?> share.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/mapped_drive-003.jpg" border="0" hspace="0" vspace="0" alt="" />
																			</p>
																		</div>
																	</div>
																	<div class="panel" title="Step 4">
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 4 ></b></font> Drive Y: is now mapped to <?=$webShare?> successfully and can be accessed just like a<br> drive Folder on your Windows PC.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/mapped_drive-004.jpg" border="0" hspace="0" vspace="0" alt="" />
																			</p>
																		</div>
																	</div>
																	<div class="panel" title="Step 5">	
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 5 ></b></font> You may now copy the website contents into this Folder and they will instantly <br />be available to access from the internet or within your home network.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/mapped_drive-005.jpg" border="0" hspace="0" vspace="0" alt="" />
																			</p>
																		</div>
																	</div>																				
																</div>
															</div>
														</div>														
														<div id="top-heading">
															<a name="FTP_Upload"></a>
													  	<h2>FTP Upload</h2>
														  <blockquote class="b">
														  	<p>Connect to the <?=$webShare?> share via any FTP client of your choice and type in the username<br> and password when prompted.
														  	</p>
														  </blockquote>
														</div>														
														<div class="slider-wrap">
															<div id="slider2" class="csw">
																<div class="panelContainer">																		
																	<div class="panel" title="Step 1">
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 1 ></b></font> Connect to the <?=$webShare?> share via any FTP client of your choice and type in<br /> the username and password when prompted.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/ftp-001.jpg" border="0" hspace="0"vspace="0" alt="" />
																			</p>
																		</div>
																	</div>
																	<div class="panel" title="Step 2">
																		<div class="wrapper">																			
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 2 ></b></font> Once logged in simply drag and drop the website contents you wish to upload<br /> to the <?=$webShare?> FTP directory.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/ftp-002.jpg" border="0" hspace="0"vspace="0" alt="" />
																			</p>
																		</div>
																	</div>		
																	<div class="panel" title="Step 3">
																		<div class="wrapper">
																			<div style="color: #444; text-align: left;">
																				<font size="3"><b>Step 3 ></b></font> That's pretty much all you have to do and your web site can now be accessed<br> directly on the Internel.
																			</div>
																			<p>
																				<img src="/v3_menu/images2/qweb/ftp-003.jpg" border="0" hspace="0"vspace="0" alt="" />
																			</p>
																		</div>
																	</div>																
																</div>
															</div>
														</div>									      
													</div>														
												</center>			
											</div>
										</div>	
									<span class="article_seperator">&nbsp;</span>
								</div>
							</td>
						</tr>
					</table>													
				</div>
			</div>
		</div>
		</div>		

		<div id="bottom">
			<div class="wrapper">
				<div class="shadow-l">
					<div class="shadow-r">
						<div class="shadow-l2">
							<div class="shadow-r2">
								<table class="bottom" cellspacing="0" cellpadding="0">
									<tr valign="top">
										<td class="mainbottom empty">
											<div class="padding"></div>
											&nbsp;
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
		<div id="footer">
			<div class="wrapper">
				<div class="shadow-l">
					<div class="shadow-r">
						<table class="footer" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td class="mainfooter">
								</td>
							</tr>
						</table>			
					</div>
				</div>
			</div>
		</div>		
	</body>
</html>