<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);

	?>

<div class="mainGenericContainer" width="100%">
<!--change the header below -->
<h1>Edit Collection</h1>
<div id=footerRibbon></div>
<br />
Edit Collection contains the previously entered collection data that can be 
edited by the owner (only available if the collection is <strong><em>not yet published.</em></strong>)   
 <div class="specialtext2">
Edit Collection is now accessed through the Collections tab of the new My Manager interface.

<ol>
<li>Select from the Header Menu <b>Tools > My Manager</b> and then click on the Collections tab.
</li>
<li>Or, Header Menu > <a href="<?echo $domainName;?>About/Manual/browseC.php"><strong>Browse > Collections.</strong></a>
The old Collection Manager has been replaced with the My Manager Collections tab.
</li>
<li>Find your collections with <strong>Keyword search</strong> and/or <strong>Limit Search by</strong> Contributor.
</li>
<li>Click the <img src="<?echo $domainName;?>/style/webImages/infoIcon-trans.png" /> icon to open the Collection.
</li>
<li>You may wish to: Change the order of the objects and save the order, change the titles of some of the objects in the collection,
or perhaps delete objects from the collection.
</li>
<li>All changes must be followed by clicking on the update/submit button to register the 
change. 
</li>
</ol>
</div>

<br />To get complete instructions for editing Collections go to the 
<a href="<?echo $domainName;?>About/Manual/browseImages.php#createCollection">Browse - Add Images to a Collection</a>
 or <a href="<?echo $domainName;?>About/Manual/collections.php">Collections</a> areas of this manual. 
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/edit_taxon_name.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	