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

include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once( 'http_build_query.php');

if(!isset($objInfo)) {
	echo '
	<script type="text/javascript" language="javascript">
		window.location.href="'.$config->domain.'Submit/index.php";
	
	</script>';

}

$id = $_GET['id'];

$page = $config->domain . 'MyManager/';


$BOInfo = 'Keywords field queries the following:<br /><br />'
		.'- Contributor name and login name<br />'
		.'- Submitter name and login name<br />'
		.'- Group Name<br />';

if ($id == "imageTab") {
	$contentPage = $page.'image.php?'.http_build_query($_GET);
	//$contentPage = str_replace('id=imageContent', '', $contentPage);
	
	$imageInfo = $BOInfo.'- Form<br />'
		.'- Sex<br />'
		.'- Developmental stage<br />'
		.'- Imaging technique<br />'
		.'- Imaging preparation technique<br />'
		.'- Part<br />'
		.'- Image id<br />'
		.'- Locality<br />'
		.'- View<br />'
		.'- Resolution<br />'
		.'- Magnification<br />'
		.'- Copyright<br />'
		.'- Taxonomic Names<br />'
		.'- TSN<br />'
		.'- Institution Code<br />'
		.'- Collection Code<br />'
		.'- Catalog Number<br />';
		
	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" /><br />
				</center>
				
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');	
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$imageInfo.'\') };
		
				</script>';
	echo '			
			</div>			
		 </div>';
}

elseif($id == "allTab") {
	//$contentPage = $page.'image.php?'.http_build_query($_GET);
	$contentPage = $page.'all.php?'.http_build_query($_GET);
	
	$allInfo = 'Searches accross all the different object types in Morphbank <br />(i.e. Returns Image records, specimen records etc. in one search)';
	
	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');			
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$allInfo.'\') };
				</script>';
			
	echo '			
			</div>			
		 </div>';
}

/*

if ($id == "imageContent") {
$contentPage = $page.'image.php?'.http_build_query($_GET);
//$contentPage = str_replace('id=imageContent', '', $contentPage);
echo '
<div class="TabbedPanelsContent">			
		<div id="imagethumbspage" class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" /><br />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\''.$contentPage.'\')" , 1000);			
			</script>';
echo '			
		</div>			
	 </div>';
}

if ($id == "imageContent") {
$contentPage = $page.'image.php?'.http_build_query($_GET);
//$contentPage = str_replace('id=imageContent', '', $contentPage);
echo '
	<script type="text/javascript">
		blindDownContent(\''.$contentPage.'\');	
	</script>';


}*/ 

elseif($id == "specimenTab") {
	//$contentPage = $page.'image.php?'.http_build_query($_GET);
	$contentPage = $page.'specimen.php?'.http_build_query($_GET);
	
	$specimenInfo = $BOInfo.'- Form<br />'
		.'- Sex<br />'
		.'- Developmental stage<br />'
		.'- Type status<br />'
		.'- Imaging technique<br />'
		.'- Imaging preparation technique<br />'
		.'- Part<br />'
		.'- Specimen id<br />'
		.'- Locality<br />'
		.'- View<br />'
		.'- Resolution<br />'
		.'- Magnification<br />'
		.'- Copyright<br />'
		.'- Type Status<br />'
		.'- Taxonomic Names<br />'
		.'- TSN<br />'
		.'- Institution Code<br />'
		.'- Collection Code<br />'
		.'- Catalog Number<br />';

	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');			
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$specimenInfo.'\') };
				</script>';
			
	echo '			
			</div>			
		 </div>';

}elseif($id == "viewTab") {
	$contentPage = $page.'view.php?'.http_build_query($_GET);
	
	$viewInfo = $BOInfo.'- View Name<br />'
		.'- Sex<br />'
		.'- Developmental stage<br />'
		.'- Imaging technique<br />'
		.'- Imaging preparation technique<br />'
		.'- Part<br />'
		.'- View id<br />'
		.'- Form<br />'
                .'- View Taxon name<br />'
                .'- TSN of View<br />';

	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');	
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$viewInfo.'\') };		
				</script>';
			
	echo '			
			</div>			
		 </div>';
	
}elseif($id == "localityTab") {
	$contentPage = $page.'locality.php?'.http_build_query($_GET);
	
	$localityInfo = $BOInfo.'- Locality<br />'
		.'- Country<br />'
		.'- Latitude<br />'
		.'- Longitude<br />'
		.'- Minimum and Maximum Elevation<br />'
		.'- Description<br />'
		.'- Continent / Water Body id<br />';

	
	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');	
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$localityInfo.'\') };				
				</script>';
			
	echo '			
			</div>			
		 </div>';
}elseif($id == "taxaTab") {
	$contentPage = $page.'taxa.php?'.http_build_query($_GET);
	
	$taxaInfo = $BOInfo.'- Scientific Name<br />'
		.'- etc<br />'
		.'- etc<br />'
		.'- etc<br />'
		.'- etc<br />'
		.'- etc<br />'
		.'- etc<br />';

	
	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');	
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$taxaInfo.'\') };				
				</script>';
			
	echo '			
			</div>			
		 </div>';
}elseif($id == "collectionTab") {
	$contentPage = $page.'collection.php?'.http_build_query($_GET);
	
	$collectionInfo = $BOInfo.'- Collection Name<br />'
		.'- Collection Id<br />'
		.'- Collection Object Titles<br />'
		.'- Collection Object Role<br />'
		.'- Collection Object Ids<br />';
	
	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');		
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$collectionInfo.'\') };			
				</script>';
			
	echo '			
			</div>			
		 </div>';
}elseif($id == "annotationTab") {
	$contentPage = $page.'annotation.php?'.http_build_query($_GET);

	$annotationInfo = $BOInfo.'- Annotation Title<br />' 
		.'- Annotation Id<br />'
		.'- Annotation Object Ids<br />'
		.'- Comment<br />'
		.'- XML Data<br />'
		.'- Annotation Label<br />'
		.'- Specimen Id<br />'
		.'- TSN<br />'
		.'- Rank Name<br />'
		.'- Determination Annotation Type<br />'
		.'- Collection Id<br />'
		.'- Materials Used<br />'
		.'- Prefix<br />'
		.'- Suffix<br />'
		.'- Resources Used<br />'
		.'- Taxonomic Names<br />';

	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');		
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$annotationInfo.'\') };	
				</script>';
			
	echo '			
			</div>			
		 </div>';
}elseif($id == "matrixTab") {
	echo"Matrix Content<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		";
}elseif($id == "pubTab") {
	$contentPage = $page.'publication.php?'.http_build_query($_GET);

	$publicationInfo = $BOInfo.'- Publication Id<br />' 
		.'- DOI<br />'
		.'- Publication Type<br />'
		.'- Author<br />'
		.'- Title<br />'
		.'- Month<br />'
		.'- Publisher<br />'
		.'- School<br />'
		.'- Series<br />'
		.'- Note<br />'
		.'- Organization<br />'
		.'- Institution<br />'
		.'- Volume<br />'
		.'- Year<br />'
		.'- ISBN<br />'
		.'- ISSN<br />';

	echo '
	<div class="TabbedPanelsContent">			
			<div id="imagethumbspage" class="imagethumbspage">
				<center>				
					<img src="/style/webImages/loading3.gif" alt="loading" />
				</center>
				<script type="text/javascript">
					replaceContent(\''.$contentPage.'\');		
					document.getElementById("keywordFilterId").onmouseover = function(e) { imagePostit(e, \''.$publicationInfo.'\') };		
				</script>';
			
	echo '			
			</div>			
		 </div>';
}

?>
