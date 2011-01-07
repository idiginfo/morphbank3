<?php 
	
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Add Taxon Name</h1>

<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">
<!--<br />
Adding a new taxon name to the temporary ITIS table maintained in Morphbank is a serious undertaking and should only be done by 
qualified research biologists who are experts in that particular portion of the taxonomic tree.
-->
<p>Click on the question of choice to jump down the page.
<ul>
<li><h3><a href="#who">Who can add taxon names?</a></h3></li>
<li><h3><a href="#whatTypes">What types of names can be added to Morphbank?</a></h3></li>
<li><h3><a href="#tempNames">What is the effect of adding Morphbank taxon names?</a></h3></li>
<li><h3><a href="#changeTemp">What if the newly added taxon name needs changing?</a></h3></li>
<li><h3><a href="#whatRank">At what rank can new taxon names be added?</a></h3></li>
<li><h3><a href="#whenWhere">When, where and how can taxon names be added?</a></h3></li>
<li><h3><a href="#taxonSearch">Where to Find <em>Taxon Name Search</em></a></h3></li>
<li><h3><a href="#addTaxonFields">What are the fields in the <em>Add Taxon Name</em> screen?</a></h3></li>
</ul>
</p>
<!-- TO DO Need to add info about 3 different ways to add taxon names. Maybe a separate manual page entirely. dp September 1, 2010 -->
<p>
<h3><a name="who">Who can add taxon names?</a></h3>
<p>Any Morphbank account holder may add taxon names to Morphbank. Via the web-interface, taxonomic names may be added at the rank of sub-order or lower. Morphbank Users may add names in any kingdom, as needed.</p>

<p>
<h3><a name="whatTypes">What types of names can be added to Morphbank?</a></h3>
<p>Contributors may submit taxon names of two types: <strong>Regular scientific names</strong> or <strong>manuscript names</strong>.</p>
<p> 
Also, a contributor must choose the status of the new name: "publish" or "do not publish." Both
types of new taxon names are given Morphbank Taxonomic Serial Numbers (TSNs) which
can be identified because they have a TSN number greater than 999,000,000. These are unique identifiers for a given taxon name.</p>

<h3><a name="tempNames">What is the effect of adding Morphbank taxon names?</a></h3>
<p>Published <strong>Regular scientific names</strong> and published <strong>Manuscript names</strong> 
are available to scientists to use for adding specimens and applying to determination annotations.</p>

<p>
Unpublished names can be edited by the Morphbank Contributor and Submitter and anyone else in that particular Morphbank group if their group role is <em>lead scientist</em> or higher. Users may also add external links and identifiers to a Taxon Name added to Morphbank. If, for example, the Taxon Name is already in another <em>taxonomic name server</em>, the identifier from that database can be put in via the Add Taxon screen to link to the taxonomic data for that name.</p>

<h3><a name="changeTemp">What if the newly added taxon name needs changing?</a></h3>
<p>What follows is a brief entry on how to <strong>Edit</strong> a taxon name. For a more detailed entry on how to 
<strong>Edit</strong> a taxon name, jump to <a href="<?echo $config->domain;?>About/Manual/edit_taxon_name.php" target="_blank">Edit Taxon Name</a>.</p>
<ul>
<li>If the Contributor/Submitter chooses <strong>do not publish</strong>, the new taxon names are viewable/usable only by them and
unpublished names <em>may be edited</em>.</li>
<li>Published <strong>Regular scientific names</strong>
may be changed <strong>only if</strong> they are not used by any other scientist</li>

