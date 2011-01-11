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
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Submit: Add Specimen</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
The data entered on the <strong>Add Specimen</strong> screen provides information about
the specimen and the parties involved in the collection of it. If not previously added, provisions have been
made on this screen to add a locality, if needed. Locality data is not required. Any instruction label that is followed by
an <font color="red">*</font> is a required field and must be completed before submitting.
</p>
<!--
<div class="specialtext2">
<a href="<?echo $config->domain;?>About/Manual/Movies/SubmitSpecimen.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a Specimen</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/SubmitSpecimen.avi" target='_blank'> video</a>
</div>
-->
<p>Path to <strong>Add Specimen</strong>: <em>header menu</em><strong> > Tools > Submit > Specimen</strong>
</p>
<img src="ManualImages/add_specimen.png" hspace="20" />
<div class="specialtext3">Note: The person logged-in will be the name of the person that displays
in the "Contributor" field above. If a Submitter is entering data on behalf of a Contributor, select the 
Contributor's name from the drop-down.
</div>
<ul>
<li><strong>Basis of Record</strong> (Required)<br />
Choose one of the options from the drop-down list. Choices are:
Observation, Living Organism, Specimen, Germplasm /Seed. The list of
options is based on the Darwin Core standard
<a href="http://rs.tdwg.org/dwc/terms/history/versions/index.htm">http://darwincore.calacademy.org/</a>. Basis of Record is defined as: A descriptive term indicating whether the record represents an object or observation. Another definition reads: The nature of the sample. If additional options are needed, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.</li>
<br />
<li><strong>Sex</strong>, <strong>Form</strong>, <strong>Developmental Stage</strong><br />
<em>All three of these fields work in the same manner</em>. Options can be added to
any of these drop-downs by selecting the add <img src="../../style/webImages/plusIcon.png" /> icon. 
This process is available only for those authorized through login permissions. (Only a
lead scientist and coordinator of group can add new options). If a scientist
needs additional options, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.
<br />
<br />
<img src="ManualImages/add_specimen_sex.jpg" />
<p>
Choose one of the options from the drop-down list for each of the 3 fields.
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
<li><strong>Type Status</strong> (Required)<br />
Choose from the drop-down list, the type status of the specimen. The type
status indicates the kind of nomenclatural type that a specimen represents. The Morpbbank
system can store any number of determinations and type designations for a specimen; contact the
<strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong> if some of your specimens
require more than a single type designation or determination.</li>
<br />
<li>Specimen <strong>Preparation Type</strong><br />
Enter the type of specimen preparation, if applicable. This is the
preparation of the whole specimen, before incorporation into the
collection. Examples include <strong>Pressed and Dried</strong> and <strong>70% ethanol</strong>.</li>
<br />
<li><strong>Number of Individuals</strong><br />
Enter the approximate number of individuals that were collected
/observed. This is the number of individuals in the lot or container
representing the specimen record.</li>
<br />
<li><strong>Determination Id/ Name</strong> (Required)<br />
Click the <img src="../../style/webImages/selectIcon.png" /> icon seen with the
Determination Id / Name field to open the Taxon Name Search screen. Traverse through the levels until
the appropriate scientific name is found. Then click the select
icon, the user will automatically be directed back to the Add
Specimen screen where the appropriate name and id number will be filled
in.
<br />
<img src="ManualImages/select_search_taxon_name.jpg" />
<br />
If a new taxon name needs to be added select the <strong>Add New Taxon</strong> button that is
visible from the family level. The <strong>Add Taxonomic Name</strong> screen will popup. (This option is
only available for authorized users.) For complete instructions on this process
see the <a href="<?echo $config->domain;?>About/Manual/addTaxonName.php">ITIS, Add Taxon Name</a> section of this manual.
</li>
<br />
<li><strong>Determined By</strong><br />
Enter the name(s) of person(s) who determined the taxonomic category of
the specimen. Add the names separated by a comma.</li>
<br />
<li><strong>Date Determined</strong><br />
Enter the date when the specimen was determined. Use the format: yyyy-mm-
dd. If the day is unknown enter yyyy-mm-00, or if the month is
unknown enter yyyy-00-00. The Date should be current or a past date.
</li>
<br />
<li><strong>Determination Notes</strong><br />
If the person who made the determination made any specific notes about the determination of the specimen, enter those notes here.
</li>
<br />
<li><strong>Institution Code</strong><br />
Enter the code for the institution to which the collection belongs.
</li>
<br />
<li><strong>Collection Code</strong><br />
Enter a unique alphanumeric value which identifies the collection to which
the specimen belongs.
</li>
<br />
<li><strong>Catalog Number</strong><br />
Enter a unique alphanumeric value which identifies the specimen record
within the collection. It is recommended that this value provides a key by
which the actual specimen can be identified. If a biological specimen
(individual organism) is represented by several collection items, for
instance representing various types of preparation, this value should
identify the individual collection item.
</li>
<br />
<li><strong>Previous Catalog Number</strong><br />
Enter a previous catalog number if the specimen was earlier identified by
another catalog number in the current catalog or at/in another
institution/catalog. A fully qualified catalog number is preceded by
Institution Code and Collection Code with a space separating each sub
element. Referencing a previous catalog number does not imply that a
record for the referenced item is or is not present in the corresponding
catalog, or even that the referenced catalog still exists.
</li>
<br />
<li><strong>Related Catalog Item</strong><br />
Enter a fully qualified identifier of a related catalog item (a reference to
another specimen). A fully qualified identifier consists of Institution Code,
Collection Code and Catalog Number, with a space separating each of the
three sub elements.
</li>
<br />
<li><strong>Relationship Type</strong><br />
Enter a string (named value) that specifies the relationship between the
specimen and the related catalog item. Example of possible values
include: "parasite of", "epiphyte on", "progeny of" etc.
</li>
<br />
<li><strong>Collection Number</strong><br />
Enter an identifying number (a string) which was applied to the specimen
at the time of collection/observation. This number links different
parts/preparation types of a single specimen and field notes with the
specimen.
</li>
<br />
<li><strong>Collector(s) Name(s)</strong><br />
Enter the name(s) of the collector(s) responsible for collection of the
specimen or taking the observation.
</li>
<br />
<li><strong>Date Collected</strong><br />
Enter the date when the specimen was collected (date when the collection
process began). The date format should be yyyy-mm-dd. If the day is
unknown enter yyyy-mm-00 or if the month is unknown enter yyyy-00-00.
The date entered must be a current or past date.
</li>
<br />
<li><strong>Locality</strong><br />
Click the <img src="../../style/webImages/selectIcon.png" /> icon seen with the
Location Id / Locality field to open the <strong>Locality</strong> search/selection screen. Use the Keyword
search to find the appropriate locality. Then click the select
icon and the user will automatically be directed back to the Add
Specimen screen where the appropriate Locality and id number will be filled
in. 
<p>To sort the list of localities, select the Sort By criteria from the drop down list(s).
The more criteria selected, (up to 3 levels) the more refined the sort will be.</p>
<img src="ManualImages/locality_from_add_specimen.jpg" />
<br />
If the desired locality/id is not on the Locality screen, click on the
Add New button to add a Locality. Once the fields are populated, click Submit
to Add the new Locality and the user will be prompted to click "Select" to auto-fill
the Locality field and may then continue with the Add Specimen screen.
</li>
<br />
<!--<div class="specialtext3">Selecting the information <img src="../../style/webImages/infoIcon.png" /> 
icon will display detailed information about that locality. Choosing the select icon will redirect back to the add
specimen page and the appropriate locality will be automatically filled in.
</div>-->
<li><strong>Notes</strong><br />
Enter additional text notes related to the specimen record. This is a good
place to add voucher label information (i.e. DNA, anatomical, etc.) or other
information such as the duration of the collection/trapping sessions noting
a range of dates the process took place.</li>
<br />
<li><strong>Contributor</strong> (Required)<br />
Select the name of the contributor (person having the authorization to
release the images) from the dropdown list. The contributor can be
different from the submitter (person entering the data). If you need to add
new entries to this list, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.
</li>
<br />
<li><strong>Add External Links</strong> to this record. For complete
instructions on providing external links refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External Links</font> to open this feature. See next: <br/>
</li>
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
instructions on providing external references refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External References</font> to open this feature. See next:<br/>
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
        <li>The database table storing this identifier requires the values be unique. If the identifier string entered is already in this table, the user needs to figure out a different prefix.</li>
        <li>For example, a user, Fred S Unstead, has a Specimen with ID=1234567 and puts his initials as the prefix for: <strong>FSU:1234567</strong></li>
        	<ul>
            <li>Florida State University (FSU) entered Specimen IDs with prefix=FSU + an identifier (1234567).</li>
            <li>Fred S Unstead needs to change his prefix in some way, for example: <strong>FSU-S:1234567</strong> (where the S is for Specimen).</li>
            </ul>
        <li>The external unique reference ID can be used in future uploads and for updates of current records in Morphbank.    
        </ul>
        </div>  
</ul>
</p>
<p>
When the <strong>Add Specimen </strong>form has been completed, <strong>Submit</strong> to complete
the add specimen process. A message will confirm that you have successfully
added a specimen. From this point the user can continue to add additional
specimens or return to the <strong>Upload (Submit)</strong> screen.
</p>
<div class="specialtext3">Note: When the <strong>Add Specimen</strong> screen is activated from the
 <strong>Add Image</strong> screen no message will be seen. Instead, the new
specimen will appear in the appropriate field on the <strong>Add Image</strong> upload
form.
</div>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/uploadSubmitView.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
