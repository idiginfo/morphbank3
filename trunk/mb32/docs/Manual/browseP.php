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
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Browse - Publications</h1>
<div id=footerRibbon></div>
<br />
Publications are entered only by logged-in users but may be searched by anyone. They can be searched, sorted and edited. Any other morphbank
object can be linked to a publication. By selecting the <strong>Browse - Publications</strong> option, the user will
be presented with a list of all publications that are registered in the morphbank database.
<br />
<br />
<img src="ManualImages/browse_publications.png" hspace="20" />
<br /> 
<br />
<h3>Browse - Publications by Keywords and Sort the Results</h3>
<br />
<br />
To display a list of Publications based on a keyword(s) search, type the keyword(s) 
in the box and select <strong>Search</strong>. For example, to browse for all publications
 pertaining to <strong>Buffington</strong> (author name),
<strong>2007</strong> (year of publication); type in <strong>Buffington 2007</strong> and select
 <strong>Search</strong>.
<br />
<br />
<img src="ManualImages/search_publications.jpg" />
<br />
<br />
Sort the list of Publications
<br />
<br />
To sort the list of collections, select the Sort By criteria from the drop down
list(s). The more criteria selected, (up to 3 levels) the more refined the results. 
The subsequent page will display the collection list with the
initial Sort By option grouped together first, followed by groups of any of the
other sort criteria that was selected.
<br />
<br />
The Sort feature of morphbank is explained in detail in
 <a href="<?echo $config->domain;?>About/Manual/browseSort.php">Browse - Sort Search Results</a>
<br />
<br />
Sort criteria options for Browse - Publications include:
<ul>
<li><strong>Publication id</strong>: Unique Morphbank-issued identifier for a publication.
</li>
<li><strong>Publication title</strong>: the name of the article/book/chapter/...
</li>
<li><strong>Author</strong>: Name(s) of the author(s) of the publication.
</li>
<li><strong>Institution</strong>: 
</li>
<li><strong>Year</strong>: Publication Year.
</li>
<li><strong>Title</strong>:
</li>
</ul>
<p>Use the <img src="../../style/webImages/infoIcon.png" /> icon to open a <strong>Publication - Single Show</strong> window
to reveal additional data about a given publication. Users who have added Publications to morphbank and would like to Edit them may use the edit <img src="../../style/webImages/edit-trans.png" />
icon to access and edit them.
</p>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/browseSort.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>
