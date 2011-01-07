<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Submit: Add Image</h1>
<div id=footerRibbon></div>

<!--
<div class="specialtext2">
<a href="<?echo $config->domain;?>About/Manual/Movies/submitimage.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit an Image</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/submitimage.avi" target='_blank'>video</a>
</div>
-->
<p>
The data entered on the <strong>Add Image</strong> screen provides information about
the image to upload, the specimen to which the image belongs, and its view. Both a specimen and a view should exist before uploading an image. If not previously added, provisions have been made on this screen to add a specimen and a view.
</p>

<div class="specialtext3">
<h3>Image Issues</h3>
<ul>
<li>The image file should be in the form of (bitmap [.bmp], joint photographic
experts group [.jpeg, .jpg], and tag information file format [.tiff]). 
</li>
<li>(<strong>Phillips SEM</strong> users see <strong>warning notation</strong> in the <strong>Image to Upload</strong> and
<strong>Magnification</strong> sections below.
</li>
<li>For tiff images with color format = grayscale, see note in the <strong>Image to Upload</strong> section).
</li>
</ul>
</div>

<p>Path to <strong>Add Image</strong>: <em>header menu</em> <strong>> Tools > Submit > Image</strong>
</p>
<p>N.B. If a user belongs to more than one <a href="<?echo $config->domain;?>About/Manual/selectGroup.php">Morphbank Group</a> - be sure to <a href="<?echo $config->domain;?>About/Manual/selectGroup.php">select the group</a> for this Image before upload.
</p>

<img src="ManualImages/add_image.png" hspace="20" />

<div class="specialtext3">N.B. Morphbank displays locality information to everyone, logged-in or not. Use care when uploading images of threatened / endangered / protected entities. When adding a Locality for this situation, please enter only general locality data for these records; avoid precise locality details like latitude / longitude data.
</div>

<ul>
<li><strong>Image to Upload</strong> (Required)<br />
Browse or enter the location and name of the image to upload. Image
files to upload need file extensions of (bitmap [.bmp], joint photographic
experts group [.jpeg,. jpg] or tag information file format [.tiff]).
</li>
<div class="specialtext3">WARNING: Philips SEM users - <strong>Image Display Problem</strong>: Philips SEM machines utilize non-square shaped pixels so when the images are
outside of Philips software they are distorted slightly.
<br />
<br />
<strong>To Fix</strong>: The images need to be resized from 1424X968 to 1424X1064 for high resolution
images and 712X484 to 712X532 for standard resolution. Philips has a small
conversion program called XL-Stretch or images can be resized manually with image
manipulation software such as Photoshop.
</div>
<div class="specialtext3">TIFF images in <strong>grayscale</strong> color format need to be converted to RGB color format before upload to Morphbank. The ImageMagick software utilized by Morphbank has a bug that creates a negative image of any tiff image in grayscale.
</div>
<!--INSERT IMAGE of Add Image with a Preview Feature of the Image to Upload-->
<li><strong>Specimen</strong> (Required)<br />
Enter the specimen from which the image was taken. To insure accuracy,
specimen names need to be selected <img src="../../style/webImages/selectIcon.png" /> from <strong>Specimens</strong> selection
screen. The specimen must exist before you can add an image.
</li>
<br />
<img src="ManualImages/add_image_select_specimen.jpg" />
<br />
To sort the list of specimens, select the Sort By criteria from the drop
down list(s). The more criteria selected, (up to 3 levels) the more refined
the sort will be.
<br />
To add a new specimen that is not in the <strong>Specimens</strong> selection screen,
select the <img src="ManualImages/add_new_button.jpg" /> icon. This will open the 
<strong>Add Specimen</strong> screen. (See the <a href="<?echo $config->domain;?>About/Manual/uploadSubmitSpecimen.php">Add Specimen</a>
section of this manual for help in completing this form). When the new specimen is submitted, the screen will redirect back
to the <strong>Add Image</strong> page where the new specimen will automatically be
entered and the process of adding an image can continue where the user
left off.
<br />
<br />
<li><strong>Views</strong> (Required)<br />
Enter the view describing the image to be uploaded. To insure accuracy,
views need to be selected from the <strong>Views</strong> selection screen. A view
must exist before uploading an image.
</li>
<br />
<img src="ManualImages/add_image_select_view.jpg" />
<p>
To sort the list of views, select the Sort By criteria from the drop down
list(s). The more criteria selected, (up to 3 levels) the more refined the
sort will be.
</p>
<p>
To add a new view that is not in the Views selection screen, select the
<img src="ManualImages/add_new_button.jpg" /> icon; This will direct the user to the Add View screen.
(See the <a href="<?echo $config->domain;?>About/Manual/uploadSubmitView.php">Add View</a> section of this manual for help in completing this form.)
When the new view is submitted, the screen will redirect back to the Add
Image page where the view will be automatically entered and the process
of adding an image can continue where the user left off.
</p>
<li><strong>Magnification</strong><br />
Enter a positive decimal number that corresponds to the magnification of
the image. The magnification is calculated by dividing the size of an object
as it appears on the image (when the image is rendered at normal or
native size [100 %]) by the actual size of the object. Example: if the leg of
the specimen is 0.5 mm long and on the image it appears to be 20 mm
long, the magnification is 20/0.5 =40. Please make sure you specify the
magnification for all images, particularly if you do not have a scale bar in
them.
</li>

