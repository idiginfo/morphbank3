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
		<h1>All tab - My Manager </h1>
<div id=footerRibbon></div>
		<td width="100%">		
	<p>With the <strong>All</strong> tab in My Manager, users can see almost every object in Morphbank.
	An individual user will see:

<ol>
<li>any objects they've contributed/submitted, regardless of group
</li>
<li>the objects of all group members, for all groups the user belongs to,
</li>
<li>and all other published objects.
</li>
</ol>
Unpublished Objects contributed/submitted by members of other groups will not be seen.

</p>

<p>Use the modular features (<a href="<?echo $domainName;?>About/Manual/graphicGuide.php" >icons</a> and tools) of
My Manager to browse, search, sort, collect, edit, and annotate any objects found here. The icons and modular tools (like Edit and Submit) work the same or analogously throughout Morphbank. Once introduced to a My Manager page, the user will be able to jump to the other My Manager pages understanding the features they'll
find there. </p>
<p>After <em>login</em>, the path to the <strong>All tab</strong> is <strong>Header Menu > Browse > All.</strong></p>	
	
	<div class="specialtext3"><h2>All</h2>: In this snap-shot of the <strong>All</strong> tab, there are 3 different 
	Morphbank Objects, a collection, an image and a view. The user can carry out various actions, 
	depending on the object type. Note the icons present for the <strong>collection</strong>, <strong>image</strong>
	and <strong>view</strong> vary. </div>
	
	<img src="ManualImages/my_manager_all.png" alt="My Manager All Tab" hspace="25" />
	<br />
    <br />
<h3>Features and Functions of the All tab</h3>

