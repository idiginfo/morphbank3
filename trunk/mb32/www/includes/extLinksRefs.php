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

/**
 * Builds external links and unique references
 * @global imgDirectory
 * @param integer $id default=NULL, id of object
 * @param string $ref default=NULL, url for deleting reference
 */
function extLinksRefs($id = NULL, $ref = NULL) {

	$db = connect();
	
	$extTypeRes = $db->query("SELECT linkTypeId, name FROM ExternalLinkType where linkTypeId != 4");
	if($message = isMdb2Error($extTypeRes, 'select link type ids')) {
		die($message);
		exit;
	}

	$extTypes = '';
	$extArray = array();
	while($extLinkType = $extTypeRes->fetchRow(MDB2_FETCHMODE_ASSOC)){
		$extArray[] = $extLinkType;
		$ids = $extLinkType['linktypeid'];
		$types = $extLinkType['name'];
		$extTypes.=	$ids. ':' .$types. ',';
		$extTypesOptions .= '<option value="' .$ids.'">' .$types.'</option>' . "\n";
	}
	$extTypes = rtrim($extTypes, ',');
	$extTypeRes->free();
	
	// Existing object, check for links and references
	if(!empty($id)){
		$sql = "SELECT linkId, mbId, extLinkTypeId, Label, urlData, description, externalId FROM ExternalLinkObject WHERE mbId = ? and extLinkTypeId != 4;";
		$linksResult = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($linksResult, 'selecting links');
		$totalLinks = count($linksResult);
		
		$sql = "SELECT linkId, mbId, extLinkTypeId, description, externalId FROM ExternalLinkObject WHERE mbId = ? and extLinkTypeId = 4;";
		$refsResult = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($refsResult, 'select references');
		$totalRefs = count($refsResult);
	}

    $rows = '';
    $display = "none";
	if ($totalLinks > 0) {
        $display = "";
        $i=1;
        foreach ($linksResult as $link) {
            $rows .= '<tr>';
            $rows .= '<td>';
            $rows .= '<input type="hidden" name="linkId[' . $link['linkid'] . ']" value="' . $link['linkid'] . '" />';
            $rows .= '<select name="extLinkTypeId[' . $link['linkid'] . ']" id="type_' . $i . '" title="Please Select the ExternalLinkType. Contact mbadmin to add more">';
            $rows .= '<option value ="">--- Select Link Type ---</option>';
            foreach($extArray as $ext){
                $rows .= ($ext['linktypeid'] == $link['extlinktypeid']) ? '<option selected = "selected" value="' .$ext['linktypeid'].'">' . $ext['name'] . '</option>' : '<option value="' .$ext['linktypeid'].'">' .$ext['name'].' </option>';
            }
            $rows .= '</select></td>';
            $rows .= '<td><input type="text" name="linkLabel[' . $link['linkid'] . ']" id="label_' . $i . '" value="' . $link['label'] . '" /></td>';
            $rows .= '<td><input type="text" name="linkUrlData[' . $link['linkid'] . ']" id="url_' . $i . '" value="' . $link['urldata'] . '" /></td>';
            $rows .= '<td><input type="text" name="linkDescription[' . $link['linkid'] . ']" id="desc_' . $i . '" value="' . ($link['description'] == 'null' ? '' : $link['description']) . '" /></td>';
            $rows .= '<td><a href="javascript: confirmDelete(\'' . $link['linkid'] . '\', \'' . $ref . '\');" ><img src="/style/webImages/delete-trans.png" alt="delete" title="Delete Collection" width="16" height="16" /></a></td>';
            $rows .= '</tr>';
            $i++;
        }
	} else {
        $rows .= '
			<tr>
			    <input type="hidden" name="linkId[]" />
			    <td width="25%">
					<select name="extLinkTypeId[]" id="type_1" title="Select the link type from the list. Contact Morphbank admin group to add more.">
						<option value="">--- Select Link Type ---</option>
						' . $extTypesOptions . '
					</select>
				</td>
				<td width="25%"><input type="text" name="linkLabel[]" id="label_1" /></td>
				<td width="25%"><input type="text" name="linkUrlData[]" id="url_1" title="Please enter Absolute Url" /></td>
				<td><input type="text" name="linkDescription[]" id="desc_1" /></td>
			</tr>';
    }
    $html = '
		<br /><br />
		<a href="" class="toggleTable"><img src="/style/webImages/plusIcon.png" alt="" title="And a new link" /> (Add External Links)</a>
		<table id="extlinks" title="3" cellpadding="0" cellspacing="3" border="0" width="600" style="display:' . $display . '">
			<tr>
				<td><h3><b>External Links</b></h3></td>
				<td>
					<a href="" class="addRow" name="add">
					<img src="/style/webImages/plusAdd.gif"	alt="Add Row" title="Click to Add a Row" /></a>
					&nbsp;&nbsp;
					<a href="" class="removeRow" name="remove">
					<img src="/style/webImages/minusRemove.gif"	alt="Remove Row" title="Click to Remove a Row" /></a>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="25%"><b>Type of Link <span class="req">*</span></b></td>
				<td width="25%"><label title="Type text to appear on website for the url"><b>Label <span class="req">*</span></b></label></td>
				<td width="25%"><b>Url <span class="req">*</span></b></td>
				<td width="25%"><b>Description</b></td>
			</tr>';
    $html .= $rows;
    $html .= '</table><br />';

    $rows = '';
    $display = "none";
	if ($totalRefs != 0) {
        $display = "inline";
        $i=1;
        foreach ($refsResult as $link) {
            $rows .= '<tr>';
            $rows .= '<td>';
            $rows .= '<input type="hidden" name="reflinkId[' . $link['linkid'] . ']" id="' . $i . '"  value="' . $link['linkid'] . '">';
            $rows .= '<input type="text" name="refDescription[' . $link['linkid'] . ']" id="rdesc_' . $i . '" value="' . ($link['description'] == 'null' ? '' : $link['description']) . '" /></td>';
            $rows .= '<td><input type="text" name="refExternalId[' . $link['linkid'] . ']" id="ext_' . $i . '" value="' . $link['externalid'] . '" /></td>';
            $rows .= '<td><a href="javascript: confirmDelete(\'' . $link['linkid'] . '\', \'' . $ref . '\');" ><img src="/style/webImages/delete-trans.png" alt="delete" title="Delete Collection" width="16" height="16" /></a></td>';
            $rows .= '</tr>';
            $i++;
        }
	} else {
        $rows .= '
			<tr>
			    <input type="hidden" name="reflinkId[]" />
				<td><input type="text" name="refDescription[]" id="rdesc_1" /></td>
				<td><input type="text" name="refExternalId[]" id="ext_1" /> </td>
			</tr>
		</table>';
	}
    $html .= '
			<br /><br />
			<a href="" class="toggleTable"><img src="/style/webImages/plusIcon.png" alt="" title="And a new reference" /> (Add External References)</a>
			<table id="extrefs" title="3" cellpadding="3" cellspacing="0" border="0" width="320" style="display:'.$display.'">
				<tr>
					<td><h3><b>External References</b></h3></td>
					<td>
						<a href="" class="addRow" name="add">
						<img src="/style/webImages/plusAdd.gif"	alt="Add Row" title="Click to Add a Row" /></a>
						&nbsp;&nbsp;
						<a href="" class="removeRow" name="remove">
						<img src="/style/webImages/minusRemove.gif" alt="Remove Row" title="Click to Remove a Row" /></a>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><label title="Type label for the provided unique identifier to be displayed, Example: External Id"><b>Description <span class="req">*</span></b></label></td>
					<td><label title="Enter your unique external identifier for this image/(view/specimen/locality/publication/taxonname)"><b>Unique Reference ID <span class="req">*</span></b></label></td>
					<td>&nbsp;</td>
				</tr>';
    $html .= $rows;
    $html .= '</table>';

	return $html;
}

