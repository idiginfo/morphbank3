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

include_once( 'menu.inc.php');
// include the class for images
include_once('mbImage.class.php');
//require "counter/phpcounter.php";
//require ( 'counter/phpcounter.php' );


/**
 * Adds top sections for html page
 * 
 * @global styleDirectory, jsDirectory, domainName, imgDirectory, metaContent, objInfo, siteTitle
 * @param string $title declare title of page
 * @param string $javaScript javascript source link
 * @param array $includeJavaScript javascript file array to build source links
*/
function initHtml ($title = "", $javaScript="", $includeJavaScript=array()) {
	global $config, $metaContent, $objInfo;
	
	$metaContent  = file_get_contents('content/metacontent.txt', true);
	$metaKeywords = file_get_contents('content/metakeywords.txt', true);
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
		<head>';
	if ( $objInfo->getServerInfo() ) {
		$serverArray = $objInfo->getServerInfo();
		$mirrorTitle = $serverArray[3];
	}


	echo'
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta name="description" content="'.$metaContent.' DBI-0446224" />
			<meta name="keywords" content="'.$metaKeywords.'" />
			<title>'.$title.' - '.$config->appName.'</title>
			<!-- Mimic Internet Explorer 7 -->
			<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
			<!-- compliance patch for microsoft browsers -->
			<!--[if IE]>
			<script src="'.$config->domain.'ie7/ie7-standard-p.js" type="text/javascript"></script>
			<![endif]--> 
			<link rel="stylesheet" title="Default" href="/style/morphbank2.css" type="text/css" media="screen"/>
			<link rel="stylesheet" href="/style/smoothness/jquery-ui-1.8.custom.css" type="text/css" media="screen"/>
			<link rel="shortcut icon" href="/style/webImages/mLogo16.ico" />
			<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryData.js"></script>
			<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryEffects.js"></script>
			<script language="javascript" type="text/javascript" src="'.$config->domain.'js/HttpClient.js"></script>
			<script type="text/javascript">
				window.name="main";
				var jsDomainName = "'.$config->domain.'";';
	if ($objInfo->getUserId() != NULL) {
		echo 'var jsUserId = '.$objInfo->getUserId().';';
	}
	if ($objInfo->getUserGroupId() != NULL) {
		echo 'var jsGroupId = '.$objInfo->getUserGroupId().';';
	}
	echo '
				function getWindowHeight() {
					var windowHeight = 0;
					if (typeof(window.innerHeight) == \'number\') {
						windowHeight = window.innerHeight;
					}
					else {
						if (document.documentElement && document.documentElement.clientHeight) {
							windowHeight = document.documentElement.clientHeight;
						}
						else {
							if (document.body && document.body.clientHeight) {
								windowHeight = document.body.clientHeight;
							}
						}
					}
					return windowHeight;
				}
				function setFooter() {
					if (document.getElementById) {
						var windowHeight = getWindowHeight();
						if (windowHeight > 0) {
							var contentHeight = document.getElementById(\'main\').offsetHeight;
							var footerElement = document.getElementById(\'footer\');
							var footerHeight  = footerElement.offsetHeight;
							if (windowHeight - (contentHeight + footerHeight) >= 0) {
								footerElement.style.position = \'relative\';
								footerElement.style.top = ((windowHeight - (contentHeight + footerHeight)) -0) + \'px\';
							}
							else {
								footerElement.style.position = \'static\';
							}
						}
						footerElement.style.visibility = \'visible\';
					}
				}
				window.onload = function() {
					setFooter();
				}
				window.onresize = function() {
					setFooter();
				}
				
				
			</script>
			
			<script type="text/javascript" src="'.$config->domain.'js/head.js"></script>' . "\n";

	if (!empty($includeJavaScript)) {
		foreach($includeJavaScript as $file){
			echo '<script type="text/javascript" src="/js/'.$file.'"></script>' . "\n";
		}
	}
	if ($javaScript !="") {
		echo $javaScript;
	}
	echo'</head>';
}

/*
 * Creates horizontal menu
 * 
 * @global includeDirectory, domainName, imgDirectory, imgMirrorDirectory, objInfo
 * @param string $user default=guest, type of user
 * @param string $headerTitle default='', title for the page
 * @param boolean $hasform0 default=true, determines whether to create search bar
 */
function makeHorMenu( $user = "guest", $headerTitle = '', $hasform0 = true) {
	global $config;
	global $objInfo;
	
	echo '<div id="ajaxHiddenDiv">&nbsp;</div>';
	echo '<div id="HttpClientStatus" style="display:none;">&nbsp;</div>';
	echo '<div id="main">';
	echo '
		<div class="mainHeader">
			<div class="mainHeaderLogo"><a href="'.$config->domain.'index.php"><img border="0" src="/style/webImages/mbLogoHeader.png" alt="logo" /></a>&nbsp;
			</div>
			<div class="mainHeaderTitle">'.$headerTitle.'</div>
			<div id="mainHeaderLoginId" class="mainHeaderLogin">';
	// located in menu.inc.php  Outputs the login info if you are loged in
	outputLoginInfo();
	echo '</div>';
	$bool = (strpos($_SERVER['PHP_SELF'], "About/Manual")===FALSE) ? FALSE : TRUE;
	if (!$bool){
		makeMenu();
	}
	if ( $objInfo->getServerLogo() ) {
		$serverLogo = $objInfo->getServerLogo();
		$serverLogo = '/images/mirrorLogos/'.$serverLogo;
		if (file_exists("$serverLogo")) {
			echo '<div id="mirrorLogo"><img src="'.$serverLogo.'" height="50" alt="Mirror Logo" title="'.$config->appName.'" /></div>';
		}
	}
	echo '</div><div class="mainRibbon"></div>	'; //mainHeader
	makeMenuOptions();
	if (!$hasform0){
		makeSearchBar();
	}
	// for selecting a new group with one click
	echo '<div class="mainNavSubMenu subMenuBGColor" id="groupSelectMenu" onmouseover="stopSubTime();" onmouseout="startSubTime();">';
	populateGroupMenu();
	echo '
		  </div>
		<div class="mainNavSubMenu subMenuBGColor" id="submitSelectMenu" onmouseover="stopSubTime();" onmouseout="startSubTime();">
		  	<a class="mainNavSubSubLink" href="'.$config->domain.'Submit/Location/">Locality</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'Submit/Specimen/">Specimen</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'Submit/View/">View</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'Submit/Image/">Image</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'Admin/Publication/addPublication.php">Publication</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'Admin/TaxonSearch/index.php?searchonly=1&amp;fromMenu=true">Taxon Name</a>
		</div>
		<div class="mainNavSubMenu subMenuBGColor" id="editSelectMenu" onmouseover="stopSubTime();" onmouseout="startSubTime();">
		  	<a class="mainNavSubLink" href="'.$config->domain.'Edit/Locality/">Locality</a>
			<a class="mainNavSubLink" href="'.$config->domain.'Edit/Specimen/">Specimen</a>
			<a class="mainNavSubLink" href="'.$config->domain.'Edit/View/">View</a>
			<a class="mainNavSubLink" href="'.$config->domain.'Edit/Image/">Image</a>
			<a class="mainNavSubLink" href="'.$config->domain.'Admin/Publication/editPublication.php">Publication</a>
			<a class="mainNavSubLink" href="'.$config->domain.'Admin/TaxonSearch/editTSN.php">Taxon Name</a>
		</div>
		<div class="mainNavSubMenu subMenuBGColor" id="managerSelectMenu" onmouseover="stopSubTime();" onmouseout="startSubTime();">
		  	<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=allTab">All</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=imageTab">Images</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=specimenTab">Specimens</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=viewTab">Views</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=localityTab">Localities</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=taxaTab">Taxa</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=collectionTab">Collections</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=annotationTab">Annotations</a>
			<a class="mainNavSubSubLink" href="'.$config->domain.'MyManager/?tab=pubTab">Publications</a>
		</div>
	  ';
}

/**
 * Adds body tag, ajax divs, and intro header
 * 
 * @param boolean $isIntroPage default true
 * @param string $headerTitle
 * @param boolean $hasform0 default true
 * @see makeHorMenu
*/
function echoHead( $isIntroPage = true, $headerTitle = ' ', $hasform0 = true) {
	echo '
	<body>';
	if ($isIntroPage){
		echo '<div class="introHeader"></div>';
	} else { //it has to change for different users
		makeHorMenu('guest', $headerTitle, $hasform0);
	}
}

/**
 * Closes html page footer
 * 
 * @global link, includeDirectory
 * @param boolean $introPage default false
 *  
*/
function finishHtml($introPage = FALSE) {
	global $link;
	if ($link) {
		mysqli_close($link);
	}
	if (!$_GET['pop'] && !$introPage) {
		echo '<div style="clear:both;">&nbsp;</div>';
		echo '</div>'; // Closing div tag for "main"
		include_once('footer.inc.php');
	}
	echo '
		</body>
	</html>';
}

/*
 * Creates search bar
 *
 */
function makeSearchBar() {
	global $config;
	echo '
	<div style="float:right">
		<form action="'.$config->domain.'MyManager/" >
			<input type="text" name="keywords"/><input type="submit" value="Search images by keywords"/>
		</form>
	</div>
	';
}
?>
