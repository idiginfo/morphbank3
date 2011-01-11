<p>
(Before submitting data you must have a <a href="/Admin/User/new" >morphbank user account</a>)<br />
There are several ways in which a user or a group of users can upload images to morphbank. Certain variables of a particular data and image set help determine which upload method fits best.
</p>
<h2>Choosing upload method - Considerations</h2>
	<ul>
	<li><strong>Frequency</strong> of upload: one time, infrequent or often?</li>

	<li><strong>Number of images</strong> to upload each time: usually less than 50, to always more than 100</li>
	<li><strong>Contributor data is: databased or not</strong>?</li>
	<li><strong>Contributor has unique identifiers or not</strong>: for each image and specimen, a unique identifier (uid) exists or can be created</li>
	<li><strong>Contributor maps</strong> data fields to <a href="/schema/mbsvc3.xsd">Morphbank XML Schema</a>: yes or no?</li>

	</ul>
<h2>Upload Methods</h2>
<p>
<strong>1. <a href="/">The Web Interface.</a></strong> Images are uploaded separately along with their associated text information by filling out web forms.  The web upload process is described in detail in the <a href="/About/Manual/">morphbank User's Manual</a>.  You must log in and choose a group before being able to submit. Consider this option if the following criteria seem to fit:
</p>

	<ul>
	<li>Frequency of upload: one time, infrequent or frequent</li>

	<li>Approximate number of images to upload each time: less than 50, not more than 100</li>
	<li>Contributor data is: not databased (usually)</li>
	<li>Contributor does not have uids and does not wish to create uids.</li>
	</ul>

<p>
<strong>2. Morphbank Excel Workbook v3. </strong> Morphbank provides a pre-prepared modular (object-oriented), multi-page Excel Workbook in 2 versions. They are identical except for the drop-down tables. One is tailored to kingdom <a href="/docs/mb3a.xls">Animalia</a>, the other to <a href="/docs/mb3p.xls">Plantae</a>.  Read a little more about the <a href="/Help/Documents/">v 3 Workbooks</a> and download the corresponding workbook and manual from there or get: <a href="/docs/mb3wbmanual.pdf">Manual - Excel Workbook v3</a>. Contributors can send to morphbank a CD containing images and an Excel Workbook populated with information.  We also have ftp upload. Consider this option using the following factors:

</p>

	<ul>
	<li>Frequency of upload: one time, infrequent or often</li>
	<li>Approximate number of images to upload each time: usually more than 100 and less than 400</li>
	<li>Contributor data is or is not databased. Very suitable if it is not.</li>
	<li>Contributor may or may not have uids, but does not need these to be databased other than perhaps in the notes field.</li>
	<li>Learning curve: fast if contributor first uploads some images to Morphbank via-the-web.</li>

	<li>Fixed fields: fields offered are fixed. Only data that fits into these fields is uploaded. If the contributor has data that must go into Morphbank, but there is no corresponding field in this workbook, choose a different upload method.</li>
	</ul>

<p>
<strong>3. <a href="/docs/customWorkbook.xls">Morphbank Custom Workbook</a>.</strong> This latest upload method released for Morphbank Contributors focuses on fields from the main Morphbank objects: Image, Specimen, Locality and View. The Workbook and Instructions for populating each field are found under <a href="/About/Manual/customWorkbook.php">Manual - Morphbank Custom Workbook. </a>Contributors need to consider the factors listed next:</p>

	<ul>

	<li>Frequency of upload: one time or even infrequent, but probably best if repeat submissions are planned.</li>
	<li>Number of images to upload: usually more than 100</li>
	<li>Contributor data is or is not databased, but this method is more suitable than option 2 if data is databased.</li>
	<li><strong>UIDs are required</strong>. If they do not already exist, a contributor must create them for each image and specimen.</li>
	<li>Mapping data to Morphbank fields: this takes some time. Mappingis faster if the user uploads some images to Morphbank through the web-interface first.
    	</li>
	<li><strong>User Property</strong>: Contributor data fields that don't map to a Morphbank field are entered into Morphbank as a User Property. These fields, with corresponding data, appear in Morphbank. The values for these fields are not searchable in the Morphbank Keyword search feature.</li>

	</ul>
	
<p>
Fields in this workbook are also DarwinCore2 (dwc v1.2) fields. Users of this upload method are also able to enter <strong>user-defined fields</strong> to accommodate data where there is no existing Morphbank field that fits. In this instance, Contributors are <em>strongly encouraged</em> to try and find a field in one of the following Schemas that match the requirements. Using standard Schemas greatly facilitates data-sharing between computers, aka <em>interoperability</em>.
</p>

<ul>
	<ul>

	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_core.xsd" target=_blank>Darwin Core XML Schema</a></li>
	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_curatorial.xsd" target=_blank>Curatorial Extension to the Darwin Core</a></li>
	<li><a href="http://rs.tdwg.org/dwc/tdwg_dw_geospatial.xsd" target=_blank>Geospatial Extension to the Darwin Core</a></li>
	</ul>
</ul>

</p>Morphbank encourages Contributors to try this method. It may be possible in the near future, via something like <a href="http://services.morphbank.net/mb3/" target=_blank>Morphbank Services</a> for users to upload and update their data directly using the custom workbook or the v 3 Workbook.

</p>
<br />
<br />

