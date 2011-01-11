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
		<h1>Morphbank Services - services.morphbank.net/mb3/</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
		<!--<div class="specialtext2">
<p><a href="<?echo $config->domain;?>About/Manual/Movies/submitview.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a View</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/submitview.avi" target='_blank'>video</a>
</p>
</div>
-->
<p><a href="http://services.morphbank.net/mb3/">services.morphbank.net/mb3/</a> makes it possible for anyone to query the Morphbank database. In essence, it is analogous to a <strong>Keyword</strong> search query in the My Manager interface of Morphbank. The <em>services</em> page lets users construct the query of their choice, selecting what to look for and in what format the output appears. For example, anytime after submitting data and images to Morphbank, a Morphbank Contributor can use the <a href="http://services.morphbank.net/mb3/">services.morphbank.net/mb3/</a> interface to return Morphbank Ids for building links. Details about how to find and use <em>services</em> are next.
</p>

<p>Services are found at <a href="http://services.morphbank.net/mb3/">services.morphbank.net/mb3/</a>
<ul>
<li>The <a href="http://morphbank.net/schema/API1.html">application programming interface (API)</a> for <a href="http://services.morphbank.net/mb3/">services.morphbank.net/mb3/</a> describes how the query is put together and reveals what search combinations are possible.
</li>
<li>The results of a <em>services</em> query may be output in XML using the <a href="http://morphbank.net/schema/mbsvc3.xsd">Morphbank Schema</a>.
</li>
</ul>
</p>

<img src="ManualImages/services_default.png" hspace="30" />
<br />
<br />
<h3>Parts of a Service Request</h3>
In the screen shot above, there are 4 sections to constructing a <em>services request</em> and each section has various choices available.
<ol>
<li><strong><font color="#000066">Choose a search method</font></strong>: the user may choose from 5 options including:</li>

	<ul>
	<li><strong>Keyword search</strong>: When choosing this search method, the keywords to search by are entered in the <strong><font color="#000066">Other parameters</font></strong> section in the <strong>	Keywords</strong> field.
    </li>
	<li><strong>Find an object by its Morphbank identifier</strong>: If an identifier is known, the data and metadata about the item can be retrieved using this identifier. Select this search method and then enter the Morphbank identifier in the <strong><font color="#000066">Other parameters</font></strong> section in the <strong>Morphbank or external id</strong> field.
	</li>
	<li><strong>Taxonomic name search</strong>: To search the Morphbank database by taxon name, choose this search method and enter the taxon name in the <strong>Taxonomic name</strong> field in the <strong>	<font color="#000066">Other parameters</font></strong> section of this page.
	</li>
	<li><strong>Recently changed objects</strong>: This option gives users a way to query the database for only the newest or most recently modified objects. When selecting this option, use it in combination with the <strong>Beginning change date</strong> and <strong>End change date</strong> or <strong>Leave beginning date blank for most recent changes, enter number of days</strong> fields in the <strong><font color="#000066">Other parameters</font></strong> section.
	</li>
	<li><strong>Find an object by its external identifier</strong>: If Morphbank Contributors provided GUIDs for their objects in Morphbank, one can search for them using this id. Choose this option and then enter this external id in the <strong>Morphbank or external id</strong> field in the <strong><font color="#000066">Other parameters</font></strong> section.
	</li>
	</ul>
<li><strong><font color="#000066">Select object type(s).</font></strong>Data is associated with various objects in Morphbank. The user may wish to search data associated with Images or Specimens, or both perhaps. Check the objects of interest.</li>

<li><strong><font color="#000066">Other parameters.</font></strong> These fields are used in combination with each other and with the other sections of this query constructor.
</li>
  <ul>
    <li><strong>Keywords</strong>: enter terms to be included in a keyword search.
    </li>
    <li><strong>Geolocated items only</strong>: use this to limit the result set to image objects in Morphbank that have latitude and longitude information provided.
    </li>
    <li><strong>Maximum number of results</strong>: in case of a possible large result set, use this to limit the number of records returned.
    </li>
    <li><strong>Offset to first result</strong>
    </li>
    <li><strong>User id</strong>: Using a Morphbank User id, the search can be limited to objects associated with a given user / contributor.
    </li>
    <li><strong>Group id</strong>: as with User id, objects in Morphbank are associated with a given Morphbank Group. Using the Group id, the search 
    </li>
    <li><strong>Beginning change date as mm/dd/yy</strong>: Use this with the next field to limit search to objects changed in a given time frame. It is used with the <strong><font color="#000066">search method</font></strong> <strong>Recently changed objects</strong>.
    </li>
    <li><strong>End change date as mm/dd/yy</strong>: Use this with the above field to limit search to objects changed in a given time frame. It is used with the <strong><font color="#000066">search method</font></strong> <strong>Recently changed objects</strong>.
    </li>
    <li><strong>Leave beginning date blank for most recent changes,</strong>: Use this with the <strong><font color="#000066">search method</font></strong> <strong>Recently changed objects</strong> to limit the search result set to objects changed in a specified number of days. For example, one might want to find out what is new in Morphbank for the last 5 days. This search can be added as an RSS feed and initiated at a click.
    </li>
    <li><strong>Morphbank or external id</strong>: If a user needs / wants information for a specific Morphbank object and knows the Morphbank id or the external id (GUID), enter the id in this field and check <strong>Find an object by its Morphbank identifier</strong> in the <strong><font color="#000066">Choose a search method</font></strong> section.
    </li>
    <li><strong>Taxonomic name</strong>: Enter a taxon name here to find information about any objects in Morphbank associated with this taxon name. This field is used with the <strong>Taxonomic name search</strong> option in the <strong><font color="#000066">Choose a search method</font></strong> section.
    </li>
    </ul>
