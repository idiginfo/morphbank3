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

	<div class="main">	<div class="mainGenericContainer" style="width: 820px;">
		<!--change the header below -->
		<h1>Introduction</h1>
		<br /><br />
			<table class="manualContainer" cellspacing="0" width="100%">
			<tbody><tr>
			<td width="100%">
		
<h1>How to Submit Data to MorphBank</h1>
<img src="http://morphbank.net/style/webImages/blueHR-trans.png" width="700" height="5" class="blueHR" />
There are several ways in which a user or a group of users can upload
images to morphbank:
<br /><br />
1. Using the existing web interface-Images are uploaded separately along
with their associated text information by filling out a web form. This
requires a username and password.
<br /><br />
2. Automatic uploading from an image-In the fall of 2006, morphbank will
offer a convenient uploading of images from a platform-independent client
developed in Java. We're also working together with several development
teams in designing project-specific clients that can help research teams to
upload images to morphbank as part of their normal workflow.
<br />
<br />
3. Delivered uploading - morphbank offers a service called delivered
uploading. We provide an already prepared Excel<sup>&copy;</sup> Data Entry Workbook
and the corresponding User's Manual. The submitters can deliver to
morphbank a CD or DVD containing images and an Excel<sup>&copy;</sup> Data Entry
Workbook populated with information.
<br /><br />
How to save and deliver the files
<br /><br />
For each image set with different release dates create separate
folders. Name each folder: ImageCollection1, ImageCollection2, etc.
Inside each folder, place the excel file named mBdet.xls and all
associated images. Be sure to have the correct image file names as
they appear in mBdet.xls. You can deliver your data to morphbank
admin team two ways:
<br /><br />
Save all image collection files on a CD or DVD (make certain the CD
is finalized so morphbank can retrieve the images from the disk
[read/write accessible]). Label the outside of the disk with contributor
name (person authorized to release images) and project. Send to the
morphbank admin team by land mail (See the address below). Make
sure to keep a backup copy for yourself and send with proper
postage and protection.
<br /><br />
Land Mail Address for delivered uploading<br />
Morphbank Admin. Group<br />
C/O Karolina Maneva-Jakimoska<br />
Mail Code 4120<br />
Florida State University<br />
Tallahassee, FL 32306-4120<br />
<br />
Morphbank provides a secure ftp upload service as an alternative
to mailing CDs/DVDs. Registered morphbank users may email
<a href="mailto:mbadmin@scs.fsu.edu">mbadmin@scs.fsu.edu</a> for an ftp upload password, username and
address. Although you should be familiar with ftp, only minimal
experience is necessary to use this service. The morphbank server
may be accessed through any graphic ftp program, terminal or the
command line.
<br />
<br />
Once a morphbank administrator has sent account information, users
may begin uploading. Be sure to use ftp (file transfer protocol), not
sftp (simple file transfer protocol which is a mail transfer
protocol for email messages) as the transfer protocol. There is no
limit on the number of files uploaded at one time. Once the images
are on the server, notify <a href="mailto:mbadmin@scs.fsu.edu">mbadmin@scs.fsu.edu</a> of the completion;
include a message requesting that the account remain open if
needed. If the account is not requested to remain open, morphbank
will delete the files off the server once they have been received. The
ftp upload password will no longer be valid.
<br />
<br />
This service may be used once or many times depending on the user's specific needs.<br />
<br />
Note: Users should only release data into morphbank that is 	 
appropriate for world-wide release. For example, if an image of an 	 
endangered or protected specimen includes annotations as to its 	 
specific locality, that information should be masked before sending the 	 
image to morphbank. When propagating the "add locality" screen of 	 
that specimen, care should be taken to avoid precise locality details (i.e. 	 
enter the collection county as opposed to the exact area within that 	 
county where the specimen was collected.
<br />
<br />
<a href="javascript:window.close();">Close Window</a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/index.php">Back to Manual Table of Contents</a>
			</td>
			</tr>
			</tbody></table>
			</div>
		
<?php
    include( $includeDirectory.'manual_comments.php');
	?>
