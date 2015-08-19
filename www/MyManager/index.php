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

include_once('head.inc.php');
include_once('postItFunctions.inc.php');
require_once('myManager.class.php');
// added for handling /?keywords urls
include_once('updateSessionMain.php');
//checkIfLogged(); // No more. let everyone use this tool! GR
//groups();

// look for 'tab' parameter
if (isset($_POST['tab']))
	$tab = $_POST['tab'];
elseif (isset($_GET['tab']))
	$tab = $_GET['tab'];
elseif ($objInfo->getCurrentTab() != "" || $objInfo->getCurrentTab() != NULL)
	$tab = $objInfo->getCurrentTab();
else
	$tab = "imageTab";
	
// look for 'keywords' parameter -- changed || to && below
if ( ($objInfo->getKeywords() != "" && $objInfo->getKeywords() != NULL) ){
	$keywords =  '&keywords='.$objInfo->getKeywords();
} elseif (isset($_GET['keywords'])) {
	$keywords = '&keywords='.$_GET['keywords'];
error_log("mymanager index keyword GET params: ".$keywords);
} elseif (isset($_POST['keywords'])) {
	$keywords = '&keywords='.$_POST['keywords'];
error_log("mymanager index keyword POST params: ".$keywords);
}	else{
	$keywords = '';
}

//TODO revise so that user settings include whether to search within group
//if (isLoggedIn()) {
	//$_GET['limit_current']='true';
	//$limit='&limit_current=true';
//}
	
//$keywords = (($objInfo->getKeywords() != "" || $objInfo->getKeywords() != NULL) 
//	&& !isset($_GET['tab'])) ? '&keywords='.$objInfo->getKeywords() : "";
	
$goTo = ($objInfo->getCurrentPage() != "" || $objInfo->getCurrentPage() != NULL) ? '&goTo='.$objInfo->getCurrentPage() : "";	 
$numPerPage = ($objInfo->getNumPerPage() != "" || $objInfo->getNumPerPage() != NULL) ? '&numPerPage='.$objInfo->getNumPerPage() : '&numPerPage=20';


	
$myManagerFormValues = $config->domain . 'MyManager/content.php?id='.$tab.$keywords.$goTo.$numPerPage.$limit;

// The beginnig of HTML
$title =  'My Manager ';
$javascript = '
<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryTabbedPanels.js"></script>
<script language="javascript" type="text/javascript" src="'.$config->domain.'js/swapRowColor.js"></script>
<script language="javascript" type="text/javascript" src="'.$config->domain.'js/managerCalendar.js"></script>
<script language="javascript" type="text/javascript" src="'.$config->domain.'js/myManager.js"></script>

<style type="text/css">@import url(/style/calandar/aqua/theme.css);</style>
<style type="text/css">@import url(/style/SpryTabbedPanels.css);</style>
<script type="text/javascript" src="'.$config->domain.'js/calendar/calendar.js"></script>
<script type="text/javascript" src="'.$config->domain.'js/calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="'.$config->domain.'js/calendar/calendar-setup.js"></script>

<script language="javascript" type="text/javascript">
	// load the content and filters dynamically from their ajax scripts 
	window.onload = function () {
		// This way did not work well, due to the page would only display after the ajax was done loading.
		// Spry.Utils.updateContent(contentId, contentUrl+"?id='.$tab.'");
		
		if ("'.$tab.'" != "imageTab") {
			document.getElementById("'.$tab.'").className = "TabbedPanelsTabSelected";
			document.getElementById("imageTab").className = "TabbedPanelsTab";	
			document.resultForm.currentTab.value="'.$tab.'";	
			previous = document.getElementById("'.$tab.'"); // this is for the tab
			previousPage = "'.$config->domain.'MyManager/content.php?id='.$tab.'";
		}
		
		updateImageFilters("'.$tab.'");
		
		//var onLoadPage = contentUrl+"?id='.$tab.'";
		var onLoadPage = "'.$myManagerFormValues.'";
		setTimeout(function(){changePage(onLoadPage)}, 10 );
		
		setFooter();	
	}
	
</script>';

initHtml( $title, $javascript, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);
//echo '<div id="HttpClientStatus" style="position: absolute; top:0px; left:0px; z-index:-1;">&nbsp;</div>';
echo '<div id="updateLoaderId" style="display:none; position:absolute; z-index:12; top: 300px; left:500px; padding:10px; background-color:#fff; border: 1px solid #000;">
<center><h2>Processing...</h2><br /><br />
<img src="/style/webImages/updateLoader.gif" alt="Updating" />
</center></div>';

echo '<div id="ajaxSuccess" style="display:none; position:absolute; z-index:12; top: 300px; left:400px;  border: 1px solid #000;">
	<img src="/style/webImages/operationSuccessful.jpg" alt="Operation Successful" />
</div>';

echo '<div id="ajaxFailed" style="display:none; position:absolute; z-index:12; top: 300px; left:400px;  border: 1px solid #000;">
	<img src="/style/webImages/operationFailed.jpg" alt="Operation Failed" />
</div>';

echo '<div id="copyCollection">
	<h3>Copy Collection to...</h3><hr />
	<a id="copyCharacterLinkId" href="#">Character Collection</a>
	<a id="copyCollectionLinkId" href="#">Normal Collection</a><br />
	<a href="javascript: closeCopyCollection();">Cancel</a>
</div>';

setupPostIt();
// Output the content of the main frame
$myManager = new MyManager($title);

$myManager->display();

// Finish with end of HTML
finishHtml();

?>