<div class="specialtext3">WARNING: Philips SEM users:<strong> Magnification Recording Problem:</strong>
 For Philips machines (and perhaps others), users may choose any size and resolution to output the images 
 and they will have variable magnification based upon the output choices.
 <br />
 <br />
<strong>To Fix:</strong> If the machine is calibrated correctly for high-resolution tiff digital output then the
magnification will be correct. However, not all SEM machines are calibrated in
this manner and your SEM technician should be consulted. It is also suggested
to print a scale bar on all images.
</div>

<li><strong>Date to Publish</strong><br />
Enter the release date for the images. The release date can be maximally
five years from the date of inclusion of the images in Morphbank. If you
leave this field unchanged, the default date is 6 months from inclusion of
the images in Morphbank.
</li><br />
<li><strong>Contributor</strong> (Required)<br />
Select the name of the contributor (person having the authorization to
release the images) from the dropdown list. The contributor can be
different from the submitter (person entering the data). If you need to add
new entries to this list, please contact <strong>mdadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>
</li>
<br />
<li><strong>Add External Links</strong> to this record. For complete
instructions on providing external links refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External Links</font> to open this feature. See next: 
<br/>
</li>
<img src="ManualImages/add_externalLinksRef.png" alt="external link options" hspace="20" vspace="10"/>
	<ul>
    <li>Choose the <strong>Type</strong> of External Link (some examples are: GenBank, Project, Institution, ...)
    </li>
    <li>Enter the text for the <strong>Label</strong> the user in Morphbank will click to go to this URL.
    </li>
    <li>Enter the complete <strong>URL</strong> here.
    </li>
    <li>The <strong>Description</strong> field is optional.
    </li>
    <li>Click the <strong>+</strong> icon to add additional external links.
    </li>
    <li>Click the <strong>-</strong> icon to remove any outdated links.
    </li>
   <br />
	</ul> 

    <li><strong>Add External References</strong> to this record. For complete
    instructions on providing external references refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External References</font> to open this feature. See next:<br/>
    <img src="ManualImages/add_externalRefs.png" alt="external identifiers" hspace="20" vspace="10"/>
		<ul>
    	<li>Enter the <strong>Description</strong> for the External Reference. This will appear to the Morphbank user as a label in front of the unique id.
    	</li>
    	<li>Enter the <strong>Identifier</strong> unique for this locality in the remote database in the <strong>Unique Reference ID</strong> field.
    	</li>
		</ul>
	</li>
</ul>
    	<div class="specialtext3">
        <ul>
        <li><strong>Unique Reference ID</strong> <em>best practice</em> is to combine an <strong>acronym prefix</strong> + an <strong>identifier</strong>.</li>
        <li>The database table storing this identifier requires the values be unique. If the identifier string entered is already in this table, the user will have to figure out a different prefix.</li>
        <li>For example, a user, Fred S Unstead, has a Image with ID=123456 and puts his initials as the prefix for: <strong>FSU:123456</strong></li>
        	<ul>
            <li>Florida State University (FSU) entered Image IDs with prefix: FSU + an identifier (123456).</li>
            <li>Fred S Unstead needs to change his prefix in some way, for example: <strong>FSU-I:123456</strong> (where the I is for Image).</li>
            </ul>
        <li>The external unique reference ID can be used in future uploads and for updates of current records in Morphbank.</li>    
        </ul>
        </div>  
</ul>
</p>
<p>
When the <strong>Add Image </strong>form has been completed, <strong>Submit</strong> to complete
the add image process. A message will confirm that you have <em>successfully
added an image</em>. From this point the user can edit the image information just submitted, continue to add additional
images or click return (goes to the front page of Morphbank) or click on the desired destination using drop-downs in the <strong>Header Menu</strong>.
</p>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/uploadSubmitPublication.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	