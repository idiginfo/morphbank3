#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php

// Modified 1/24/2006 to include proper path names.

include_once('head.inc.php');
//checkIfLogged();
//groups();
// this is for web validation purpose.  web validator can't log in, so set the obj object manually
//$objInfo->userId=77685;
//$objInfo->userGroupId=3;
include_once('gettables.inc.php');
$link = Adminlogin();

include_once('mainMyCollection.php');
require_once('Phylogenetics/Classes/BaseObject.php');
require_once('Phylogenetics/Classes/Collection.php');
require_once('Phylogenetics/Classes/CollectionObject.php');
require_once('Phylogenetics/Classes/CharacterState.php');
require_once('Phylogenetics/Classes/PhyloCharacter.php');

global $objInfo;

if (isset($_GET['collectionId'])){
	$collectionId= $_GET['collectionId'];
} else if (isset($_GET['id'])){
	$collectionId= $_GET['id'];
} else {
	$collectionId = 0;
}

if ($objInfo->getUserId() != NULL) { // if logged in
	$loggedIn = TRUE;
	if ($collectionId==0) {
		$collectionArray = getUserCollectionArray($objInfo->getUserId(), $objInfo->getUserGroupId());
		if ($collectionArray){
			$collectionId = $collectionArray[0]['id'];
		}
	}
} else {
	$loggedIn = FALSE;
}

$isMyCollection = isMyCollection($collectionId, $objInfo->getUserId());

$js = '
<link href="'.$config->domain.'style/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" /> 

<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/core.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/events.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/css.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/cookies.js"></script>


<!-- CARPE DHTML slider JS file -->
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'myCollection/source/org/tool-man/slider.js" ></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'js/myManager.js" ></script>
<script language="JavaScript" type="text/javascript" src="'.$config->domain.'js/collections.js" ></script>
';


// The beginnig of HTML
$title = 'My Collection';
//TODO incorporate postIt.js
$javaScripts = array('HttpClient.js','spry/SpryCollapsiblePanel.js');
initHtml( $title, $js, $javaScripts);


// Add the standard head section to all the HTML output.
echoHead( false, $title);

//echo '<div id="HttpClientStatus" style="position: absolute; top:0px; left:0px; z-index:-1;">&nbsp;</div>';

echo '<div id="updateLoaderId" style="display:none; position:absolute; z-index:12; top: 300px; left:500px; padding:10px; background-color:#fff; border: 1px solid #000;">
<center>
<h2>Processing...</h2>
<br />
<br />
<img src="/style/webImages/updateLoader.gif" alt="Updating" />
</center>
</div>';

echo '<div id="ajaxSuccess" style="display:none; position:absolute; z-index:12; top: 300px; left:400px;  border: 1px solid #000;">
	<img src="/style/webImages/operationSuccessful.jpg" alt="Operation Successful" />
</div>';

echo '<div id="ajaxFailed" style="display:none; position:absolute; z-index:12; top: 300px; left:400px;  border: 1px solid #000;">
	<img src="/style/webImages/operationFailed.jpg" alt="Operation Failed" />
</div>';

// Includes the necessary post it files and calls the setupPostIt function to set up post it.
include_once('postItFunctions.inc.php');
setupPostIt();

// Output the content of the main frame
mainMyCollection($collectionId, $loggedIn);

// Finish with end of HTML
finishHtml();

?>
