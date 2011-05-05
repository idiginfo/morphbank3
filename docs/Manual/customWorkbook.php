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
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Morphbank Custom Worksheet Instructions</h1>

<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<p>To use this workbook for upload to Morphbank, a user <em>must</em> have <strong>unique identifiers</strong> for their Specimens and Images. Unique identifiers are optional for Views and just what a unique identifier is and how to construct one is included in these instructions. If the data to be uploaded does not have
unique identifiers, use our options <a href="<?echo $config->domain;?>docs/mb3a.xls">Data Entry Workbook - Animalia</a> or <a href="<?echo $config->domain;?>docs/mb3p.xls">Data Entry Workbook - Plantae.</a> Note that these workbooks are located for easy download 
from <a href="<?echo $config->domain;?>Help/Documents/"> Documents</a> and are easily modified to suit other kingdoms.</p>

<blockquote>
 HINT: Before using this, or any mass upload method, please take the time to upload 2 or 3 images via the web-interface at http://www.morphbank.net.
    Familiarity results in a much faster learning curve and less confusion when it comes to filling out the various forms found in a workbook or in getting the data mapped to Morphbank's XML schema.  </blockquote>

<h3>Custom Workbook Considerations</h3>
<ul>
<li>Users need only supply minimal data with this upload method.</li>
<li>Very few required fields.</li>
<li>Fields not utilized can be deleted from Data sheet to simplify filling out the workbook.</li>
<li>Drop-downs are easily added where needed / appropriate (ask Morphbank if assistance needed to learn to do this).</li>
<li>User-supplied data that does not map to any Morphbank field may be uploaded and stored in Morphbank as a <strong>userProperty</strong>. This is accomplished with a <em>triple-store</em> meaning
the name of the field, the value for that field, and the object in Morphbank where that field and value are to appear are stored in one table in Morphbank.</li>
	<ul>
    <li>Note that while this type of data displays in Morphbank, it is not findable via the Keyword search.</li>
    </ul>
<li>To use this workbook, the contributor must have unique identifiers (uids) for every image and specimen and preferably, every view although view uids are optional.</li>
<li>Taxon Names cannot be added with this workbook at this time. Users must add names via one of 3 possiblities:
  <br />
  <br />
  <ol>
    <li>For a few names, using the web interface is most efficient. Names can be added via the web from <em>Sub-order</em> or lower rank.</li>
    <li>If many names, at <em>rank Genus or lower</em> need adding to Morphbank, please use the <strong>Specimen Taxon Data worksheet</strong> in the <a href="<?echo $config->domain;?>docs/mb3a.xls">Data Entry Workbook - Animalia</a> or <a href="<?echo $config->domain;?>docs/mb3p.xls">Data Entry Workbook - Plantae.</a></li>
    <li>Lastly, if names need adding, including names with <em>rank above Sub-order</em> there is a simple form to fill out that uploads via php. Contact <strong>mbadmin <font color=blue>at</font> scs <font color=blue>dot</font> fsu <font color=blue>dot</font> edu</strong> for the form.</li>
  </ol>
</li>
</ul> 

<p>This Excel workbook contains 5 sheets: <strong>Data</strong>, <strong>UserProperties</strong>, <strong>ContributorInfo</strong>, <strong>Field Help</strong> and <strong>Drop Downs</strong></p>

<ul>
  <li><strong>Data (required)</strong>: All available data fields in Morphbank are present on this sheet. <strong>Required fields</strong> are indicated in the header of the column by a dotted-fill pattern and noted in the pop-up comment in the header of each column. Note <strong>Data sheet</strong> is divided into 4 parts (by color) corresponding to objects in Morphbank: <strong>image, specimen, view, locality</strong>. If a field column is <strong>not required</strong> by Morphbank and <strong>not used</strong> by the contributor, that column can be deleted from the sheet entirely to make data entry easier. This does not affect upload.</li>
  <br />
  <li><strong>UserProperties</strong> (optional): This sheet used only if the contributor submits data to Morphbank that does not fit into any existing Morphbank fields. If &lt;userProperty&gt; fields are not needed, this entire sheet can be deleted from the workbook.</li>
    <br />
  <li><strong>ContributorInfo (required</strong>): Enter information about the:</li>
    <ol>
    <li>Morphbank User (aka Contributor) Name and Id</li>
    <li>Morphbank Submitter Name and Id</li>
    <li>Morphbank Group Name and Id</li>
    <li>Date-to-publish the objects</li>
    <li>Creative Commons Copyright for the Images</li>
    </ol>
       <br />
  <li><strong>Field Help</strong>: On this sheet, each field, its data type and field length are defined. Example data is provided along with some hints on how to simplify the process for some fields.</li>
    <br />
  <li><strong>Drop Downs:</strong> This sheet makes the drop-downs that appear on the<strong> Data sheet</strong>. Users can add needed values to most columns. Some columns use a fixed vocabulary and so cannot be altered (without contacting Morphbank).</li>
    <br />
  <li><strong>Order to Fill out Sheets</strong>: Start with <strong>ContributorInfo</strong> then <strong>Data</strong>, and populating <strong>UserProperties sheet</strong> if needed. Add values to fields on the <strong>Drop Downs</strong> sheet as needed. Note that if many items are added to a given drop-down, the contributor will need to use Excel to <strong>edit via Name Manager in Excel</strong> in order for the new items to appear in the drop-downs on the <strong>Data sheet.</strong></li>