<li>A user may <strong>Edit</strong> their contributed <strong>taxon names</strong> by:
<ol>
<li>First, selecting the <strong>Group</strong> the user originally associated with these names.
<br />Select the <strong>Group</strong> from the <strong>Header Menu > Tools > Select Group > groups list</strong> or hover over <strong>Group</strong>
at top left of Header to choose <strong>Group</strong> from a list.</li>
<li>Going to <strong>Header Menu</strong>, Tools >  My Manager > click <strong>Taxa</strong> tab.</li>
<li>In the <strong>Keywords</strong> field, enter the name (or at least part of the name) to be edited.</li>
<li>If there are many hits, use <strong>Limit by</strong> in the left sidebar. Click <strong>Contributor,</strong>
then <strong><img src="ManualImages/go.gif" alt="Go button">.</strong> The new search results will be limited to names the logged-in user
 has uploaded to Morphbank.</li>
<li>Find the name of interest. Click the <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"/> edit icon to
open the <strong>Edit Taxon Name</strong> screen.</li>
<li>Correct the fields that need updating, click 
<img src="ManualImages/update_button.png" alt="Update button">.</li>
</ol>
</li>
</ul>

<p>
<h3><a name="whatRank">At what rank can new taxon names be added?</a></h3>
<ul>
<li>Via the web interface, new taxon names can be added at the <strong>Suborder</strong> rank or below at this time. The system is designed to only allow names to be added at one rank at a time and only to a
level that is appropriate. For example, if adding a new name starting at the
family rank, you can only add subfamily, tribe, subtribe or genus. All
subordinate taxonomic names, if needed, are added one at a time.</li>
<li>If many names need to be added, the current <a href="<?echo $config->domain;?>Help/Documents/">
Excel workbook v 3 (Animalia or Plantae)</a> <em>SpecimenTaxonData sheet</em> can be used to add taxon names from <strong>Genus</strong> and below.</li>
<li>Morphbank developers have introduced a new spreadsheet for adding taxon names along with the associated
metadata (taxon author, name source, external link and external id for a name). Using this method, names can be added 
at<strong> any taxonomic rank</strong>. Contact <strong>mbadmin <font color=blue>at</font> scs <font color=blue>dot</font> fsu <font color=blue>dot</font> edu</strong>
if this fits your user case scenario.</li>
</ul>
</p>

<p><h3><a name="whenWhere">When, where and how can taxon names be added?</a></h3>
<ul>
<li><em>Taxon Names can be added one-at-a-time, anytime, via the Taxon Name Search</em> (see next screen shot). </li>
</ul>

<!--
<div class="specialtext2">
<a href="<?echo $config->domain;?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>add a Taxon Name</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'>video</a>
</div>
--->

<div class="specialtext2">
	<h3><a name="taxonSearch">Where to Find <em>Taxon Name Search</em></a></h3>
	<ul>
    <li>Header Menu: <strong>Browse > Taxon Search</strong>.    </li>
	<li>Header Menu: <strong>Tools > Submit > Taxon Name</strong>.	</li>
	<li>Header Menu: <strong>Tools > Submit Specimen</strong> (click on the <img src="../../style/webImages/selectIcon.png" alt="select check"/> next to Determination Id/Name field).	</li>
	</ul>
</div>

The <img src="ManualImages/add_button.gif" alt="Add taxon button"/>
button is visible on screen for any authorized logged-in Morphbank user anytime 
they are searching for a taxon name and they have reached at least the Suborder level in the Taxon
Name Search.
<ul>
<li>Select the <strong>Group</strong> desired, before adding a Taxon Name. You will need to be in this group to edit the name, should it need editing.</li>
<li>From the <strong><em>Taxon Name Search</em></strong>, enter the taxon name desired to see if it's arleady in Morphbank, click Search.  </li>
<ul>
  <li>Use <a href="<?echo $config->domain;?>Help/nameMatch/"><strong>Name Query</strong></a> to search for a whole list of names at one time.</li>
  <li>For names that need adding then...</li>
</ul>
<li>Go to<strong><em>Taxon Name Search</em></strong>, enter the taxon name for the rank just above the rank of the taxon name to be added and click Search.</li>
<li>
  Once there, click on the Add button on the right, to add a child with that row's name as the parent. See the example below for how this works.</li>
