<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Submit: Add View</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<!--
<div class="specialtext2">
<a href="<?echo $domainName;?>About/Manual/Movies/submitview.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a View</strong>: <a href="<?echo $domainName;?>About/Manual/Movies/submitview.avi" target='_blank'>video</a>
</div>
-->

<p>
The data entered on the <strong>Add View</strong> screen provides information about
the view of the specimen to include imaging technique, preparation technique,
specimen part, sex, form, developmental stage, view angle and highest taxon
to which this view is applicable. A view and specimen must exist before an image is uploaded.
</p>
<p>Path to <strong>Add View</strong>: <em>header menu</em> <strong>> Tools > Submit > View</strong>
</p>

<img src="ManualImages/add_view.png" hspace="20" />
<p>Forms in Morphbbank's latest version (3.0) use javascript and <em>default values</em> to speed data-entry for the online user. Note in the Add View screen, the first 7 fields are default to <strong>Unspecified</strong> or <strong>Not specified.</strong> The user may leave those and only change the desired fields and add terms as necessary to each of the drop-downs. Once a user clicks <strong>Submit</strong>, they are easily able to add another <em>very similar view</em> if needed.
</p>

<div class="specialtext3">N.B. The person logged-in will be the name of the person that displays
in the "Contributor" field above. If a Submitter is entering data on behalf of a Contributor, select the 
Contributor's name from the drop-down. Go to <a href="<?echo $domainName;?>About/Manual/userPrivileges.php">Users and their Privileges</a> for more about Morphbank Group Roles &amp; Contributor / Submitter status.
</div>
<ul>
<li><strong>Imaging Technique</strong>, <strong>Imaging Preparation Technique</strong>,
<strong>Specimen Part</strong>, <strong>Sex</strong>, <strong>Form</strong>,
 <strong>Developmental Stage</strong>, and <strong>View Angle</strong>
 <br />
<em>All of these fields work in the same manner</em>. Options can be added to any of these drop-downs by selecting the add <img src="../../style/webImages/plusIcon.png" /> icon. 
This process is available only for those authorized through login permissions.
<br />
<br />
<img src="ManualImages/add_view_detail.png" />
<p>
Choose one of the options from the drop-down list for each of the 7 fields.
The drop-down lists contain terms based on community consensus. The choices for Sex might consist of:
Male, Female, Bisexual, Indeterminate (if the specimen was examined but
the sex could not be determined), Unknown (if the specimen was not
examined for its sex), Transitional (if the specimen is between sexes, like
sequential hermaphrodites).
<p>
If the option desired is not in the drop-down, click the <img src="../../style/webImages/plusIcon.png" />
and enter the appropriate text on the <strong>Add Sex</strong> page and <strong>Submit</strong> . A
confirmation message will appear on screen to let the user know that the
addition was successful. Choosing the select icon will redirect the
information back to the Add Specimen screen. The added information
will be a permanent addition to the list of options. Now choose the
appropriate sex from the drop-down list. <em>Repeat for </em><strong>Add Form</strong> and <strong>Add
 Developmental Stage</strong>, adding terms to the drop-downs, as needed.
</p>
</li>
<li><strong>View Applicable To</strong> (Required)
<br />
It is possible for a view to be applicable
outside of the user's immediate taxon of interest. For example, the view
name of lateral habitus, when photographing a wasp, is relevant for
Insecta but it can also be applied to Arthropoda, Hexapoda, and perhaps
Animalia. The user should decide the <em>highest possible applicable taxon</em> in
which they have confidence that the terminology for that view is relevant.
In this case, lateral habitus may be useful as a view name through
Hexapoda, but may not necessarily apply to images of the side view of all
animals.
<p>To insure accuracy, taxonomic names need to be selected from
<strong>Taxon Name Search</strong> screen. Select the name of the highest taxon to
which this view is applicable. Traverse through the levels until the
appropriate scientific name is found. Then click the select icon, it
will automatically direct the user back to the add view screen and the
appropriate name will be filled in.</p>
<img src="ManualImages/view_applicable_to.jpg" />
<li><strong>Contributor</strong> (Required)<br />
Select the name of the contributor (person having the authorization to
release the images) from the dropdown list. The contributor can be
different from the submitter (person entering the data). If you need to add
new entries to this list, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.
</li>
<br />
<li><strong>Add External Links</strong> to this record. For complete
instructions on providing external links refer to <a href="<?echo $domainName;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External Links</font> to open this feature. See next: 
</li>
<br/>
<img src="ManualImages/add_externalLinksRef.png" alt="external link options" hspace="20" vspace="10"/>
	<ul>
    <li>Choose the <strong>Type</strong> of External Link (some examples are: GenBank, Project, Institution, ...)    </li>
    <li>Enter the text for the <strong>Label</strong> the user in Morphbank will click to go to this URL.    </li>
    <li>Enter the complete <strong>URL</strong> here.    </li>
    <li>The <strong>Description</strong> field is optional.    </li>
    <li>Click the <strong>+</strong> icon to add additional external links.    </li>
    <li>Click the <strong>-</strong> icon to remove any outdated links.    </li>
   <br />
</ul> 
<li><strong>Add External References</strong> to this record. For complete
instructions on providing external references refer to <a href="<?echo $domainName;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External References</font> to open this feature. See next:<br/>
<img src="ManualImages/add_externalRefs.png" alt="external identifiers" hspace="20" vspace="10"/>
<ul>
    <li>Enter the <strong>Description</strong> for the External Reference. This will appear to the Morphbank user as a label in front of the unique id.
    </li>
    <li>Enter the <strong>Identifier</strong> unique for this locality in the remote database in the <strong>Unique Reference ID</strong> field.</li>
</ul>
</li>
</ul>
    	<div class="specialtext3">
        <ul>
        <li><strong>Unique Reference ID</strong> <em>best practice</em> is to combine an <strong>acronym prefix</strong> + an <strong>identifier</strong>.</li>
        <li>The database table storing this identifier requires the values be unique. If the identifier string entered is already in this table, the user will have to figure out a different prefix.</li>
        <li>For example, a user, Fred S Unstead, has a View with ID=123456 and puts his initials as the prefix for: <strong>FSU:123456</strong></li>
        	<ul>
            <li>Florida State University (FSU) entered View IDs with prefix: FSU + an identifier (123456).</li>
            <li>Fred S Unstead needs to change his prefix in some way, for example: <strong>FSU-V:123456</strong> (where the V is for View).</li>
            </ul>
        <li>The external unique reference ID can be used in future uploads and for updates of current records in Morphbank.    
        </ul>
        </div>  
</ul>
</p>
<p>
When the <strong>Add View </strong>form has been completed, <strong>Submit</strong> to complete
the add view process. A message will confirm that you have <em>successfully
added a view</em>. From this point the user can continue to add additional
views or return to the <strong>Submit</strong> screen.
</p>
<div class="specialtext3">Note: When the <strong>Add View</strong> screen is activated from the
 <strong>Add Image</strong> upload screen no message will be seen. Instead, the new
view will appear in the appropriate field on the <strong>Add Image</strong> upload
form.
</div>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/uploadSubmitImage.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	