</ul>

<div class="specialtext3">
Suggestion: download and open the <a href="<?echo $config->domain;?>docs/customWorkbook.xls">Morphbank Custom Workbook</a> to look at while reading this documentation.</div>

<ol>
<li>Before filling out and submitting this workbook to Morphbank, check all the needed taxon names with names in the Morphbank database. There are two ways to do this.</li>
<ol>
    <li>Login to Morphbank, go to <em>HeaderMenu > Tools > Submit > Taxon Name</em>. Here a user can access the <em>Taxon Name Search</em> to see if a name is in Morphbank or not, or is in Morphbank in > 1 location. Any names not in Morphbank need adding before the upload using the custom workbook.</li>
    <br />
	<ol>
	<li>Names can be added via the web-interface from Sub-Order and lower ranks. Go to HeaderMenu > Tools > Submit > Taxon 	Name to 	start. Try the <a href="<?echo $config->domain;?>About/Manual/addTaxonName.php" target="_blank">Online User Manual - Add Taxon Name</a> for help with this process.	</li>
	<li>If many names need adding or if many names need adding <em>at the rank of Order or higher</em>, contact Morphbank <strong>mbadmin <font color=blue>at</font> scs <font color=blue>dot</font> fsu <font color=blue>dot</font> edu</strong> as we have another strategy for this situation.	</li>
	<li>If a taxon name is in Morphbank more than one time, each occurrence has a unique taxonomic serial number (tsn). The user will need to make a note of the tsn that represents the taxon name placement preferred and put that tsn in the custom workbook <em>Determination TSN</em> column on the <strong>Data sheet.</strong></li>
    <br />
    </ol>
    <li>Using <a href="<?echo $config->domain;?>Help/nameMatch">Name Query</a>, a list of taxon names can be searched all-at-once and a list of tsns returned. This list can be pasted directly into the <strong>Data sheet </strong><em>Determination TSN</em> column.</li>
</ol>
<br />
<br />
<h3>Mapping and User Properties</h3>	
<li>Next, review the fields present in the workbook. Most of them are from the DarwinCore2 Schema (v1.2). Each Contributor will need to map their data to these fields to see what matches and what does not. Then, decide which fields must go into Morphbank and which really do not need to be stored here. If extra fields, that Morphbank does not have, must go into Morphbank, they must be defined by the user. These are <strong>User Properties.</strong> If at all possible, try to find a field in one of the following Schemas that fits the dataset in question. Someone at Morphbank can help with this, just ask.</li>
	<ul>
	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_core.xsd" target=_blank>Darwin Core XML Schema</a></li>
	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_curatorial.xsd" target=_blank>Curatorial Extension to the Darwin Core</a></li>
	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_geospatial.xsd" target=_blank>Geospatial Extension to the Darwin Core</a></li>
</ul>
<div class="specialtext2">
	<h3>Entering a <em>User Property</em> in the Workbook - an example</h3>
	<ul>
	<li>Let's say the <em>userProperty</em> = Size. The Contributor has specific size information.</li>
	<li>To what object in Morphbank (image, specimen, locality, view) does the size information relate? For the
    sake of this example, the Contributor obtained the size measurement by directly measuring the <em>specimen</em>.	</li>
	<li>In the Workbook in the <strong>Data sheet</strong>, find the <em>Specimen</em> section and the <em>userProperty</em> column in that section.	</li>
    <li>In the column heading row, replace the <em>&lt;userProperty&gt;</em> text with new column header <em>Size</em>.  	</li>
    <li>Under this new column heading, place the Size values for each Specimen.</li>
    <li>Next, go to Sheet 2. Enter the name of the userProperty in the first column (in this case <em>Size</em>), under the column 
    heading &lt;<em>userProperty</em>&gt;. In the next column (Associated MB Object), choose <em>Specimen</em>
    from the drop-down.
    <ul>
      <li>Now, the &lt;userProperty&gt; is defined as Size and this data will appear in Morphbank with the Specimen object..</li>
    </ul>
    </li>
    <li>If another &lt;<em>userProperty&gt;</em> is needed for <em>Specimen</em>, insert another column next to the 
    Size column on Sheet 1, and repeat the above steps.</li>
    </ul>
