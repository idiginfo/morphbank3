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

/*
 * File name: textarea_name_form.php
 * script that returns the wildcard matches from mb Tree based on scientific name
 * @author Katja Seltmann moon@begoniasociety.org
 *
 *
 *
 */
function main_namematch($link,  $objInfo) {

	$names = $_REQUEST['name_match'];
	echo '<div class="mainGenericContainer" style="width:960px;">';
	echo '<form action="" method="post">';
	echo '<table class="blueBorder" cellpadding="5" cellspacing="0" width="100%"><tr><td>';

	if (empty($names)) {
		echo '<table width="100%" cellpadding="0" cellspacing="0">';
		echo '<tr><td>';
		echo '<p>Enter a list of search phrases, each on a new line. The search will return information about the matching taxon names.</p>';
		echo '<br />The syntax of each search phrase follows the Mysql database fulltext syntax. <a href="#example">View Examples</a> or '
				.'<a href="http://dev.mysql.com/doc/refman/5.1/en/fulltext-boolean.html" target="_blank">MySql developer reference</a>' 
				.'<br />A plus or minus (+/-) on the beginning of a word means that the word is required to be present or absent, resp.'
				.'<br />An asterisk (*) at the end of a word is the wild card operator.';
		echo '<br /><br />';
		echo 'When entering a list of taxon names, putting them in quotation marks will find the matches. The output (in html and CSV) 
				includes the search term, scientific name, kingdom, parent, rank, tsn, taxon author, name source, accept, image count 
				and parents columns. Name source, if populated, explains what taxonomic name server or group holds the information about 
				the name itself. The accept column concerns the status of the taxon name as far as ITIS is concerned. In the parents 
				column, one can see the current taxonomic hierarchy in Morphbank for that taxon name.';
		echo '<br /><br />';
		echo '<b>Kingdom</b><br />';
		echo '<input type="radio" name="kingdom_id" value="0" checked />None&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="1" />Monera&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="2" />Protozoa&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="3" />Plantae&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="4" />Fungi&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="5" />Animalia&nbsp;&nbsp;&nbsp;';
		echo '<input type="radio" name="kingdom_id" value="6" />Chromista&nbsp;&nbsp;&nbsp;';
		echo '<br /><br /></td></tr>';
		echo '<tr><td><br/><textarea name="name_match" rows="10" cols="90">';
		echo "+chromis +ab*\n";
		echo 'hymenoptera';
		echo '</textarea></td></tr>';
		echo '<tr><td align="left"><br /><br /><a href="javascript: document.forms[0].submit();" class="button smallButton"><div>Submit</div></a></td></tr>';
		echo '</table>';
		echo '<br /><br />';
		echo '<a name="example"></a><br />';
		echo 'The following examples demonstrate some search strings that use boolean full-text operators:';
		echo '<ul>';
		echo '<li style="margin-bottom:10px;"><b>apple banana</b><br />Find rows that contain at least one of the two words.</li>';
		echo '<li style="margin-bottom:10px;"><b>+apple +juice</b><br />Find rows that contain both words.</li>';
		echo '<li style="margin-bottom:10px;"><b>+apple macintosh</b><br />Find rows that contain the word "apple", but rank rows higher if they also contain "macintosh".</li>';
		echo '<li style="margin-bottom:10px;"><b>+apple -macintosh</b><br />Find rows that contain the word "apple" but not "macintosh".</li>';
		echo '<li style="margin-bottom:10px;"><b>+apple ~macintosh</b><br />Find rows that contain the word "apple", but if the row also contains the word "macintosh", rate 
				it lower than if row does not. This is softer than a search for +apple -macintosh, for which the presence of "macintosh" 
				causes the row not to be returned at all.</li>';
		echo '<li style="margin-bottom:10px;"><b>+apple +(&gt;turnover &lt;strudel)</b><br />Find rows that contain the words "apple" and "turnover", or "apple" and "strudel" 
				(in any order), but rank "apple turnover" higher than "apple strudel".</li>';
		echo '<li style="margin-bottom:10px;"><b>apple*</b><br />Find rows that contain words such as "apple", "apples", "applesauce", or "applet".</li>';
		echo '<li style="margin-bottom:10px;"><b>"some words"</b><br />Find rows that contain the exact phrase "some words" (for example, rows that contain "some words of 
				wisdom" but not "some noise words"). Note that the quotation characters that enclose the phrase are operator characters that 
				delimit the phrase. They are not the quotation marks that enclose the search string itself.</li>';
		echo '</ul>';
	} elseif (!empty($names)) {
		echo '<tr><td align="left"><br /><br /><a href="javascript: document.forms[0].submit();" class="button smallButton"><div>Reset</div></a></td></tr>';
		echo '<tr><td>';
		//include the process of the data for printing inside the form
		echoNameMatch($names);
		echo '</td></tr>';
		echo '<tr><td align="left"><br /><br /><a href="javascript: document.forms[0].submit();" class="button smallButton"><div>Reset</div></a></td></tr>';
		echo '</table>';
	}
	echo '</td></tr></table></form>';
	echo '</div>';
}//close main_namematch() function
?>
