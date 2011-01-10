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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

function getFormFieldRow($label, $title, $name, $type, $size, $value, $required,$title=null) {
	$tag = '<tr>';
	$tag .= getFormField($label, $title, $name, $type, $size, $value, $required, $title);
	$tag .= '</tr>'."\n";
	return $tag;
}

function getFormField($label, $title, $name, $type, $size, $value, $required,$title=null){
	$tag = getFormLabel($label, $required);
	$tag .= '<td>';
	$tag .= getFormFieldInput($name, $type, $size, $value, $required,$title);
	$tag .= '</td>';
	return $tag;

}

function getFormFieldInput($name, $type, $size, $value, $required, $title=null){
	$tag .= '<input type="'.$type.'" name="'.$name.'"';
	if($required){
		$tag.= ' class="required"';
	}
	if (!empty($value))$tag .= ' value ="'.$value.'"';
	if (!empty($size)) $tag .= ' size="'.$size.'"';
	if (!empty($title)) $tag .= ' title="'.$title.'"';
	$tag .= ' />';
	return $tag;
}

function getFormHiddenField($name, $value){
	$tag = '<input type="hidden" name="'.$name.'" value="'.$value.'">';
	return $tag;
}

function getFormPasswordField($required = false){
	$req = getRequired($required);
	$new = $required === true ? '' : 'New';
	$tag ='<tr><td><b>'.$new.' Password:</b> '.$req.'</td>';
	$tag .= '<td><input type="password" id="pin" name="pin" /></td></tr>';
	$tag .= '<tr><td><b>Confirm Password:</b> '.$req.'</td>';
	$tag .= '<td><input type="password" id="confirm_pin" name="confirm_pin"/></td>';
	$tag .= '</tr>';
	return $tag;
}

function getGroupSelect($label, $name, $userId, $prefGroup, $required){
	$db = connect();
	$query = "Select Groups.groupName,Groups.id from Groups, UserGroup where UserGroup.user ="
	. $userId . " and Groups.id = UserGroup.groups";
	$result = $db->query($query);
	$tag = '<tr>';
	$tag .= getFormLabel($label, $required);
	$tag .= '<td><select name="'.$name.'">';
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)){
		$tag .= '<option value="' . $row['id'] . '"';
		if ($prefGroup == $row['id']) $tag .= ' selected="selected" ';
		$tag .= '>' . $row['groupname'] . '</option>';
	}
	$tag .= '</select></td>';
	$tag .= '</tr>';
	return $tag;
}

function getFormSelector($label, $title, $name, $type, $size, $value, $required,$selectTitle, $alt, $action, $selectField, $selectValue){
	$tag = '<tr>';
	$tag = getFormLabel($label, $required);
	$tag .= '<td>';
	$tag .= getFormFieldInput($name, $type, $size, $value, $required);
	$tag .= ' ';
	$tag .= getFormSelectObjectTag($selectTitle, $alt, $action, $selectField, $selectValue);
	$tag .= '</td></tr>';
	return $tag;
}
function getFormSelectObjectTag($title, $alt, $action, $field, $value){
	
	$tag = '<a href="javascript:formname=\'group\';'.$action.';">';
	$tag .= '<img src="/style/webImages/selectIcon.png" border="0" title="';
	$tag .= $title;
	$tag .= '" alt="'.$alt.'"/></a>';
	$tag .= '<input type="hidden" name ="'.$field.'" value ="'.$value.'"/>';
	return $tag;
}

function getFormLabel($label, $required){
	$tag = '<td><b>'.$label.':</b>';
	$tag .= getRequired ($required);
	$tag .= '</td>';
	return $tag;
}

function getRequired($required){
	if ($required) {
		$tag = '<span style="color:red">*</span>';
		return $tag;
	}
	return '';
}

$states = array(
//'sym' => 'state',
	'' => 'none', 'AL' =>'Alabama', 'AK' =>'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado',
		'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii',
		'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana',
		'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
		'MO' => 'Missouri', 'MT' => 'Montana', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NM' => 'New Mexico', 'NY' => 'New York',
		'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
		'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
);

function getStateSelector($label, $title, $name, $value, $required) {
	global $states;
	
	$tag = '<tr>'."\n";
	$tag .= getFormLabel($label, $required);
	$tag .= '<td><select name="'.$name.'" title="'.$title.'">'."\n";
	foreach ($states as $sym => $state){
		$tag .= '<option value="'.$sym.'"';
		if ($sym == $value) $tag .= ' selected="selected" ';
		$tag .= '>'. $state . '</option>'."\n";
	}
	$tag .= '</select></td>'."\n";
	$tag .= '</tr>'."\n";
	return $tag;
}

function getCountrySelector($label, $title, $name, $value, $required) {
	$db = connect();
	$results = $db->query("select * from Country order by description");
	$tag = '<tr>'."\n";
	$tag .= getFormLabel($label, $required);
	$tag .= '<td><select name="'.$name.'" title="'.$title.'">'."\n";
	$tag .= '<option value="">-- Select --</option>'."\n";
	while($row = $results->fetchRow(MDB2_FETCHMODE_ASSOC)){
		$tag .=  '<option value="'.$row['description'].'"';
		if ($value == $row['description']) $tag .= " selected='selected' ";
		$tag .= '>' . $row['description'] . '</option>'."\n";
	}
	$tag .= '</select></td>'."\n";
	$tag .= '</tr>'."\n";
	return $tag;
}

