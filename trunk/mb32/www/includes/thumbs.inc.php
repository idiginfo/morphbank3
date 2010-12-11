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

function printEmptySet() {
	echo '<div class="innerContainer7">
			<h1>Empty set.</h1>
				<ul>
			 		<li>Images are either not released as of today</li>
					<b>Or</b>
					<li>You do not have permission to view object</li>
				</ul>
			</div>';
}

function printLinks($total, $numPerPage, $offset, $callingPage) {

	global $config;
	include_once( 'http_build_query.php');

	$totalPages = ceil($total/$numPerPage);
	$lastGroup = ($total%$numPerPage);
	$lastGroup = $lastGroup==0?$numPerPage:$lastGroup;
	$pagesToShow = $config->displayThumbsGroup; // =9

	$start = ($offset/$numPerPage)-(int)($pagesToShow/2);
	if ($start < 1) $start = 0;

	$numPages = $pagesToShow+$start;
	if (($numPages*$numPerPage)> $total) {
		$numPages = $totalPages;
		//if ($lastGroup != 0)  $numPages = $numPages+1;
	}

	//Reset goTo allways
	$_GET['goTo'] = NULL;

	// go First
	$_GET['offset'] = 0;
	echo '<a href="'.$callingPage.'?'.htmlentities(http_build_query($_GET)).'" title="go to first">
			<img src="/style/webImages/goFirst2.png" border="0" alt="goToFirst" /></a>&nbsp;&nbsp;';

	if ($offset>$numPerPage)
	$_GET['offset'] = $offset-$numPerPage;
	else
	$_GET['offset'] = 0;

	if ($offset <> 0)
	echo '<a href="'.$callingPage.'?'.htmlentities(http_build_query($_GET)).'" title="previous">
			<img src="/style/webImages/backward-gnome.png" border="0" alt="back" /></a>&nbsp;&nbsp;';

	for ($i = $start; $i < $numPages; $i++)
	{
		// Ideally we would use the method http_build_query($data), however this is for PHP>5.0
		// Could change if the PHP version is updated on the server.

		//$_GET['offset'] = ($i-1) * $numPerPage;
		$_GET['offset'] = $i * $numPerPage;
		$range1 = ($i * $numPerPage)+1;
		$range2 = $numPerPage*($i+1);
		if ($i == ($totalPages-1) & ($lastGroup > 0))
		$range2 = ($i * $numPerPage)+$lastGroup;
			
		if ($offset == $_GET['offset']) {
			//echo '<font color=#d50200>['.$range1.' - '.$range2.']</font> &nbsp';
			echo '<font color="#d50200">'.($i+1).'</font> &nbsp;';
		}
		else {
			//echo '<a href="'.$callingPage.'?'.http_build_query($_GET).'"><font color=#2e2eff>['.$range1.' - '.$range2.']</font></a> &nbsp;';
			echo '<a href="'.$callingPage.'?'.htmlentities(http_build_query($_GET)).'&amp;log=NO"><font color="#2e2eff">'.($i+1).'</font></a> &nbsp;';
		}
	}

	if ($offset+$numPerPage < $total) {
		$_GET['offset'] = $offset+$numPerPage;
		echo '<a href="'.$callingPage.'?'.htmlentities(http_build_query($_GET)).'" title="next">
				<img src="/style/webImages/forward-gnome.png" border="0" alt="foward" /></a>&nbsp;&nbsp;';
	}

	$_GET['offset'] = $total-$lastGroup;
	echo '<a href="'.$callingPage.'?'.htmlentities(http_build_query($_GET)).'" title="go to last"><img src="/style/webImages/goLast2.png"  border="0" alt="goToLast" /></a>
			&nbsp;&nbsp;<b> of '.$totalPages.'</b>';

}

function printLinksNew($total, $numPerPage, $offset, $callingPage) {

	global $config;
	include_once( 'http_build_query.php');

	$totalPages = ceil($total/$numPerPage);
	$lastGroup = ($total%$numPerPage);
	$lastGroup = $lastGroup==0?$numPerPage:$lastGroup;
	$pagesToShow = $config->displayThumbsGroup; // =9

	$start = ($offset/$numPerPage)-(int)($pagesToShow/2);
	$oneBack = $start+5;
	$oneForward = $start+7;
	if ($start < 1) $start = 0;

	$numPages = $pagesToShow+$start;
	if (($numPages*$numPerPage)> $total) {
		$numPages = $totalPages;
		//if ($lastGroup != 0)  $numPages = $numPages+1;
	}

	//Reset goTo allways
	$_GET['goTo'] = NULL;

	// go First
	$_GET['offset'] = 0;
	echo '<a href="javascript: manager_goToPage(1);" title="go to first">
			<img src="/style/webImages/goFirst2.png" border="0" alt="goToFirst" /></a>&nbsp;&nbsp;';

	if ($offset>$numPerPage)
	$_GET['offset'] = $offset-$numPerPage;
	else
	$_GET['offset'] = 0;

	if ($offset <> 0)
	echo '<a href="javascript: manager_goToPage('.$oneBack.');"  title="previous">
			<img src="/style/webImages/backward-gnome.png" border="0" alt="back" /></a>&nbsp;&nbsp;';

	for ($i = $start; $i < $numPages; $i++)
	{
		// Ideally we would use the method http_build_query($data), however this is for PHP>5.0
		// Could change if the PHP version is updated on the server.

		//$_GET['offset'] = ($i-1) * $numPerPage;
		$_GET['offset'] = $i * $numPerPage;
		$range1 = ($i * $numPerPage)+1;
		$range2 = $numPerPage*($i+1);
		if ($i == ($totalPages-1) & ($lastGroup > 0))
		$range2 = ($i * $numPerPage)+$lastGroup;
			
		if ($offset == $_GET['offset']) {
			//echo '<font color=#d50200>['.$range1.' - '.$range2.']</font> &nbsp';
			echo '<font color="#d50200">'.($i+1).'</font> &nbsp;';
		}
		else {
			//echo '<a href="'.$callingPage.'?'.http_build_query($_GET).'"><font color=#2e2eff>['.$range1.' - '.$range2.']</font></a> &nbsp;';
			echo '<a href="javascript: manager_goToPage('.($i+1).');"><font color="#2e2eff">'.($i+1).'</font></a> &nbsp;';
		}
	}

	if ($offset+$numPerPage < $total) {
		$_GET['offset'] = $offset+$numPerPage;
		echo '<a href="javascript: manager_goToPage('.$oneForward.');" title="next">
				<img src="/style/webImages/forward-gnome.png" border="0" alt="foward" /></a>&nbsp;&nbsp;';
	}

	$_GET['offset'] = $total-$lastGroup;
	echo '<a href="javascript: manager_goToPage('.$totalPages.');" title="go to last"><img src="/style/webImages/goLast2.png"  border="0" alt="goToLast" /></a>
			&nbsp;&nbsp;<b> of '.$totalPages.'</b>';

}

?>
