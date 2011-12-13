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

	<div class="main">	<div class="mainGenericContainer" style="width: 820px; ?>
		<!--change the header below -->
		<h1>Introduction</h1>
		<br /><br />
			<table class="manualContainer" cellspacing="0" width="100%">
			<tbody><tr>
			<td width="100%">
		
	<h1>Introducing Morphbank</h1>
<img src="http://morphbank.net/style/webImages/blueHR-trans.png" width="700" height="5" class="blueHR" />
Morphbank is an open web repository of images serving the
biological research community. It is currently being used to document and annotate
specimens in natural history collections, to voucher DNA sequence data, and to
share research results in disciplines such as taxonomy, morphometrics, comparative
anatomy, and phylogenetics. Morphbank can serve as a virtual reference collection
of named organisms or a resource for comparative morphological study; new use
cases are continuously added. Each image in the database is associated with fully
searchable text information, and images can be downloaded in several different
formats.<br />
<br />
Morphbank is open to any biologist interested in storing and
sharing digital images of organisms. A major advantage of Morphbank is that images
and data associated with them are maintained in a system based on open standards
and free software, facilitating the development of tools for image uploading, retrieval,
annotation, and related tasks. The <a href="<?php echo $config->domain; ?>About/Team" target="_blank">Morphbank team</a> is currently working on a range
of such tools. The Morphbank team is also working together with other developers
on connecting their software to the Morphbank system.<br />
<br />
Morphbank was established in 1998 by a Swedish-Spanish-American consortium of systematic entomologists and is currently housed at the
<a href="http://www.scs.fsu.edu/" target="_blank">School of Computational Science (SCS)
</a> at <a href="http://www.fsu.edu/" target="_blank">Florida State University</a> and mirrors at
other institutions around the world will soon be available. The images are currently
stored on two separate systems on the FSU campus, each a 1 TB RAID with tape
backup and the other with a 5 TB RAID with backup. Software used in the current
Morphbank system includes PHP<sup>&copy;</sup>, ImageMagick<sup>&reg;</sup>, MySQL<sup>&reg;</sup>, 
Apache<sup>&copy;</sup>, Java<sup>&#8482;</sup>, and
Javascript<sup>&#8482;</sup>.<br />
<br />
The Morphbank team at FSU is working together with others under the auspices of
<a href="http://www.tdwg.org/" target="_blank">TDWG</a> to develop a metadata standard for biological images. We're also teaming up
with other image database projects in developing the interoperability of web
repositories of biological images.<br />
<br />
<a href="javascript:window.close(); ?>Close Window</a>
<br />
<br />
<a href="<?php echo $config->domain; ?>About/Manual/index.php">Back to Manual Table of Contents</a>
			</td>
			</tr>
			</tbody></table>
			</div>
		
<?php
    include( $includeDirectory.'manual_comments.php');
	?>

	
