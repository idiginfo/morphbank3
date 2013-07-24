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
		<h1>System Requirements</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			
<p><strong>Morphbank currently uses ports 80/8080. To view the database, client
systems must be able to connect to these ports.</strong>
</p>
Windows<sup>&reg;</sup> Operating System
<br />
<ul>
<li>Computer: PC with at least a Pentium-class or equivalent processor.
<ul>
	<li>Microsoft<sup>&reg;</sup> Windows<sup>&reg;</sup> XP, or NT 4.0
</li>
<li>Minimum of 128 MB (RAM) memory minimum (512 or higher
recommended).
</li>
<li>Minimum of 70 MB of available hard disk space. The actual amount of
disk space required is dependent upon how your machine is configured to store temporary internet files.
</li>
<li>Recommend a high speed internet connection.
</li>
</ul>
</li>
<li>Monitor: SVGA color monitor; minimum 102X768x600 screen resolution
or higher recommended; minimum of 256 colors
</li>
<li>Printer: Not required
</li>
<li>Additional Software
<ul>
<li>Web Browser: Mozilla Firefox&#8482; version 2 or higher or Microsoft Internet Explorer (MSIE), version 7 or higher
</li>
<li>Settings: Morphbank employs the use of pop-up screens to display
various data screens. Pop-ups and cookies must be enabled for this
site. Also, Java&#8482; and Javascript&#8482; must be enabled in order to gain
full functionality. The newest versions of this software can be
downloaded at <a href="http://java.com/" target="_blank">http://java.com/</a> and <a href="http://javascript.com/" target="_blank">http://javascript.com/</a>
</li>
<li>Adobe<sup>&reg;</sup>Reader<sup>&reg;</sup>: Version 7.0 or higher. The Reader can be
downloaded at <a href="http://www.adobe.com/" target="_blank">http://www.adobe.com/</a>.
</li>
<li>Media Player: Windows(r)
</li></ul>
</li>
</ul>
<br />
Macintosh<sup>&reg;</sup> OS X Operating System
<ul>
<li>Computer: an Apple<sup>&reg;</sup> Macintosh or compatible capable of running
Macintosh OS X operating system 10.3 or newer
<ul>
<li>Mac OS X 10.3 or newer
</li>
<li>Memory: Minimum required by the operating system
</li>
<li>Hard Disk with 40-50 MB available disk space.
</li>
<li>Recommend a high speed internet connection
</li>
</ul>
</li>
<li>
Monitor: SV-GA capable 13" or larger, Macintosh compatible Screen
Resolution: 1024x768 or higher recommended; minimum 256 color depth
recommended.
</li>
<li>Printer: Not required
</li>
<li>Additional Software
<ul>
<li>Web Browser: Safari<sup>&reg;</sup> (OS X Default), or a Mac OS X compatible
version of Netscape<sup>&reg;</sup> Navigator or Commmunicator, or Mozilla Firefox&#8482;.
</li>
<li>Adobe<sup>&reg;</sup>Reader<sup>&reg;</sup>: Version 7.0 or higher. The Reader can be
downloaded at <a href="http://www.adobe.com/" target="_blank">http://www.adobe.com/</a>
</li>
<li>Settings: Morphbank employs the use of pop-up screens to display
various data screens. Pop-ups and cookies must be enabled for this
site. Also, Java&#8482; and Javascript&#8482; must be enabled in order to gain
full functionality. The newest versions of this software can be
downloaded at <a href="http://java.com/" target="_blank">http://java.com/</a> and <a href="http://javascript.com/" target="_blank">http://javascript.com/</a>
</li>
<li>Mac Media Player version 10.0
</li>
</ul>
</li>
</ul>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/userPrivileges.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