<li>
  In the My Manager interface, click the <a href="<?echo $config->domain;?>About/Manual/myManagerTaxa.php" target="_blank">Taxa</a> tab to see how a name looks once it's entered.</li>
<li>There are several different ways to add names to Morphbank.
  <ol>
    <li>Web - good for adding names at rank of Suborder or lower. Well-suited for intermittent upload and less than 30 - 50 names.</li>
    <li><a href="<?echo $config->domain;?>Help/Documents/" target="_blank">Excel Workbook v 3</a> <strong>SpecimenTaxonData</strong> sheet - many  names can be added with this feature, from rank Genus and lower.</li>
    <li>In beta-testing, a taxon upload workbook is available to upload names at any rank. Contact <strong>mbadmin <font color=blue>at</font> scs <font color=blue>dot</font> fsu <font color=blue>dot</font> edu</strong> for more about this option.</li>
    </ol>
  <br />
  <img src="ManualImages/taxon_name_search2.png" alt="taxon name search" />
  <br />
</li>
</ul>
<p>Note at this point, the user has 3 choices:
<ol>
<li>
add a child at this taxon rank, under Coleoptera by clicking on Add,</li>
<li>click on Adephaga, Archostemata, Myxophaga or Polyphaga to see the existing children for these taxon, or</li>
<li>
*Add a child to one of the Suborders by clicking on Add on the far right of that table row. It is important to check first to see what children (if any) already exist for this name by using option 2 above.</li>
</ol>
</p>
<p>
In this example, the user puts "Coleoptera" in the Taxon Name Search. Wanting to add a child below Adephaga, the user clicks
the Add button on the far right of the container in the Adephaga table row. This opens the following Add Taxon screen to add a child under Adephaga.</p>
<img align="texttop" src="ManualImages/add_taxon_name.png" alt="add taxon name screen" hspace="20" />
<br />
<br />
Filling out the <h3><a name="addTaxonFields">Add Taxon Name</a></h3> form:
<ul>
    <li><strong>Type of Name:</strong> A user must select <strong>Regular scientific
    name</strong> or <strong>manuscript name</strong> from the drop-down. Regular scientific name is the default.
    <br />
    <strong>Warning</strong>: there are some stringent guidelines regarding when the names become
     public and whether or not they can be edited/deleted. See <strong>Name Status</strong> field below.
     </li>
     <br />
	<li><strong>Taxon Name:</strong> (Required) In the example above, the user clicked on the Adephaga Suborder and then
	clikced on "Add new taxon." Now a Family name may be added below this Suborder.
    </li>
    <br />
	<li><strong>Rank Identification:</strong> (Required) Use the drop-down to indicate the rank of the 
	name entered in the Taxon Name field (this example has family). If the user then needs to add
	another taxon name at the next lower rank, they'll repeat this form at that rank.
    </li>
	 <br />
	<li><strong>Name Source:</strong> Enter the name of the organization that holds the publication data for the name and/or
	is responsible for contributing the name to the Morphbank database. Examples of values this field might contain include:
	uBio, TROPICOS, IPNI, APNI, Catalog of Life or a group like SBMNH, SCAMIT, AMNH, World Spider Catalog, etc.
    </li>
    
	<li><strong>Publication Id / Name:</strong> (Required by ITIS) If the publication data is already in Morphbank or needs to be
	added to Morphbank, click the <img src="../../style/webImages/selectIcon.png" alt="select check"/> to open
	a Publication window. The user can <img src="ManualImages/select.gif" alt="select check button" /> a publication already 
	there, or click "Add New" to add a Publication. The Publication Id will then auto-fill as well as the Taxon Author field.
	<br /><br />If the user knows the Morphbank Publication Id, they may enter it and the Taxon Author field will not auto-fill.
	<br />
	<br />
	</li>

	<li><strong>Taxon Author(s), Year:</strong> Enter the Taxon Author or Authors followed by the year (unless auto-filled).
    </li>
	<br />
	
    <li><strong>Page(s):</strong> Indicate the appropriate page number(s) where the Taxon Name appears in the Publication.
    </li>
	 <br />
	
    <li><strong>Contributor:</strong> If you are the <strong>Submitter,</strong> be sure to choose the <strong>Contributor</strong> from this drop-down.
    </li>
    <br />
	
    <li><strong>Name status:</strong> Choose <strong>Publish now</strong> or <strong>Do not publish now.</strong> 
    If <strong>Publish now</strong> is selected, any Morphbank user may use the new Taxon Name and if they do, it cannot be edited. 
    With <strong>Do not publish</strong>, the name is editable and cannot be seen or used by Morphbank users other
  	than the Contributor or Submitter of this Taxon. Published <strong>Regular scientific names</strong> may only
  	be changed if they have not been used by any other Morphbank members.
  	Published <strong>Manuscript names</strong> can ONLY be changed by contacting <strong>mbadmin <font color=blue>at</font> scs <font color=blue> dot</font> fsu <font 					color=blue>dot</font> edu</strong>
    </li> 
    <br />
    
	<li><strong>Vernacular:</strong> Associated with the Taxon Name, a common name may be added here, in the language of choice. If a language needs to be added, contact <strong>mbadmin <font color=blue>at</font> scs <font color=blue>dot</font> fsu <font color=blue>dot</font> edu</strong>
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
    <li>Click the <strong>-</strong> icon to remove any outdated links.</li>
   </ul>
   <br />