/**
 * Update existing external links
 * @param integer $id Id of object
 * @param array $array post array
 * @return bool
 */
function updateLinks($id, $array){

    $db = connect();
    foreach ($array['linkId'] as $key => $value) {

        $linkId = trim($array['linkId'][$key]);
        $type = trim($array['extLinkTypeId'][$key]);
        $label = trim($array['linkLabel'][$key]);
        $url = trim($array['linkUrlData'][$key]);
        $description = trim($array['linkDescription'][$key]);

        if (!empty($linkId))
        {
            $params = array($linkId, $id, $type, $db->quote($label), $db->quote($url), $db->quote($description), $db->quote(null));
            $result = $db->executeStoredProc('ExternalLinkObjectUpdate', $params);
            if(isMdb2Error($result, 'External link update')) {
                return false;
            }
            clear_multi_query($result);
        }
        elseif (empty($linkId) && !empty($type))
        {
            $params = array($id, $type, $db->quote($label), $db->quote($url), $db->quote($description), $db->quote(null));
            $result = $db->executeStoredProc('ExternalLinkObjectInsert', $params);
            if(isMdb2Error($result, 'External link insert')) {
                return false;
            }
            clear_multi_query($result);
        }
    }

	return true;
}

/**
 * Update unique references
 * @param int $id Id of object
 * @param array $array post array
 * @return bool
 */
function updateReferences($id, $array){

	$db = connect();
    foreach ($array['reflinkId'] as $key => $value) {

        $linkId = trim($array['reflinkId'][$key]);
        $description = trim($array['refDescription'][$key]);
        $externalId = trim($array['refExternalId'][$key]);

        if (!empty($linkId))
        {
            $params = array($linkId, $id, 4, $db->quote(null), $db->quote(null), $db->quote($description), $db->quote($externalId));
            $result = $db->executeStoredProc('ExternalLinkObjectUpdate', $params);
            if(isMdb2Error($result, 'External reference update')) {
                return false;
            }
            clear_multi_query($result);
        }
        elseif (empty($linkId) && !empty($externalId))
        {
            $params = array($id, 4, $db->quote(null, 'integer'), $db->quote(null), $db->quote($description), $db->quote($externalId));
            $result = $db->executeStoredProc('ExternalLinkObjectInsert', $params);
            if(isMdb2Error($result, 'External reference insert')) {
                return false;
            }
            clear_multi_query($result);
        }
    }

	return true;
}
