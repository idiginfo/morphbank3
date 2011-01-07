
<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);

	?>

	<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Character Collections</h1>
<div id=footerRibbon></div>
		<td width="100%">
		<br />
Starting with Morphbank 2.8 you can use Morphbank images and other objects to define a character.  In this version of the software continuous and ordered characters are not supported.  Please use the <a href="<?echo $config->domain;?>Help/feedback/" target="_blank">feedback form</a> and send us email
 regarding questions and suggestions.  It is highly suggested to take the short video tour.
		<div class="specialtext2">
		<b>Quick note on this version of the manual:</b>
		<br /><br />Please feel free to email <b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</b> us or call (850-644-6366) if you have specific questions about the software that the manual presently does not cover.  We are in the process of updating it.
</div>	

	<h3>How it Works</h3>
<br />The Character Collection interface is a workspace for defining characters.  The general functionality of the collection is the same as a
 <a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsSample.php">regular collection</a> in Morphbank where you can slide sort images, 
 label images and save the organization of the objects within the collection. However, the Character Collection adds the functionality of an
  image as being part of a character and coded with a character state. To create a character using images you need to be in the Images tab from the
   My Manager (<em>header menu</em> <strong>> click on Browse > Image tab</strong> opens by default).  Check all of the images you want to use for representing the character.  
<br /><br />
<br />
For "Check All" > Create Character > Submit > the following screen pops up.
<br /><br />
<img src="<?echo $config->domain;?>About/Manual/ManualImages/character_collection.png">
<br /><br />
A new Character Collection starts with only the "undesignated state tile" appearing in the workspace with all of the selected images behind it.
  These would effectively be coded with a <b>?</b> or unknown.  Coding the character can be done in two ways:
<br /><br />
1. Click the check boxes (highlight) the OTUs (represented as image tiles) you wish to code for a new character state> click Add State.  All of the 
images will appear behind the character state tile.  A image behind a character state tile represents the Operational Taxonomic Unit or OTU that
the taxon or specimen pictured in the image belongs to.


<div class="specialtext3">
<b>What does each image represent</b>
<br />Every image in Morphbank is given a taxon name at the time of upload.  These names can be valid and accepted names from ITIS or they can be
 manuscript names added by the user.  Check out the <a href="<?echo $config->domain;?>About/Manual/addTaxonName.php">Add Taxon Name</a> section of the manual for more information on how to add a taxon name.  Applying a character state to a Morphbank Image is a way to make an annotation on that taxon, specimen or OTU (Operational Taxonomic Unit) associated with the Image.
<!--Soon Morphbank will support matrices in which you can associate OTUs and Character Collections.
Operational Taxonomic Units or OTUs may be created by creating collections of taxon names or specimens.  These OTUs then communicate a users taxon concept regarding that group of taxa or specimens.  OTUs are visible from the Taxa tab in the My Manager.  When you code an image in the Character Collection, that applied to a Matrix, you are making a kind of annotation that says " this character state applies to every OTU in which that taxon or specimen exists or the taxon name applied to the specimen.".
-->
</div>

2. Add all of your character states by clicking the Add State button for each new state you wish to add, then drag the images tiles (representing OTUs) behind a tile.  This process is done one image at a time.
			<br />
            <br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/myManagerAnnotations.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>