<li><strong>Add External References</strong> to this record. For complete
instructions on providing external references refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External References</font> to open this feature. See next:<br/>
<img src="ManualImages/add_externalRefs.png" alt="external identifiers" hspace="20" vspace="10"/>
	<ul>
    <li>Enter the <strong>Description</strong> for the External Reference. This will appear to the Morphbank user as a label in front of the unique id.
    </li>
    <li>Enter the <strong>Identifier</strong> unique for this locality in the remote database in the <strong>Unique Reference ID</strong> field.</li>
	</ul>
</li>


    	<div class="specialtext3">
        <ul>
        <li><strong>Unique Reference ID</strong> <em>best practice</em> is to combine an <strong>acronym prefix</strong> + an <strong>identifier</strong>.</li>
        <li>The database table storing this identifier requires the values be unique. If the identifier string entered is already in this table, the user will have to figure out a different prefix.</li>
        <li>For example, a user, Fred S Unstead, has a Locality with ID=123456 and puts his initials as the prefix for: <strong>FSU:123456</strong></li>
        	<ul>
            <li>Florida State University (FSU) entered Locality IDs as prefix: FSU + an identifier (123456).</li>
            <li>Fred S Unstead needs to change his prefix in some way, for example: <strong>FSU-L:123456</strong> (where the L is for Locality) and the identifier will upload into Morphbank.</li>
            </ul>
        <li>The external unique reference ID can be used in future uploads and for updates of current records in Morphbank.    
        </ul>
        </div>  


<li>After completion of the form, click <img src="ManualImages/submit_button.gif" alt="submit button" /> 
to add the New Taxon Name with its Morphbank 999 TSN.
</li>
</ul>

For a given contributor, submitted names will be usable in the Morphbank system immediately from Taxon Name Search. 
If published, it will take 24 hours before the name appears in the Taxon Hierarchy. From the <strong>Taxa tab</strong> of My Manager, logged-in users may peruse / edit the Taxon Names they have contributed to Morphbank. For more information on this, jump to the <a href="<?echo $config->domain;?>About/Manual/myManager.php" target="_blank">My Manager</a> section of 
this manual or go to the Header Menu: <strong>click Browse to open My Manager > click the </strong><a href="<?echo $config->domain;?>About/Manual/myManagerTaxa.php" target="_blank"> Taxa tab</a>.
<br /><br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/InfoLinking.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table></div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	

