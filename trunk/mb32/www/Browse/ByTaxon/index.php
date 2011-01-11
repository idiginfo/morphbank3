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
include('treeview.inc.php');
include('lib/template.inc.php');
include('lib/layersmenu.inc.php');
include('webServices.inc.php');

/* The beginnig of HTML */
$title = 'Browse - Taxon hierarchy';
$includeJavaScript = array('layerstreemenu-cookies.js', 'layersmenu-browser_detection.js');
initHtml($title, NULL, $includeJavaScript);
echoHead(false, $title);

/* Set needed variables */
$browseByImage = $config->domain . 'Browse/ByImage/';
$annotationHref = $config->domain . 'Admin/TaxonSearch/annotateTSN.php';
$tsn = isset($_GET['tsn']) ? $_GET['tsn'] : 0;
$returnUrl = $_SERVER['HTTP_REFERER'];
if (!isset($_GET['majorCategories'])) $_GET['majorCategories'] = '0';
if (!isset($_GET['noSynonyms'])) $_GET['noSynonyms'] = '0';
if (!isset($_GET['images'])) $_GET['images'] = '0';

/* Build page content */
echoGenericContainer($title);
echoJSFuntionShow();
echoFilterForm($tsn, $_GET);
$treeString = createTreeView($tsn, $_GET);
echoTreeMenu($treeString, $_GET);

/* Finish with end of HTML */
finishHtml();

/**
 * Echo generic html container
 * @param string $title Title of the page
 * @return void
 */
function echoGenericContainer($title){
	echo '<div class="mainGenericContainer" style="width:770px">
			<h1 align="center">' . $title . '</h1><br/><br/>';
}

/**
 * Echo javascript for selcting form filter
 * @return void
 */
function echoJSFuntionShow()
{
	echo '
    <script language="JavaScript" type="text/javascript">
      function showCategories( form) {
        form.submit();
        //location.reload(true);
      }
    </script>';
}

/**
 * Echo form filter
 * @param integer $tsn TSN Id
 * @param array $array GET values
 * @return void
 */
function echoFilterForm($tsn, $array){
	echo '<form action="index.php" method="get">
		  <input name="tsn" value="' . $tsn . '" type="hidden" />
          <input type="checkbox" name="majorCategories" onclick="showCategories(this.form)" value="1" title="Check if you want to see only the major categories"' . (($array['majorCategories'] == '1') ? 'checked="checked" ' : '') . ' /> Only major categories &nbsp;&nbsp;&nbsp;
          <input type="checkbox" name="noSynonyms" onclick="showCategories(this.form)" value="1" title=" Check if you want to see only valid/accepted taxa by ITIS"' . (($array['noSynonyms'] == '1') ? 'checked="checked" ' : '') . ' /> Only valid/accepted taxa &nbsp;&nbsp;&nbsp;
          <input type="checkbox" name="images" onclick="showCategories(this.form)" value="1" title="Check if you want to see only taxa associated with images"' . (($array['images'] == '1') ? 'checked="checked" ' : '') . ' /> Only taxa with images &nbsp;&nbsp;&nbsp;
          </form>';
}

/**
 * Echo empty html if tree menu empty
 * @see echoTreeMenu()
 * @return void
 */
function echoEmptySet()
{
	echo '<div class="innerContainer7">
      <h2>Empty set.</h2>
        <ul>
           <li>This tsn id doesn\'t exist</li>
        </ul>
      </div>';
}

/**
 * Creates the tree view
 * @param integer $tsn TSN Id
 * @param array $array GET array values
 * @return string
 */
function createTreeView($tsn, $array){
	global $browseByImage;
	$treeView = new TreeView();
	$treeView->setExtraLinkHref($browseByImage);
	$treeView->setExtraLinkHref1($itisHref);
	$treeView->setExtraLinkHref2('../../Admin/TaxonSearch/annotateTSN.php');
	return $treeView->createTree($tsn, $array['majorCategories'], $array['noSynonyms'], $array['images']);
}

/**
 * Echo tree menu
 * @param string $treeString
 * @param array $array GET array values
 * @return void
 */
function echoTreeMenu($treeString, $array){
	
	echo '<table width="100%" cellspacing="0" border="1"><tr>
        <td height="500px" valign="top">';
	if ($treeString != "") {
		$mid = new LayersMenu();
		$mid->setPrependedUrl("?tsn=");
		$mid->setShowMajorCategories($array['majorCategories']);
		$mid->setShowSynonyms($array['noSynonyms']);
		$mid->setShowWithImages($array['images']);
		$mid->setLibjsdir('../../js/');
		$mid->setLibdir('../../includes/lib/');
		$mid->setImgdir('../../style/webImages/');
		$mid->setImgwww('../../style/webImages/');
		$mid->setDownArrowImg('down-nautilus.png');
		$mid->setForwardArrowImg('forward-nautilus.png');
		$mid->setMenuStructureString($treeString);
		$mid->parseStructureForMenu("treemenu1");
		echo $mid->newTreeMenu("treemenu1");
	} else {
		echoEmptySet();
	}
	echo '</td></tr></table></div>';
}
