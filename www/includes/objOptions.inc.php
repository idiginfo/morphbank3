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

include_once ("urlFunctions.inc.php");
$objOptions = array('Info' => true, 'Edit' => true, 'Annotate' => true,
	'Select' => false, 'Delete' => false, 'Copy' => false);

function printOptions($myObjOptions, $objId, $typeObj = null, $objTitle = '', $target='target="show"')
{
	global $config, $objInfo;

	//var_dump($objInfo);
	if ($objInfo) {
		if ($objInfo->getUserId() == null)
		$myObjOptions['Annotate'] = false;
	}

	if ($_GET['referer'] == "search") {
		$referer = "search";
	} elseif ($_GET['referer'] == "browse") {
		$referer = "browse";
	} else {
		$referer = "";
	}
	$myHtml = '';
	//$objTittle = '';

	global $config;
	if ($myObjOptions['Copy'] && $objInfo->getUserId() != null) {
		//only working for collection
		if ($typeObj == 'Browse Collection') {
			$myHtml .= '&nbsp;<a href="' . $config->domain . 'includes/copyCollection.php?id='
			. $objId . '">'
			.getCopyImageTag('Make a copy of this collection') . '</a>';
		} else {
			$myHtml .= '&nbsp;<a href="#" onclick="copyCollectionCharacter(event, \''
			. $objId . '\', \'' . $typeObj . '\'); return false;">'
			. getCopyImageTag('Make either a Character Collection or Regular Collection from'
			.' this collection') . '</a>';
		}
	}

	if ($myObjOptions['Info']) {
		$taxaFlag = ($typeObj == "Taxa") ? "&tsn=true" : "";
		if ($typeObj == "Collection" || $typeObj == "MbCharacter") {
			$myHtml .= '&nbsp;'. showTag($objId ,false, null, $taxaFlag, $target)
			.getInfoImageTag() . '</a>';
		} else {
			$myHtml .= '&nbsp;'.showTag($objId, false, null, $taxaFlag, $target);
			$myHtml .= getInfoImageTag() . '</a>';
		}
	}

	if ($myObjOptions['Edit']) {
		$tsn = ($typeObj == 'Taxa') ? '&tsn=true' : '';
		$myHtml.= ' &nbsp;';
		if ($typeObj == "TaxonName") {
			$myHtml .= '<a href="#" onclick="alert(\'You cannot edit a public annotation.\');'
			.' return false;">'
			. getEditImageTag('Edit') . '</a>';
		} elseif ($typeObj == "Taxa") {
			$url = $config->domain . "Edit/index.php?id=" . $objId . $tsn;
			$myHtml .= '<a href="javascript:checkUser(\'' . $url . '&pop=yes\',\''
			. $objId . '&tsn=true\');">'
			.getEditImageTag('Edit') . '</a>';
		} else {
			$myHtml.= getEditObjectLink($objId);
		}
	}

	if ($myObjOptions['Annotate']) {
		if ($typeObj == 'Taxa') {
			$myHtml .= '&nbsp; <a href="javascript: openPopup(\'' . $config->domain
			. 'Admin/TaxonSearch/annotateTSN.php?tsn=' . $objId . '&amp;pop=yes\')">'
			.getAnnotateImageTag('Annotate') . '</a>';
		} else {
			$myHtml .= '&nbsp; <a href="'
			. '/Annotation/index.php?id=' . $objId . '" target="_blank">'
			.getAnnotateImageTag('Annotate') . '</a>';
		}
	}

	//if ($myObjOptions['Viewer']) {
		$myHtml .= '&nbsp; ' . showImageViewerTag($objId);
	//}

	if ($myObjOptions['Select']) {
		$objTitle = htmlspecialchars(str_replace("'", "\'", $objTitle));

		if (strtolower($typeObj) == 'publication') {
			$myHtml .= '&nbsp; <a href="javascript:opener.updatePublication(\''
			. $objId . '\', \'' . $objTitle . '\');window.close();" ';
		} elseif (strtolower($typeObj) == 'mirror') {
			$myHtml .= '&nbsp; <a href="javascript:opener.updatePrefServ(\''
			. $objId . '\', \'' . $objTitle . '\');window.close();" ';
		} else {
			$myHtml .= '&nbsp; <a href="javascript:submitParentWindowForm(\''
			. $typeObj . '\', \'' . $objId . '\', \'' . $objTitle . '\', \'' . $referer . '\');" ';
		}

		$myHtml .= ' class="button smallButton"><div>Select <span>&#8730;</span></div></a>';
	}

	return $myHtml;
}
?>
