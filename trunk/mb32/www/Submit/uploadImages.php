<?php

/*
 * Webpage allowing users to drop image files in their personal folder
 * using Javascript.
 */

include_once('head.inc.php');

// The beginnig of HTML
$title = 'Upload images';
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
$objInfo = resetObjInfo();
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$authorized = checkAuthorization(null, $userId, $groupId, 'add');

echo '<div class="mainGenericContainer" style="width:700px">';
//TODO add the uploader here
if($authorized == true) {
  echo "you're allowed.<br/>";
  echo "you are $objInfo->name after all...";
}
else {
  echo "you're not allowed. <br/>"
  . "who are you anyway?";
}
echo '</div>';

// Finish with end of HTML
finishHtml();

?>