<p><strong>4. Morphbank XML Upload</strong>: Users with data <em><strong>in a database</strong></em> and many images (>500) are encouraged to use this option. This method is suitable when users plan to upload multiple large datasets and the accompanying images over time. Efforts are underway to automate this process. Considerations for this option:</p>

	<ul>
	<li>Frequency of upload: one time, infrequent or often</li>
	<li>Approximate number of images to upload each time: usually more than 100</li>

	<li>Contributor data is a database.</li>
	<li>Contributor has uids.</li>
	<li>Learning curve: Try upload of some images to Morphbank via-the-web to become familiar with Morphbank fields to simplify mapping process.</li>
	<li>Fields a contributor has that don't match an existing Morphbank field can be inserted as a user property field (a user-defined field).</li>
	</ul>

<p><strong>Work-flow for this Option</strong> is roughly as follows:</p>

<ul>
    <li><strong>Mapping </strong>Contributor database fields to Morphbank fields. 
    	<ul>
        <li>See Morphbank <a href="/docs/mbTablesDescription.pdf">Table descriptions pdf</a> and</li> 
        <li><a href="/schema/mbsvc3.xsd">Morphbank XML Schema</a> to map Contribuor database fields to Morphank fields.</li>
        </ul>

    <li><strong>Checking &amp; Adding Taxon Names</strong> where necessary, to Morphbank, before upload of data &amp; images and getting Morphbank <strong>tsn</strong> ids for taxon 				names.</li>
    	<ul>
        <li><strong>Login and </strong>use <a href="/Admin/TaxonSearch/index.php?">Taxon Search</a> if checking, let's say, fewer than 20 names.</li>

        <li>Use <a href="/Help/nameMatch/">Name Query</a> to check a long list of taxon names and get a CSV file report of matches / non-matches.</li>
        <li>Names added to Morphbank via 1) web site, 2) <a href="/docs/mb3a.xls">Excel Workbook</a>, 3) Taxon Upload form (contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>)</li>

        </ul>
    <li><strong>Contributor Data converted from CSV format to Morphbank XML Schema format</strong>
    <li><strong><em>Perhaps </em>Modifying the Morphbank XML Schema</strong> to accomodate the Contributor dataset.</li>
    <li><strong>Upload of Data</strong></li>
    <li><strong>Images sent via FTP</strong> or hard drive</li>

    </ul>

<p><strong>XML Expertise:</strong> Those <em>would-be Contributors</em> facile with <strong>XML</strong> are encouraged to contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>.
Users must put their data into the Morphbank XML Schema themselves, facilitating the upload process.
	</p>

<p>
<strong>5. Specify plug-in. </strong> Currently in alpa-testing, a plug-in from Specify allows any contributor with data already in Specify6 to upload directly to 
Morphbank from Specify. Consider this option using the following factors:
</p>

	<ul>
	<li>Frequency of upload: one time, infrequent or often</li>
	<li>Approximate number of images to upload each time: usually more than 100</li>
	<li>Contributor data is in Specify6 database, or there are plans to import data into this database.</li>

	<li>Contributor has uids in Specify.</li>
	<li>Learning curve: Try upload of some images to Morphbank via-the-web before using plug-in to become familiar with Morphbank fields.</li>
	<li>Any populated data fields in Specify mapped to the plugin (Darwin Core fields) insert into Morphbank. If one of those Specify fields is not in Morphbank, that field and it's values are inserted into Morphbank as a User Property.</li>
	</ul>

<hr width="525" height="5" />

<div style="margin-left:20px;">
  <em><strong>How to save and send the files</strong></em>

  <br /><br />
  For each image set with a unique release date, create a separate folder. Name each folder uniquely, preferably with no spaces or special characters. Inside each folder, place the morphbank Excel workbook and all associated images. The <a href="/Help/Documents/">workbook manuals</a> should be followed carefully to prevent errors in your upload.     

  You can send your data to morphbank admin in several ways:
  <br /><br />
  <ul>
    <li><strong>DVD or CD.</strong> Save all image collection files on a CD or DVD and send to morphbank via land mail (see address below). Make sure to keep a backup copy for yourself and send with proper postage and protection.</li>  
  
    <li><strong>Secure FTP upload.</strong>  Morphbank provides ftp (ftp) and  secure ftp (sftp) upload service as an alternative to mailing CDs/DVDs.  Registered morphbank users may email <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong> for password, username and address.  Although you should be familiar with sftp or ftp, only minimal experience is necessary to use this service.  The Morphbank server may be accessed through any graphic sFTP program, terminal or the command line.</li>

    
    <li><strong>External Hard Drive.</strong> Those with a large number of images may want to send them to Morphbank on an external hard drive. After upload, Morphbank returns the hard drive.</li>
    
    <li><strong>Images via cURL.</strong> If images are already on a server accessible to Morphbank, images can be copied over from that server using an automated script. The contributor arranges for the Morphbank server to have permission to access the image files in this manner. Data is still sent to Morphbank on CD or DVD or via ftp or sftp.
    </li>
  </li> 
  </ul>
</div>

<p>
Once a morphbank administrator has sent your account information you may begin uploading. Be sure to use sftp as the transfer protocol. There is no limit on the number of files uploaded at one time.  Once the images are on the server email mbadmin at scs dot fsu dot edu and let us know that your file transfer is complete and if you would like the account to remain open.  Morphbank will delete the files off of the server once they have been received and the account password changed if the account is not requested to remain open.  This service may be used once or many times depending on the user's specific needs.

</p>
<p>
When we receive images it may be a few weeks before upload; a member of the morphbank admin team will contact you in order to check your image upload or follow up with corrections to your Excel workbook.  For further information about these options contact the morphbank admin group at <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>
</p>

<hr width="525" height="5" />

<p>
<strong>Mailing Address for non-web upload:</strong><br />
Morphbank <br />
400 Dirac Science Library<br />
School of Computational Science<br />
Florida State University<br />
Tallahassee, FL 32306-4120<br />
USA
</p>

