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
<h1>Edit Annotation</h1>
<div id=footerRibbon></div>
<br />
Edit Annotation contains the previously entered annotation data that can be edited by the owner
 (<em>only available if the annotation is not yet published</em>.)  
<div class="specialtext2">

Edit Annotation is now accessed through the Annotations tab in the <em><strong>new My Manager interface</strong></em>. Select 
from the Header Menu <b>Tools > My Manager </b> then click the Annotations tab. 
A user may click the <img src="../../style/webImages/edit-trans.png" /> <strong>edit icon</strong> of
any of their own<em> unpublished</em> annotations to make necessary changes.
</div>

The information included on the Edit Annotation screen reflects all the previous data that was included on the original annotation.  


To edit the information on this page, click on the appropriate area to highlight the data 
and type in or select the corrected information.  The type of annotation cannot be changed.
If, however, the annotation has not been published, it can be deleted entirely and re-entered under the proper type.  
Help in filling out the data fields on this page can be obtained 
in <a href="<?echo $domainName;?>About/Manual/annotationAdd.php">Add Annotations</a> located in this manual. After the
changes are made, click the <img src="ManualImages/submit_button.gif" /> button to save the changes.
<br />
<img src="ManualImages/edit_annotation_screen.jpg" vspace="10" hspace="20"/>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/edit_collections.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	