<br />
		Tag Descriptions
	<ol>
	
	<li><a href="<?echo $domainName;?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="Feedback" align="middle"></a>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank. The link labeled <strong><font color="red">(Help)</font></strong> opens a page in the online User Manual.</li>
	
	<li><a href="<?echo $domainName;?>About/Manual/myManagerFeatures.php" ><strong>Keywords</strong></a>: This is an important search feature in Morphbank. <em>A mouse-over will indicate the fields the Keywords Search function
	utilizes</em>.</li>
	
	<li><a href="<?echo $domainName;?>About/Manual/myManagerFeatures.php" ><strong>Sort by</strong></a>: Use choices in the drop down for sorting search results.</li>
	
	<li><a href="<?echo $domainName;?>About/Manual/myManagerFeatures.php" ><strong>Limit Search by</strong></a>: Users wishing to see only those objects they have contributed simply click the appropriate box to limit the search results. Results may also be restricted to any particular group the user selects. This capability makes collecting, annotating and editing select items easy and fast.</li>
	
	<li><a href="<?echo $domainName;?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation</strong></a>: Drop-down choices vary depending on the My Manager Tab. The user can select more
	than one object using the box to the left of the Object title (see the green highlighted entry above). Having selected
	several (or many) objects, choose the action to be performed on those objects from the <strong>Select Mass Operation
	</strong> drop-down. With this method, a user might change the date-to-publish on many objects at once. Or perhaps, a user
	might create a collection.</li>
	
	<li><strong><font color="blue">(edit...)</font></strong>: Click if you wish to change the title of one of your unpublished collections. Click the name of the collection to
	 view the collection itself.</li>
	
	<li><strong>Icons</strong>: Click to jump to the 
	<a href="<?echo $domainName;?>About/Manual/graphicGuide.php" >guide to Morphbank graphics</a> for a thorough
	overview.
		<ul>
	
	<img src="ManualImages/icon_sample1.png" alt="icons" vspace="10"  />
	
	<li>Use the <img src="<?echo $domainName;?>/style/webImages/copy-trans.png" /> icon to <strong>copy a collection</strong>.</li>
	<li>In Morphbank, the <img src="<?echo $domainName;?>/style/webImages/infoIcon-trans.png" />
	 <strong>information</strong> icon opens a feature called <strong><a href="<?echo $domainName;?>About/Manual/show.php" >Single Show</a></strong> that varies with
	 the object. In general, one sees data about the object.</li>
	<li>Use the <img src="<?echo $domainName;?>/style/webImages/calendar.gif" /> <strong>calendar</strong>
	icon to easily change the date to make an image visible to all who use Morphbank or extend the time the object remains private.</li>
	<li>With <img src="<?echo $domainName;?>/style/webImages/delete-trans.png" />, the user may <strong>delete
	</strong> an object in Morphbank. Note the delete icon will only appear for objects you have permission to delete.</li>
		</ul>
	<br />
	</li>
	<li><strong><font color="blue">(Publish Now)</font></strong>: A simple feature of Morphbank designed to make it easier for a user to track and change the
	dates objects in Morphbank can be released to public view. Click the link <strong><font color="blue">(Publish Now)</font></strong> to automatically
	change the date-to-publish, i.e. release the object to public view. A user may click the box <img src="ManualImages/check_box.png" alt="check box" /> for one or many <strong>unpublished</strong> objects and then use the <strong>Select Mass Operation</strong> feature to change the date-to-publish on all the selected objects at once.
	</li>
	
	<li><strong>More Icons</strong>: as seen for a given Image.
		<ul>
	<img src="ManualImages/icon_sample2.png" alt="icons" vspace="10"  />
		
		<li>Anywhere the <img src="<?echo $domainName;?>/style/webImages/edit-trans.png" /> <strong>Edit</strong> icon appears, a user
		can access the metadata for an object to add-to, update or correct information associated with that object. Note
		this is applies to objects the user has contributed/submitted. If data needs to be added/changed on other
		objects not owned by the user, they can contact 
		<b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu 
		<font color="blue">dot</font> edu</b></li>	
		<li>Users may <strong>Annotate</strong> any object in Morphbank where the 
		<img src="<?echo $domainName;?>/style/webImages/annotate-trans.png" /> icon appears. These annotations
		are permanently associated with a given Morphbank Object. Note some annotations, like <strong>Determination
		Annotations,</strong> allow users to select a date-to-publish the comment/s. Other annotations, like <strong>Taxon
		Name Annotations</strong> are made public immediately. If <strong>Annotations</strong> are present, the
		link will indicate how many there are and the user can view them by clicking on the link.</li>
		<li>Use the <img src="../../style/webImages/magnifyShadow-trans.png" alt="FSIviewer icon"> magnifying glass
		to open an image in Morphbank with the <a href="<?echo $domainName;?>About/Manual/FSI.php">FSI Viewer</a> to zoom, rotate and adjust 
        the colors of an image to help reveal more image features. Morphbank utilizes this proprietary viewer with its unique capabilities to 
        increase the value of the photograph for the user. Click on any thumbnail or click on the resulting image in the <strong>Image Record Show</strong> to 
	open the image in the FSI Viewer. Alternatively, click the <img src="../../style/webImages/magnifyShadow-trans.png" alt="FSIviewer icon">
	to open the image in the FSI Viewer.</li>
		</ul>
	</li>
	
	<li><h3>Creating Collections with My Manager</h3>: <em>This version of Morphbank allows users to collect <strong>any objects in Morphbank</strong> into a collection.</em> Prior to this version, only images could be gathered
	into a collection. One can also create a <strong>collection of collections</strong>, which is very useful when putting links in publications to objects in Morphbank.
   <div class="specialtext3">
    Currently, there are <em>3 types of Morphbank Collections</em>:
    <ol>
    <li>a Collection of Images (and / or other objects), 
    </li>
    <li>a Character Collection created to illustrate Character States for a defined Character, and an
    </li>
    <li>OTU Collection consisting of Specimens and Taxon Names -- designed to help a user describe <em>operational taxonomic units (OTU)s.</em>
    </li>
    </ol>
   </div>
	
	<div class="specialtext2">
    <h3>Short-cut instructions to creating a collection</h3>
	<ul>
	<li>To group objects together creating a collection, use the 
	<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> next to each Object Id and Title.
	<br />
	<em>Each item checked will be highlighted in green</em>.
	</li> 
	<li>After checking all desired items, go up to the <strong>Select Mass Operation</strong> drop-down
	and choose the desired action (like <strong>copy to a New Collection</strong> or <strong>Create a Character
	Collection</strong> and then click <strong>Submit</strong>.
	</li>
	<li>Other objects from other tabs in My Manager can be added to this Collection in the same manner.
	</li>
	</ul>
    </div>
	<p><strong>Image Collections</strong>, <strong>Mixed Collections</strong> &amp; <strong>Character Collections</strong> can be seen in the <a href="<?echo $domainName;?>About/Manual/myManagerCollections.php" ><strong>Collections</strong></a> tab. <strong>OTU Collections</strong> are found in the My Manager<a href="<?echo $domainName;?>About/Manual/myManagerTaxa.php"> - <strong>Taxa </strong></a>tab.
    </p>
    
	
	
    </li>
	</ol>
    
		<a href="<?echo $domainName;?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?echo $domainName;?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
		<br />
			<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/myManagerImages.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>