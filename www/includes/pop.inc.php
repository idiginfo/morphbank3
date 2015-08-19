<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

/**
 * Adds top sections for html page
 * 
 * @global styleDirectory, jsDirectory, domainName, objInfo
 * @param string $title declare title of page
 * @param string $javaScript javascript source link
 * @param array $includeJavaScript javascript file array to build source links
*/
function initHtml ( $title = "Morphbank :: The only choice for image databasing", $javaScript="", $includeJavaScript = array())
{
	global $config, $objInfo;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
			<title>'.$title.'</title>
			<!-- compliance patch for microsoft browsers -->
			<!-- [if IE]><![endif]--> 
			<script src="'.$config->domain.'ie7/ie7-standard-p.js" type="text/javascript"></script>
			
			<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryData.js"></script>
			<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryEffects.js"></script>
			
			<link rel="stylesheet" title="Default" href="/style/morphbank2.css" type="text/css" media="screen"></link>
		';
	echo '<script type="text/javascript">
				var jsDomainName = "'.$config->domain.'";';
				
				if ($objInfo->getUserId() != NULL) {
					echo 'var jsUserId = '.$objInfo->getUserId().';';				
				}
				
				if ($objInfo->getUserGroupId() != NULL) {
					echo 'var jsGroupId = '.$objInfo->getUserGroupId().';';				
				}
				
				echo '
				var iframeTop, iframeLeft, iframeWidth, iframHeight;
				
				var IE = document.all?true:false;
				
				var detect = navigator.userAgent.toLowerCase();
				var browser, thestring, place;
				
				if (checkIt(\'opera\')) browser = "Opera"		
				
				function checkIt(string) {
					place = detect.indexOf(string) + 1;
					thestring = string;
					return place;
				}

				function loadInOpener(url) {	
					if (window.opener) {
						opener.location.href= url;
						opener.focus();						
					}
					else				
						window.open(url, "main").focus();					
				}
				
				function openPopup ( url, w, h ) {	
					var width = (w) ? "width="+w : "width="+870; 
					var height = (h) ? "height="+h : "height="+650;
					var paramString = width+\',\'+height+\',location=no,resizable=yes,scrollbars=yes,left=0,top=0\';
					
					/*alert( url+\' \'+paramString);*/
					
					newwindow=window.open( url,\'\',paramString);
					newwindow.focus();  					 
				}	
				
				function openExtLink ( url ) {	
	
					var paramString = "location=yes,titlebar=yes,status=yes,directories=yes,toobar=yes,menubar=yes,resizable=yes,scrollbars=yes,left=50,top=0";
					
					newwindow=window.open( url,\'\',paramString);
					newwindow.focus();  					 
				}		
	
				
				function submitParentWindowForm(typeObject, objectId, objectTitle, referer) {
					opener.update(typeObject, objectId, objectTitle); 
					
					if (referer == "search") {
						opener.document.resultControlForm.activeSubmit.value=\'2\';
					} else if (referer == "browse") {
						opener.document.resultControlForm.activeSubmit.value=\'2\';
						opener.document.resultControlForm.submit(); 
					} else {
						window.close();						
					}
						
					window.close();			
				}
				
		  </script>
	<script language="javascript" type="text/javascript" src="'.$config->domain.'js/HttpClient.js"></script>
	<script type="text/javascript" src="'.$config->domain.'js/head.js"></script>' . "\n";
	
	if (!empty($includeJavaScript)) {
		foreach($includeJavaScript as $file){
			echo '<script type="text/javascript" src="/js/'.$file.'"></script>' . "\n";
		}
	}
	
	if ($javaScript !="")
	{
		echo $javaScript;
	}
	
	echo'</head>';
}

/**
 * Echos body tag, ajax divs, and intro header
 * 
 * @param boolean $isIntroPage default true, echo intro header if true
 * @param string $headerTitle default empty, passed to menu function 
*/
function echoHead( $isIntroPage = true, $headerTitle = ' ') 
{
	echo '<body>';
		echo '<div id="ajaxHiddenDiv">&nbsp;</div>';
	echo '<div id="HttpClientStatus" style="display:none;">&nbsp;</div>';

	if ($isIntroPage){
		echo '<div class="introHeader"></div>
		';
	} else { //it has to change for different users
		//makeHorMenu('guest', $headerTitle);
	}
}

/**
 * Echos background div class mainBackGround
*/
function echoBackGround()
{
	
	//echo '<div class="mainBackGround"><img src="/style/webImages/mainBackGround100.jpg" border="0" align="top"></div>';
	echo '<div class="mainBackGround">';
}

/**
 * Echos body and html tag in footer for popup
*/
function finishHtml()
{
	echo '
		
		</body>
	</html>';
}
?>
