<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
        //include_once('../../includes/head.inc.php');
        //include_once('/panfs/panasas1/users/dpaul/www/includes/head.inc.php');
		include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
        $title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Morphbank's Image-Sharing Philosophy</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>		
Morphbank's policy regarding public and private images stresses that all images must be uploaded with a <a href=" http://creativecommons.org/licenses/by-nc-sa/3.0/us/">Creative Commons License 3.0 (BY-NC-SA)</a> or <em>less-restrictive</em> copyright. To select a less restrictive copyright, or public domain, please visit <a href="http://creativecommons.org/choose/">Creative Commons - choose a license</a>. The strategy behind this policy is to encourage cooperation and collaboration between organizations. Morphbank strives to facilitate sharing of images and image data. For example, Morphbank is a collaborator on the <a href="http://www.eol.org/">Encyclopedia of Life (EOL)</a> project and is sharing images with them.
</p>
<p>
Overall, the response to this viewpoint is very positive. For example, from Museums with large collections, Morphbank has especially good luck getting permission to release images of museum specimens to the public when the upload of images is associated with a Publication.
</p>

It is up to each Morphbank Contributor to negotiate this topic with the institutions to:
<ol>
<li>allow the imaging of specimens to occur and</li>
<li>release photographs for public view.</li>
</ol>

Note that:
<ul>
<li>A. The Institution can be recognized on the Morphbank pages.</li>
<li>B. A link can be made to any publication.</li>
<li>C. Links can be embedded in a publication that pull up more images from Morphbank.</li>
<li>D. Links can be made to the Institution page/s as well.</li>
</ul>
<p>
Lastly, when images are uploaded to Morphbank, the user provides a date in the <strong>date-to-publish</strong> field associated with the Image record. Prior to this <em>user-supplied</em> date, the image/s can only be seen by the Morphbank Contributor who supplied the image <em>and</em> any other Morphbank account holder who is in the same Morphbank Group. With this security model, a Morphbank Contributor can time the release of images to the public to coincide with a publication.
</p>


			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/manualHints.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	
