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
		<h1>Morphbank Terms and Definitions</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<dl>
<dt><strong>ABCD</strong></dt> 
<dd>Stands for <a href="http://www.bgbm.org/TDWG/CODATA/Schema/" title="ABCD Schema">Access to Biological 
Collection Data</a>. A naming schema. Morphbank schema most closely matches Darwin Core. Follow this link to an <a href="http://rs.tdwg.org/dwc/terms/history/dwctoabcd/index.htm" title="ABCD - Darwin Core Map">ABCD - Darwin Core Map.</a> Here is a schema for <a href="<?echo $domainName;?>About/Manual/dwcabcdmb.php">Morphbank - Darwin Core - ABCD</a></dd>
<dt><strong>Administrator</strong></dt>
<dd>There are very few individuals given administrator privileges. An administrator has complete access to all 
data and in addition can add/modify and/delete news, base or master tables. Only someone with administrative privileges can 
add new users and create groups for which there is no associated taxon. Those with administrative privileges have all rights 
in all groups and are responsible for managing the entire Morphbank system.
</dd>
<dt><strong>Angle</strong></dt>
<dd>In <a href="<?echo $domainName;?>About/Manual/uploadSubmitView.php" title="What is a Morphbank View &amp; How to Submit one to Morphbank">Views</a> section, the location of the camera with respect to the specimen for photographing
</dd>
<dt><strong>API</strong></dt>
<dd>The <a href="http://morphbank.net/schema/API1.html" title="Current API for Morphbank web services">application programming interface (API)</a> for <a href="http://services.morphbank.net/mb3/" title="Morphbank Web Services">services.morphbank.net/mb3/</a> describes how the query is put together and reveals what search combinations are possible. Currently contributors to Morphbank may use <a href="http://services.morphbank.net/mb3/">services.morphbank.net/mb3/</a> to perform searches on the Morphbank database. In the future, it will be possible to Insert (Put) data into Morphbank as well as Update (Push) data into Morphbank via <em>web services</em>. For those wondering just what <em>restful services</em> are, try the humorous introduction at <a href="http://tomayko.com/writings/rest-to-my-wife" title="RESTful services explained">How I Explained REST to My Wife.</a>
</dd>
<dt><strong>Basis of Record</strong></dt>
<dd>At the time of collection, the specimen was categorized as an observation, a living organization, 
a specimen, a germplasm/seed. The list of
options is based on the Darwin Core standard <a href="http://rs.tdwg.org/dwc/terms/history/versions/index.htm" title="Darwin Core Standard">http://darwincore.calacademy.org/</a>. Basis of Record is defined as: A descriptive term indicating whether the record represents an object or observation. Another definition reads: The nature of the sample.
</dd>
<dt><strong>Collection Name</strong></dt>
<dd>Name given to a Morphbank Collection by the collection's creator
</dd>
<dt><strong><a href="<?echo $domainName;?>About/Manual/myManagerCollectionsDefined.php" title="What is a Morphbank Collection?">Collections</a></strong></dt>
<dd>Groups of specimen images or any objects in Morphbank assembled from the Morphbank database by Morphbank members for the purpose 
of manipulating, viewing, or storing for future use.
</dd>
<dt><strong><a href="<?echo $domainName;?>About/Manual/characterCollections.php" title="What is a Morpbhbank Character Collection?" >Character Collection</a></strong></dt>
<dd>A kind of Morphbank collection that contains tools to define a character and its states.
</dd>
<dt><strong>Collector Name</strong></dt>
<dd>Person(s) who collected the specimen that is referenced in Morphbank.
</dd>
<dt><strong>Contributor</strong></dt>
<dd>Person having the authority to release the images for publication into Morphbank.
</dd>
<dt><strong>Coordinator</strong></dt>
<dd>Associated with <a href="<?echo $domainName;?>About/Manual/userPrivileges.php" title="More about Morphbank Roles, Groups, Coordinators, ...">roles</a> within a <a href="<?echo $domainName;?>About/Manual/selectGroup.php" title="How to Select a Group">Morphbank Group</a>, a Coordinator has the same privileges as Lead Scientist 
and each group may only have one Coordinator. In order to be assigned as a Group Coordinator, you 
must have lead scientist privileges for that group or have been assigned by the Morphbank administration. 
A Coordinator can add / remove group members, change a user's role in the group, as well as request spin-off 
groups to be developed with assigned Coordinators. The Coordinator can appoint another Lead Scientist in the 
group as a Coordinator. Only Coordinators have access to the group module, other group members do not.
</dd>
<dt><strong>Creative Commons</strong></dt>
<dd>From their web site, <a href="http://creativecommons.org/about/">Creative Commons</a> is a nonprofit corporation dedicated to making it easier for people to share and build upon the work of others, consistent with the rules of copyright. They provide free licenses in clear language. Morphbank's policy regarding public and private images stresses that all images must be uploaded with a <a href=" http://creativecommons.org/licenses/by-nc-sa/3.0/us/">Creative Commons License 3.0 (BY-NC-SA)</a> or <em>less-restrictive</em> copyright. To select a less restrictive copyright, or public domain, please visit <a href="http://creativecommons.org/choose/">Creative Commons - choose a license.</a>
</dd>
<dt><strong><a href="http://rs.tdwg.org/dwc/terms/history/versions/index.htm" title="What is Darwin Core?">Darwin Core</a></strong></dt>
<dd> The Darwin Core (sometimes abbreviated as <strong>dwc</strong>) is a standard set of field
definitions designed to facilitate collection, integration and exchange of natural history specimen information.
Morphbank uses many of the Darwin Core-defined fields from Darwin Core 1.2 (Classic) and Darwin Core 1.4 (Draft Standard).
</dd>
<dt><strong>Date collected</strong></dt>
<dd> The date the specimen (or sample) was collected.
</dd>
<dt><strong><a href="<?echo $domainName;?>About/HowToContribute/" title="More information about this process">Delivered uploading</a></strong></dt>
<dd> Morphbank offers a service called delivered uploading. We provide an already prepared 
Excel Data Entry Workbook and the corresponding user's manual. The contributors can deliver to Morphbank 
a CD or DVD containing images and an Excel<sup>&copy;</sup> Data Entry Workbook populated with information ready for upload.
</dd>
<dt><strong>Determination</strong></dt>
<dd>The taxon name applied to the specimen / sample. From Darwin Core 1.4: The taxon name (with date and authorship information if applicable) of the lowest level taxonomic rank that can be applied.
</dd>
<dt><strong>Developmental stage</strong></dt>
<dd> Growth phase of specimen. Current Darwin Core term for this is <strong>Life Stage</strong>: the age class or life stage of the biological individual represented by the sample. Examples: "egg", "eft", "juvenile", "adult". For insects, relevant values might be "larva", "pupa", 
</dd>
<dt><strong>Download</strong></dt>
<dd>The user receives data from a remote computer. 	 
</dd>
<dt><strong><a href="<?echo $domainName;?>About/Manual/externalLink.php" title="How to guide for URLs to &amp; from Morphbank Objects">External Links</a></strong></dt>
<dd>References the ability of the Morphbank database to store URL website links associated 
Morphbank objects. 	 
</dd>
<dt><strong>Fair Use Web Site</strong></dt>
<dd>The images in Morphbank that are not password protected can be used for private, 
education, research or other non-commercial purposes for free, provided that the source and the copyright 
holder are cited. 	 
</dd>
<dt><strong>Form</strong></dt>
<dd>Choose a description of the morphotype of the specimen (emergent, parthenogenetic, queen, worker,...).
Use "Indeterminate" if you do not wish to apply a specific morphotype designation to the specimen. In taxonomy, 
a morphotype is a specimen chosen to illustrate a morphological variation within a species population.  	 
</dd>
<dt><strong>Group id, User id, Specimen id, View id, Locality id, Image id, Publication id, Annotation id</strong></dt>
<dd>Morphbank-issued unique identifying numbers</dd>
<dt><strong>Groups</strong></dt>
<dd>Groups are comprised of users of the Morphbank system that share a common interest in a specific 
taxonomic area.
</dd>
<dt><strong>Guest</strong></dt>
<dd>user role that has read only access privilege in the group where they have the "Guest" role.
</dd>
<dt><strong>GUI</strong></dt>
<dd>"Pronounced gooie" - stands for globally unique identifier (or graphical user interface).
</dd>
<dt><strong>GUID</strong></dt>
<dd>"Pronounced goo id" - stands for globally unique identifier.
</dd>
<dt><strong>Imaging</strong></dt>
<dd> Technique used to capture photo of specimen such as auto montage, transmitted light; bright
field, etc.
</dd>
<dt><strong>ITIS</strong></dt>
<dd> <a href="http://www.itis.gov/">Integrated Taxonomic Information System</a>: database maintained by the US Department of Agriculture and
used by Morphbank as the main taxonomic name server.
</dd>
<dt><strong>Jpg (jpeg), .tif (tiff)</strong></dt>
<dd>  <a href="http://www.jpeg.org/">Joint Photographic Experts Group</a> (pronounced jaypeg), tagged image file format -- Morphbank-accepted file formats for images.
</dd>
<dt><strong>Lead Scientist</strong></dt>
<dd>  Same privileges as scientist but on all objects owned by the group to which the user belongs. A lead
scientist can also be a coordinator or group manager and therefore manage users and their permissions in a
group. For now, a lead scientist sends a request to the Morphbank team for creation of a group.
</dd>
<dt><strong>Locality</strong></dt>
<dd>Detailed information about where a specimen was collected or observed.
</dd>
<dt><strong>Login</strong></dt>
<dd>  Enter in a Morphbank issued user id and password for the purpose of adding /modifying or viewing restricted
data.
</dd>
<dt><strong>LSID</strong></dt>
<dd> Life Science Identifiers -- the GUID widely used identification scheme for the Life Science domain.
</dd>
<dt><strong>Modified Darwin Core Standard</strong></dt>
<dd>  A naming standard currently used by Morphbank.
</dd>
<dt><strong>Morphbank</strong></dt>
<dd>Open web repository of images serving the biological research community.
</dd>
<dt><strong>My Manager</strong></dt>
<dd>Morphbank's user-interface giving users easy access to and control over their objects in Morphbank.
</dd>
<dt><strong>NCBI accession numbers</strong></dt>
<dd> <a href="http://www.ncbi.nlm.nih.gov/">National Center for Biotechnology Information</a> accession numbers
</dd>
<dt><strong>Object</strong></dt>
<dd> Referring to an image, specimen, locality, collection, annotation, etc.
</dd>
<dt><strong>OTU</strong></dt>
<dd> Operational Taxonomic Units are representations of the organisms being compared.  Units might include specimen(s) or taxon name(s).
</dd>
<dt><strong>Photographer</strong></dt>
<dd> The name of the person who took the image uploaded to Morphbank.</dd> 
<!--<dt><strong>Primary TSN</strong></dt>
<dd> Morphbank issued taxonomic serial number identifies the user's specific area of expertise.
</dd>
<dt><strong>Privilege TSN</strong></dt>
<dd> Morphbank-issued taxonomic serial number representing the highest Taxonomic Name for which a Morphbank member has expertise. It is primarily used in identifying which groups for which they may have membership.
</dd>
-->
<dt><strong>Publication</strong></dt>
<dd> Referring to any publication entered into the Morphbank system that may be related to other objects in Morphbank such as an image, specimen, locality, collection, taxon name etc. 
</dd>
<dt><strong>Published</strong></dt>
<dd> Referring to Morphbank related objects such as an image, specimen, locality, collection, etc. When an object is released in Morphbank for worldwide viewing.
</dd>
<dt><strong>Record</strong></dt>
<dd> Referring to an image, specimen, locality, collection, annotation, etc. Any Morphbank object.
</dd>
<dt><strong>Roles</strong></dt>
<dd> Categories of users within Morphbank groups such as guest, scientist, lead scientist, coordinator, and administrator. Roles are used to determined privileges that a user has within a Morphbank group. 
</dd>
<dt><strong>Scientist</strong></dt>
<dd> User role that has the authorization to add/modify/delete Specimen, Image, View, and Locality as well as annotate released images within their taxon or images not released and owned by the group they belong to.
</dd>
<dt><strong>Search</strong></dt>
<dd>Find specific data through queries using keywords or id numbers
</dd>
<dt><strong>Secondary TSN</strong></dt>
<dd> Morphbank-issued taxonomic serial number representing the area in which the user has an alternate area of expert knowledge.
</dd>
<dt><strong>Sex</strong></dt>
<dd>Gender of specimen
</dd>
<dt><strong><a href="http://services.morphbank.net/mb3/" title="Go to the Morphbank Services website">services.morphbank.net</a></strong></dt>
<dd>Web services are currently available for Morphbank contributors to query Morphbank and return output in a variety of formats (XML, Thumbnails, RDF) as well as set up RSS feeds. Go to the Morphbank User Manual section <a href="<?echo $domainName;?>About/Manual/services.php">Morphbank Web Services</a> for details.</dd>
<dt><strong>sftp, smtp</strong></dt>
<dd>Secure File Transfer Protocol (used for transferring files over the internet), Simple Mail Transfer Protocol 
(used for transferring email from one server to another) 	 
</dd>
<dt><strong>Sort</strong></dt>
<dd>Arrange or order groups of information with a common interest into a sequence.
</dd>
<dt><strong><a href="http://specifysoftware.org/">Specify 6</a></strong></dt>
<dd>From the Specify 6 web site: Specify 6 is a museum and herbarium collections data management system for Windows, Mac OS X, and Linux. Specify 6 processes specimen information for computerizing holdings, for tracking collection management transactions, and for mobilizing species occurrence data to the internet. Specify is free and open source licensed. A project is underway to create a piece of software that will output data in a Specify 6 database into Morphbank XML suitable for uploading via <em>web services</em>. This streamlines and simplifies the upload of large amounts of data to Morphbank and paves the way for future data-updates to keep databases in sync.
</dd>
<dt><strong>Specimen part</strong></dt>
<dd>Pertains to a view that contains a portion of a specimen
</dd>
<dt><strong>Stage</strong></dt>
<dd>The Developmental Stage, i.e., the age class, reproductive stage, or life stage of the biological individual referred to by the record.
Examples: "juvenile", "adult", "nymph", the developmental growth phase of a specimen.
</dd>
<dt><strong>Submit</strong></dt>
<dd> Upload data to a remote computer. (E.g. clicking the "submit" button on the Add Image screen will upload the image to the Morphbank database.
</dd>
<dt><strong>Submitter</strong></dt>
<dd> Person entering the data into Morphbank (may be the same person as the contributor).
</dd>
<dt><strong>Tab or comma-delimited text files</strong></dt>
<dd> ASCII data files where the fields are separated by a tab or comma character. Used for upload of data into Morphbank.
</dd>
<dt><strong>Taxon</strong></dt>
<dd> Scientific name of the specimen
</dd>
<dt><strong>Tools</strong></dt>
<dd> Header Menu button in Morphbank directing the user to: Submit, My Colletions, My Manager, Select Group, Account settings, and Log Out.
</dd>
<dt><strong>Taxonomic Serial Number (TSN)</strong></dt>
<dd>The ID number of a specific taxon in the ITIS database.
</dd>
<dt><strong>(Temporary) Morphbank Number</strong></dt>
<dd>A sometimes temporary Taxonomic identification number assigned by Morphbank to a taxon name added to Morphbank. These
numbers always begin with 999. Some of these names will eventually be 
in ITIS and the Morphbank 999...number will be changed to the ITIS TSN assigned to the name. Otherwise the 999 number is permanent.
</dd>
<dt><strong>TWiki</strong></dt>
<dd> A structured Wiki site. One is currently used by Morphbank developers to gather information about future version requirements.
</dd>
<dt><strong>Type Status</strong></dt>
<dd> A code indicating a particular kind of specimen. Morphbank uses nomenclatural types as defined by ICBN and the ICZN.
</dd>
<dt><strong>Upload</strong></dt>
<dd> The user sends data to a remote computer.
</dd>
<dt><strong>View</strong></dt>
<dd> A view specifies the criteria under which a photograph was taken. Specific details are included here about: what is
in a given image (specimen part), the orientation of the camera with respect to the object being imaged (view angle),
preparation of the specimen for imaging (imaging preparation technique) as well as the sex, form and life stage of the
specimen in the image.
</dd>
<dt><strong>Wiki</strong></dt>
<dd> A type of website that allows users to add, remove, or otherwise edit and change all content very quickly and easily, sometimes without the need for registration (defined from <a href="http://en.wikipedia.org/wiki/Main_Page">http://en.wikipedia.org/wiki/Main_Page</a>) </dt>
<dt><strong>XML Schema for Morphbank</strong></dt>
<dd> View the current <a href="http://morphbank.net/schema/mbsvc3.xsd">Morphbank XML Schema here.</a>
</dd>
</dl>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/screenTips.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