</div>

</li>
<li>Now, every item to go into Morphbank fits into one of the columns in the workbook. The columns labeled <userProperty> 
  are for the data items where the Contributor finds a suitable datafield in one of the above Schemas, or comes up with a unique one. It is srongly suggested that users attempt to find a field that matches in one of the above defined Schema. It greatly facilitates sharing data between databases stored on different computers, aka interoperability.</li>
  <br />
<li>In addition to the <strong>Field Help</strong> sheet, see the Morphbank Online User Manual for Morphbank Field descriptions for more about what type of data goes into which field and what the format of the data needs to be for each of those fields.
	<br />
    <br />
    <ul>
	<li><a href="<?echo $config->domain;?>About/Manual/uploadSubmitImage.php" target="_blank">Image Fields</a></li>
	<li><a href="<?echo $config->domain;?>About/Manual/uploadSubmitSpecimen.php" target="_blank">Specimen Fields</a></li>
    <li><a href="<?echo $config->domain;?>About/Manual/uploadSubmitView.php" target="_blank">View Fields</a></li>
    <li><a href="<?echo $config->domain;?>About/Manual/uploadSubmitLocality.php" target="_blank">Locality Fields</a></li>
	</ul>
    <br />
<li><b>External Unique Identifiers (uid):</b> What they are and how to fill out the columns in the workbook.

  <div class="specialtext3">
<h3>Morphbank encourages UIDs</h3>
<br/>
If the provider (Contributor to Morphbank) maintains their own unique identifiers for their objects, these can be stored in Morphbank. With this identifier, the object is easily shared between systems. They also make it possible in the future for users to update their own data in Morphbank utilizing their own unique identifiers. In this workbook, a provider may supply Morphbank with unique ids for 3 Morphbank objects, image, specimen & view. A unique identifier must be unique within Morphbank itself. An example of a unique identifier might be to concatenate an organization name + an image file name. For this to work, the provider must control the naming conventions for images they supply to Morphbank so that no images will have the same combination.</div>
<ul>
<br />
<li>In the workbook, the user is asked to populate two columns for any given uid. Provide the identifer in the External id column, example:<b>0001568</b>. In the Prefix column, provide what is to be concatenated with the id to make it unique. Example:<b>CToL-I:</b> Now the uid in Morphbank will appear as <b>CToL-I:0001568</b>.</li>
<br />
<li>It can be very simple to create these.
  Here are some ideas and guidelines.
  <br />
   <br />
  <ul>
    <li>Usually, an acronym for the institution/collection or person is suitable for the Prefix. </li>
    <li>The identifier for the image can be the name of the image, or a unique number given to that image.
      <ul>
        <li>note the Contributor must keep track of these on their end.</li>
        </ul>
    <li>The identifier for a specimen is usually the <strong>catalognumber</strong> or it might be the <strong>collector's identifier</strong> (the number given to that item when it was collected by a particular individual).</li>       
    <li>Keep in mind that Darwin Core has a simple strategy for creating unique identifiers that consists of concatenating the <em>institution code + collectioncode +</em><em> catalognumber</em>. So, if the data is coming from a formal collection, this can be used. In Morphbank, the <em>institution code + collection code</em> would make up the Prefix. The <em>catalognumber </em>goes in the External id column.
      <ul>
        <li>An example:
          <ul>
            <li> <strong>prefix</strong> FSU:FSU + <strong>External id</strong> 000126543</li>
            <li>concatenated in Morphbank, the <strong>uid</strong> = <strong>FSU:FSU:000126543</strong></li>
            <li>it could be read as Florida State University, FSU = Herbarium Acronym from Index Herbariorum, 000126543 is the catalog number of a given specimen in that collection.</li>
          </ul>
        </li>
        </ul>
    </li>
    </ul>
</ul>

<br/>
<li>Fields not needed in the Workbook can be deleted -- for clarity and ease of use of the workbook.</li>
<br />
<li>For any columns that a User decides to populate - <i>Do Not Change</i> the column headings (except for <em>userProperty</em>).</li>
<br />
<li>In addition to the <strong>Field Help</strong> sheet, see the <a href="<?echo $config->domain;?>docs/mbTablesDescription.pdf"> MB Table Descriptions</a> for even more about Morphbank database tables and field types  (varchar, text, field length restrictions).</li>
<br />
<li>Send the completed workbook to Morphbank along with the images. The workbook can be sent via e-mail, cd/dvd, or ftp. Send images via cd/dvd, ftp or mail a hard drive with the images.</li>
<br />
<li>The file will be validated to check the contents. This validation looks at field content match (text fields contain text, numeric fields contain numerics) and overall data. Morphbank will return the file to the sender with questions if need be, otherwise it will be uploaded. Always keep a copy of the workbook.</li>
</ol>

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/index.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	

