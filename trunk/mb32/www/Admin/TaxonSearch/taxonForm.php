#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<form name="main" class="frmValidate" id="<?php echo $frmId ?>" action="<?php echo $frmAction ?>" method="post">
<input type="hidden" name="pop" value="<?php echo $_GET['pop'] ?>" />
<input type="hidden" name="maintsn" value="<?php echo $main_tsn ?>" />
<input type="hidden" name="tsn" value="<?php echo $tsn ?>" />
<input type="hidden" name="objId" value="<?php echo $baseObjectId ?>" />
<input type="hidden" name="kingdomid" id="kingdomid" value="<?php echo $kingdom_id ?>" />
<?php printTaxonNames($parent_tsn) ?>
<br />
<table width="100%">
	<?php echo taxaNameTypeSelect($array['nametype']); ?>
	<?php if ($isUpdate) { ?>
	<tr>
		<td width="25%px"><b>Parent Taxon Id/Name: <span style="color:red">*</span></b></td>
		<td>
			<input type="text" name="parent_tsn" value="<?php echo $parent_tsn ?>" size="16" title="Curent parent TSN" />
                <b> / </b>
                <input type="text" name="parentname" size="26" value="<?php echo $parentName ?>" title="Curent parent Name" readonly />
                &nbsp;&nbsp;
                <a href="javascript:openPopup('/Admin/TaxonSearch/index.php?pop=YES&searchonly=0&tsn=<?php echo $objInfo->getUserTSN(); ?>')">
			<img src="/style/webImages/selectIcon.png" /></a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td width="30%"><b>Taxon Name: <span style="color: red">*</span></b></td>
		<td>
			<?php
			if ($parentRank >= 180){
				echo $parentName . "  ";
			}
			?>
			<input type="text" name="sname" size="20" value="<?php echo $newName ?>" title="Enter the Taxon name"/>
		</td>
	</tr>
</table>
<table width="100%">
	<?php printRankSelector($parent_tsn, $rank_id); ?>
	<tr>
		<td width="30%px"><b>Name Source: </b></td>
		<td>
			<input type="text" name="namesource" size="40" value="<?php echo $array['namesource'] ?>"/>
		</td>
	</tr>
	<tr>
		<td width="30%px"><b>Publication Id / Name:</b></td>
		<td>
			<input type="text" name="reference_id" size="8" value="<?php echo $reference_id ?>"
					title="Select Publication Id/ Name, add one if it does not exist in MorphBank" /><b> / </b>
			<input type="text" name="reference" size="50" readonly
					title="Select Literature Reference Id/Name, add one if it does not exist in MorphBank"
					value="<?php echo $reference ?>" />
			&nbsp;<a href="javascript:openPopup('/Browse/ByPublication/?pop=YES')">
			<img src="/style/webImages/selectIcon.png" /></a>
		</td>
	</tr>
	<tr>
		<td width="30%px"><b>Taxon Author(s), Year:</b></td>
		<td>
			<input type="text" name="taxon_author" id="taxon_author" class="autocomplete taxonAuthor" size="45" value="<?php echo $taxon_author ?>"
					onmouseover="startPostIt( event, '<?php echo $postItContent ?>')"
					onmouseout="stopPostIt()" />
		</td>
	</tr>
	<tr>
		<td width="30%px"><b>Page(s): </b></td>
		<td>
			<input type="text" name="pages" size="12" title="Enter the page, range of pages relevant to taxon"
					value="<?php  echo $array['pages'] ?>"/>
		</td>
	</tr>
	<?php echo getContributorSelectTag($contributor, $groupId) ?>
	<?php echo getStatusSelectTag($array['status']) ?>
	<tr>
		<td width="30%px"><b>Comments: </b></td>
		<td><textarea cols="50" rows="4" name="comments"><?php echo $array['comments'] ?></textarea></td>
	</tr>
</table>
<?php 
echo displayVernacular($tsn);
echo extLinksRefs($baseObjectId, $ref);
echo frmSubmitButton($frmButton);
?>
</form>
