<form id="<?php echo $frmId ?>" class="frmValidate" action="<?php echo $frmAction ?>" method="post">
<input type="hidden" name="pop" id="pop" value="<?php echo ($_GET['pop'] == 'yes' ? 'yes' : 'no'); ?>" />
<input type="hidden" id="frmPublication" value="publication" />
<input type="hidden" name="objId" id="objId" value="<?php echo $id; ?>" />
<table id="mytable" border="0" width="100%">
	<?php if ($isEdit) { ?>
	<tr>
		<td><b>Group Name: </b></td>
		<td><b><a href="/?id=<?php echo $array['groupid'] ?>"><?php echo $array['groupname'] ?></a></b></td>
	</tr>
	<?php } ?>
	<?php echo publicationTypesSelectTag($array['publicationtype']); ?>
	<tr id="doi">
		<td><b>DOI: </b></td>
		<td><input type="text" name="doi" size="26"
			value="<?php echo $array['doi']; ?>"
			title="Enter the DOI (if applicable) for this publication" /></td>
	</tr>
	<tr id="publication_title">
		<td><b>Publication title: <span id="pt" class="asterisk"></span></b></td>
		<td><input type="text" name="publicationtitle" size="45"
			value="<?php echo $array['publicationtitle']; ?>"
			title="Enter the publication title" /></td>
	</tr>
	<tr id="title">
		<td><b>Article title: <span id="at" class="asterisk"></span></b></td>
		<td><input type="text" name="title" size="45"
			value="<?php echo $array['title']; ?>"
			title="Enter the article title" /></td>
	</tr>
	<tr id="author">
		<td><b>Author(s): <span id="au" class="asterisk"></span></b></td>
		<td><input type="text" name="author" size="45"
			value="<?php echo $array['author']; ?>"
			title="Enter a coma separated list of name(s) of author(s) of the publication"></td>
	</tr>
	<tr id="year">
		<td><b>Published in year: <span id="ye" class="asterisk"></span></b></td>
		<td><input type="text" name="year" size="10"
			value="<?php echo $array['year']; ?>"
			title="Enter the year of publication" /></td>
	</tr>
	<?php echo createMonthFieldSelectTag($array['month']); ?>
	<tr id="day">
		<td><b>Published on day: <span id="yd" class="asterisk"></span></b></td>
		<td><input type="text" name="day" size="8"
			value="<?php echo $array['day']; ?>"
			title="Enter the day of publication" /></td>
	</tr>
	<tr id="number">
		<td><b>Journal/Techreport issue number: </b></td>
		<td><input type="text" name="number" size="4"
			value="<?php echo $array['number']; ?>"
			title="Enter the journal/techreport number" /></td>
	</tr>
	<tr id="series">
		<td><b>Series: </b></td>
		<td><input type="text" name="series" size="32"
			value="<?php echo $array['series']; ?>"
			title="Enter the name of the series of the set of books"></td>
	</tr>
	<tr id="organization">
		<td><b>Organization: </b></td>
		<td><input type="text" name="organization" size="45"
			value="<?php echo $array['organization']; ?>"
			title="Enter the name of the Organization sponsoring the conference" /></td>
	</tr>
	<tr id="school">
		<td><b>School: <span id="sc" class="asterisk"></span></b></td>
		<td><input type="text" name="school" size="45"
			value="<?php echo $array['school']; ?>"
			title="Enter the name of the School where the dissertation/thessis was written" /></td>
	</tr>
	<tr id="pages">
		<td><b>Pages: <span id="pg" class="asterisk"></span></b></td>
		<td><input type="text" name="pages" size="32"
			value="<?php echo $array['pages']; ?>"
			title="Enter the page or range of pages"></td>
	</tr>
	<tr id="chapter">
		<td><b>Chapter number/title: <span id="ch" class="asterisk"></span></b></td>
		<td><input type="text" name="chapter" size="45"
			value="<?php echo $array['chapter']; ?>"
			title="Enter the chapter number that you want to reference" /></td>
	</tr>
	<tr id="volume">
		<td><b>Volume: <span id="vo" class="asterisk"></span></b></td>
		<td><input type="text" name="volume" size="11"
			value="<?php echo $array['volume']; ?>"
			title="Enter the volume number of the journal" /></td>
	</tr>
	<tr id="edition">
		<td><b>Edition: </b></td>
		<td><input type="text" name="edition" size="16"
			value="<?php echo $array['edition']; ?>"
			title="Enter the edition stated in words, ex: second" /></td>
	</tr>
	<tr id="editor">
		<td><b>Editor(s): <span id="ed" class="asterisk"></span></b></td>
		<td><input type="text" name="editor" size="45"
			value="<?php echo $array['editor']; ?>"
			title="Enter the name(s) of the editor(s). This is a required field for books"></td>
	</tr>
	<tr id="howpublished">
		<td><b>How published: </b></td>
		<td><input type="text" name="howpublished" size="45"
			value="<?php echo $array['howpublished']; ?>"
			title="Enter how the publication was published"></td>
	</tr>
	<tr id="institution">
		<td><b>Institution: </b></td>
		<td><input type="text" name="institution" size="45"
			value="<?php echo $array['institution']; ?>"
			title="Enter the name of the Institution which authorized publishing" /></td>
	</tr>
	<tr id="publisher">
		<td><b>Publisher: <span id="pu" class="asterisk"></span></b></td>
		<td><input type="text" name="publisher" size="45"
			value="<?php echo $array['publisher']; ?>"
			title="Enter the publisher name" /></td>
	</tr>
	<tr id="address">
		<td><b>Publisher address: <span id="pa" class="asterisk"></span></b></td>
		<td><input type="text" name="address" size="45"
			value="<?php echo $array['address']; ?>"
			title="Enter the publisher address if known" /></td>
	</tr>
	<tr id="isbn">
		<td><b>ISBN: </b></td>
		<td><input type="text" name="isbn" size="45"
			value="<?php echo $array['isbn']; ?>"
			title="Enter the ISBN of the publication (if known/applicable)" /></td>
	</tr>
	<tr id="issn">
		<td><b>ISSN: </b></td>
		<td><input type="text" name="issn" size="45"
			value="<?php echo $array['issn']; ?>"
			title="Enter the ISSN of the publication (if known/applicable)" /></td>
	</tr>
	<tr id="note">
		<td><b>Comments: </b></td>
		<td>
			<textarea name="note" cols="45" rows="4" 
				title="Enter additional information to help the reader" /><?php echo $array['note']; ?></textarea></td>
	</tr>
	<tr>
		<td><b>Date To Publish (YYYY-MM-DD): </b></td>
		<?php $pubDate = explode(" ", $datetopublish); ?>
		<td><input type="text" name="DateToPublish" 
				value="<?php echo (empty($pubDate[0]) ? date('Y-m-d', strtotime('+6 months')) : $pubDate[0]); ?>" 
				size="10" /></td>
	</tr>
	<?php echo getContributorSelectTag($contributor, $groupid); ?>
</table>
<?php 
echo extLinksRefs($id, $ref);
echo frmSubmitButton($frmButtonText);
?>
</form>
