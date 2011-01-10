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
		<h1>External Linking - Internal Linking</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<p>Morphbank provides an option to link deposited images to any <strong>external link</strong> (link to a site outside Morphbank) and link any site back to Morphbank (<strong>internal link</strong>). 

<ul>
<li>Links may include other databases, NCBI accession numbers, location maps, publications, other Morphbank records, etc.
</li>
<li>Links may be included at the time of image deposition in the Excel spreadsheet or on the Morphbank Web site
or after the image data is deposited in Morphbank.
</li>
<li>For each link, a label must be provided describing the link. The label determines how the link will
 appear on the Morphbank Single Show page.
</li>
<li>Links may be associated with any Morphbank table. So, they can appear in the Specimen record, Image record,
 Locality record, View record, etc.
</li>
<li>Published Morphbank images may be linked to any Web site or database for educational and non-profit purposes (see
Morphbank copyright information <a href="<?echo $config->domain;?>About/Copyright/" target="_blank">http://www.morphbank.net/About/Copyright/</a>).</li>
</ul>
<hr align="left" width="650" height="5" color="#AAB0D0" />
<h2>External Links - Adding to any Morphbank record</h2>
<p> Morphbank Contributors using <a href="<?echo $config->domain;?>About/Manual/submit.php"><em>mass upload</em></a> methods may add these external links during upload. Information needed includes the:</p>
<ul>
<li><strong>URL</strong> itself in http:// format</li>
<li><strong>Label</strong> the Morphbank User clicks on to go the URL</li>
<li>choice of <strong>type of URL</strong> (from drop-down list, examples include: GenBank, Institution, etc ...)</li>
</ul>
 
 <p>When logged-in and browsing, a Morphbank user can add external links to objects they are uploading or to objects they have already contributed or submitted.
 </p>
 
<ol>
<li>  
  To supply external links for images, specimens, views, locations and publications already deposited in Morphbank, browse
  for the desired object and click on the <img src="../../style/webImages/edit-trans.png" /> edit icon. 
  	<ul><li>For images and other objects 
  already deposited in Morphbank the label and link information will need to be associated with the actual Morphbank record id. This id is unique
  to each record and is located at the top of each Morphbank record.</li>
  </ul>
<br />
<img src="ManualImages/browse_image_fish.png" /></li>
<li>
<a name="addLink"></a>
After clicking on the <img src="../../style/webImages/edit-trans.png" /> edit icon, go to the
bottom of the Edit screen and click on the blue highlighted text "Add External Links." This will
bring up the external links submit/edit form as seen below. A Contributor/Submitter will supply external links in this
same place during the submitting process, if submitting through the web interface.</li>
<img align="left" src="ManualImages/add_links.png" vspace="20"/><img src="ManualImages/add_external_links.png" hspace="10" vspace="5"/><br />
<br />
<li><strong>Type</strong>: Select from the drop-down list the appropriate link type. Selections
might include, Institution, GenBank, Publication, Google Maps or Other</li>

<li><strong>Label</strong>: Type in a short, descriptive label. This label is the name of the link
that will appear on the Morphbank record page. Appropriate labels would
include the name of the institution that the link is going back to or the name of
the gene region that is linking to an accession number. Examples: Google
Map, CO1, AMNH. Make sure the label is brief.</li>

<li>
<strong>URL</strong>: Enter or cut and paste the URL where the link is located.</li>

<li>
<strong>Description</strong>: Free text entry about the link. Example: comment about the
institution that is being linked to.</li>

<li>Click Submit.</li>

<li>To add more than one <strong>external link</strong>, click on the plus sign. The 
minus sign will remove any link not needed.</li>
</ol>

<div class="specialtext3">
Note: A Morphbank Contributor or Submitter can add external links from anywhere the <img src="../../style/webImages/edit-trans.png" /> icon appears in any <a href="<?echo $config->domain;?>About/Manual/myManager.php" target="_blank">My Manager</a> tab. It's done
exactly as described above once the edit icon is clicked. Also links can be edited or deleted as needed after a record is published.
</div>

<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Image Record Show - sample External Links</h3>
<br />
<a href="<?echo $config->domain;?>134312"><img src="ManualImages/external_link_sample.png" vspace="20" hspace="30"  /></a>
<br />
<hr align="left" width="650" height="5" color="#AAB0D0" />
<h2>Internal Links - Link Back to Morphbank From an Outside Source</h2>
<ul>
<li>The Morphbank Id for each record is unique.  These records with unique ids include: collections, specimens, images, views, locations, 
annotations, publications, character collections and OTUs.  Thus unique links can be formed in this manner to any of these Morphbank records.
</li>
<li>All links to Morphbank records are formed the same way with<br />
<b>http://www.morphbank.net/?id=</b> as the base of the URL + the Morphbank ID for that object (in this case 64122)
</li>
<li>Put these links in web sites, journal articles, online documents, emails, tweets, etc.</li>
<li>An entire link to one specific specimen record may look like this: 
<a href="<?echo $config->domain;?>Show/?id=64122" target="_blank">http://www.morphbank.net/Show/?id=64122</a>.
</li>
</ul>

<div class="specialtext3">
The source and copyright holder must be cited when images are used from
Morphbank. To satisfy this requirement, it is highly encouraged and beneficial to
link images back to the Morphbank record that contains this information. For
example: Assuming the above image has been linked to a Web site, this image
should be linked back to the Morphbank record.
</div>
<h3>Internal Link Options</h3>
<p>Since each object in Morphbank has a <strong>unique identifier</strong> in Morphbank, it is possible to build a URL to point to that object (i.e. return that object to the user). Objects include: Contributor, Submitter, Group, Image, Specimen, View, Locality, Publication, Collection and Annotation.The following table shows how a URL is built to point to various objects in Morphbank.</p>
<h3>Internal Link Samples</h3>
<div class="specialtext2">
<table cellpadding="2" cellspacing="2" border="1" hspace="30">
<tr><th align="left">Base URL</th><th align="left">Morphbank Object Id</th><th align="left">Actual Internal Link to Morphbank</th><th align="left">What the Link Returns</th></tr>
<tr><td>http://www.morphbank.net</td><td align="center">478760</td><td><a href="<?echo $config->domain;?>?id=478760">http://www.morphbank.net/?id=478760</a></td><td>Morphbank Image Metadata + 400px image</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">228716</td><td><a href="<?echo $config->domain;?>?id=228716">http://www.morphbank.net/?id=228716</a></td><td>Morphbank Specimen Metadata + 400px image</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">575098</td><td><a href="<?echo $config->domain;?>?id=575098">http://www.morphbank.net/?id=575098</a></td><td>Morphbank Collection</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">464413</td><td><a href="<?echo $config->domain;?>?id=464413">http://www.morphbank.net/?id=464413</a></td><td>Morphbank Annotation</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435">http://www.morphbank.net/?id=579435</a></td><td>Morphbank Image Metadata + 400px image</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=jpeg">http://www.morphbank.net/?id=579435&amp;imgType=jpeg</a></td><td>Morphbank Image jpeg</td></tr>
<tr><td>http://www.morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=thumb">http://www.morphbank.net/?id=579435&amp;imgType=thumb</a></td><td>Morphbank thumbnail</td></tr>
<tr><td height="32">http://www.morphbank.net</td>
<td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=jpeg&amp;imgSize=500">http://www.morphbank.net/?id=579435&amp;imgType=jpeg&amp;imgSize=500</a></td><td>Morphbank Image with user-specified width</td></tr>
</table>
</div>

<h2>Sample Internal Link</h2>
<p>If the Contributor wishes to show the Image and Metadata for the Image, this is the Morphbank "Show" page for the Image Record.<br />
The URL looks like: <a href="<?echo $config->domain;?>?id=65847" target="_blank">http://www.morphbank.net/?id=65847</a><br />
and when clicked, opens the following page in Morphbank.</p>
<img src="ManualImages/image_record_link_back.png" hspace="30"/>
<br />
<br />
Linking back to the Morphbank record provides a valuable increase in available information associated with the image
to include data about the locality, collector, specimen, contributor, etc. This increases the value of the image to
a webpage. For those interested in using images deposited on Morphbank for online keys and Web sites, please contact Morphbank admin
<strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>
 for options and suggestions.
<br />
<br />
Note:
Morphbank is an Entrez LinkOut (<a href="http://www.ncbi.nlm.nih.gov/entrez/linkout/" target="_blank">
http://www.ncbi.nlm.nih.gov/entrez/linkout/</a>) provider for the Nucleotide
(<a href="http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?db=Nucleotide" target="_blank">
http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?db=Nucleotide</a>) and Taxonomy
(<a href="http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?db=Taxonomy" target="_blank">
http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?db=Taxonomy</a>) databases.
Morphbank will provide NCBI with the accession numbers and ids that are sent for the purpose of linking GenBank back to images of voucher
specimens on Morphbank. Morphbank also provides links for NCBI taxonomy using images deposited in Morphbank. These links are updated
periodically. If only GenBank accession numbers and Morphbank ids are involved, then the full URL does not need to be provided, but only the
accession number. Data for external links may also be accepted in tab-delimited or comma-delimited text files.
<br /> 

<hr align="left" width="650" height="5" color="#AAB0D0" />
<h2>Image Ids - A Special Case</h2>
<h3>Using Image Ids to Embed an Image in an external Web site</h3>
<br />
<img src="ManualImages/browse_image_fish.png" />
<br />
<ul>
<li>Images in Morphbank may also be linked to other Web sites, online keys, species pages, etc. using only the path
that links to the .jpeg of that image (i.e. <a href="http://www.morphbank.net/?id=133776&imgType=jpeg" target="_blank">
http://www.morphbank.net/?id=133776&imgType=jpeg</a>). This
path can easily be found by clicking on the .jpg icon from a search results page, in the navigation bar of that page.
</li>
<li>All links to embedded images are formed the same way with<br />
<b>http://www.morphbank.net/?id=</b> as the base of the URL + the Morphbank ID for that object (in this case 133776) 
+ &imgType=jpeg (specification of image type)
</li>
<li>Image sizing:
The same image is found on Morphbank in multiple sizes that may be useful for linking.
These sizes include a thumbnail (95X70), medium jpg (400X300), large jpeg, large tiff and a user-defined size. These sizes may be found by changing the path
name. If: <br /><a href="http://www.morphbank.net/?id=133776&imgType=jpeg" target="_blank">
http://www.morphbank.net/?id=133776&imgType=jpeg</a> is the full size jpeg image then<br />
<a href="http://www.morphbank.net/?id=133776&imgType=tiff" target="_blank">
http://www.morphbank.net/?id=133776&imgType=tiff</a> is the tiff,
<br /> 
<a href="http://www.morphbank.net/?id=133776&imgType=jpg" target="_blank">http://www.morphbank.net/?id=133776&imgType=jpg</a> is the medium
sized image and<br />
<a href="http://www.morphbank.net/?id=133776&imgType=thumb" target="_blank">http://www.morphbank.net/?id=133776&imgType=thumb</a> is the
thumbnail image.
<br/>
<a href="http://www.morphbank.net/?id=133776&imgType=jpg&imgSize=500" target="_blank">http://www.morphbank.net/?id=133776&imgType=jpg&imgSize=500</a> is the
jpg image where a user desires an image with a defined width, in this case, 500 pixels. The user may insert the number of pixels to constrain the image to the size that works best for their purpose.
</li>
</ul>

<div class="specialtext3">
A simple example: <br /><br /><img src="http://www.morphbank.net/?id=133776&imgType=thumb" /> is embedded in this manual's html 
using an img src tag:<br /><b> &lt;img src="http://www.morphbank.net/?id=133776&imgType=thumb" /&gt; </b>
</div>
</p>
<hr align="left" width="650" height="5" color="#AAB0D0" />
<h3>Getting Morphbank Ids to Build URLs</h3>
<p>Via <a href="http://services.morphbank.net/mb3/">Services</a>, users retrieve Morphbank Ids for various objects. Using these ids, Contributors create urls and may also build other websites that utilize web services, pulling images via their ids. See <a href="<?echo $config->domain;?>About/Manual/services.php">Morphbank Web Services</a> in this online user manual for more details.</p>

<br />
<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/show.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
			</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