<li><strong><font color="#000066">Choose an output format</font></strong>: Here the query output type is selected. A user may only want Morphbank ids or an RSS feed for a given query. Some users may need the data returned in XML format. Other options include seeing the geolocated items via Google Maps and outputting the data in Morphbank XML Schema.</li>
	<ul>
	<li><strong>IDs only in XML</strong>: outputs ids only, in XML format.
    </li>
    <li><strong>XML using schema <a href="http://morphbank.net/schema/msvc3.xsd">http://morphbank.net/schema/mbsvc3.xsd</a></strong> returns data in XML format that matches the current Morphbank schema.
    </li>
    <li><strong>Thumbnail page</strong>: Using this option, Morphbank users see thumbnails of objects and a link to Google with the objects mapped.
    </li>
    <li><strong>Basic information in XML</strong>
    </li>
    <li><strong>RSS feed</strong>: Once a particular query is created, it can be saved and run again anytime a user desires. This is basically what an RSS feed does. If a user selects this option, an icon is saved to their Browser toolbar (for example) and they can carry out the services request by simply clicking the icon.
    </li>
    <li><strong>Detailed information in RDF</strong>: outputs the data in <strong><a href="http://en.wikipedia.org/wiki/Resource_Description_Framework">Resource Description Framework (RDF)</a></strong> format.
    </li>
    </ul>
</ol>
<h3>A Service Request Revealed</h3>
Once a user chooses options from above and clicks <strong>Submit</strong>, a <em>services request</em> is sent to the database and results returned in the desired format. Each part of the request can be seen in the brower's URL display. Here is a simple example:
<ol>
<li><strong><font color="#000066">Choose a search method</font></strong>: <strong>Keyword search</strong>
</li>
<li><strong><font color="#000066">Select object type(s)</font></strong>: <strong>Image</strong>
</li>
<li><strong><font color="#000066">Other parameters</font></strong>: <strong>Keywords:</strong> Magnolia acuminata fruit
<br />Leave other fields in this section as is.
</li>
<li><strong><font color="#000066">Choose an output format</font></strong>: <strong>Ids only in XML</strong>
</li>
<li>Click<strong> Submit</strong> (see results in screen shot):
</li>
</ol>
<img src="ManualImages/services_request1.png" hspace="10"/>
<h3>Using the IDs returned</h3>
In the above example, a user wants to find out if Morphbank has any images of <em>Magnolia acuminata</em> fruit. The search request via <em>services</em> returns 3 Image Ids. On any web site the user may be building, these ids can be embedded in any web pages to construct a dynamic URL link to the images. For the 3 Ids returned, the user can construct links to the data about the image or to the image itself as shown in the following URLs. Note the formats of the URLs.
<ul>
<li><a href="http://www.morphbank.net/?id=466838">http://www.morphbank.net/?id=466838</a>
	<ul>
    <li>Returns the Image Record Show page from Morphbank which has a 400 px width image embedded and displays any data about the image.
	</li>
    </ul>
</li>

<li><a href="http://www.morphbank.net/?id=466838&imgType=jpeg">http://www.morphbank.net/?id=466838&amp;imgType=jpeg</a>
	<ul>
    <li>With <strong>&amp;imageType=jpeg</strong>, the user can specify the version of the image to be returned. The user may choose from <strong>tiff</strong>, <strong>jpeg</strong>,<strong> jpg</strong> or <strong>thumb</strong>.
    </li>
    </ul>
</li>
<li><a href="http://www.morphbank.net/?id=466838&imgType=jpeg&imgSize=200">http://www.morphbank.net/?id=466838&amp;imgType=jpeg&amp;imgSize=200</a>
	<ul>
    <li>Adding the <strong>&amp;imgSize=XX</strong> parameter gives the user the flexibility to specify the width of the image to be displayed.
    </li>
    </ul>
</li>
</ul>
		<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/dwcabcdmb.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
