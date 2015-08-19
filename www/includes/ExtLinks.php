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

	function externalLinks($extSql, $ref){
		$extTypeRes = runQuery('SELECT linkTypeId, name FROM ExternalLinkType;');
		while($extLinkType = mysqli_fetch_array($extTypeRes, MYSQL_ASSOC)){

			$ids = $extLinkType['linkTypeId'];
			$types = $extLinkType['name'];
			$extTypes.= $ids. ':' .$types. ',';
	 	}

                //freeResult($extTypeRes);
		 $extTypes = rtrim($extTypes, ',');
		//echo $extSql;
	
		$linksResult = runQuery($extSql);
		$totalLinks =mysqli_num_rows($linksResult);

		if($totalLinks != 0){
	
			echo '  <input type = "hidden" name = "extNum" value = "' .$totalLinks.'">
					<input type = "hidden" name = "extLinksNum" value = 0>
					<a href= "javascript: onclick = showTable();" id = "addlinks" style = "display:none"> Add Links</a>

					<table id = "extlinks" cellpadding="0" cellspacing="0" border="0" width = "600" style = "display:inline">
					<tr><td><h3><b>External Links</b></h3></td>
					<td><a href= "javascript: onclick = addRow(\'extlinks\', \'' .$extTypes.'\');" name = "add" >
					<img src = "/style/webImages/plusAdd.gif" alt = "Add Row"  title="Click to Add a Row" /></a></td>
					<td><a href= "javascript: onclick = removeRow(\'extlinks\');" name = "remove" >
					 <img src = "/style/webImages/minusRemove.gif" alt = "Remove Row"  title="Click to Remove a Row" /></a></td>
					<td> &nbsp; </td>
					</tr>
					<tr><td><b>Type of Link <span class="req">*</span></b></td><td><b> Label<span class="req">*</span></b> </td>
					<td><b>Url<span class="req">*</span></b></td><td><b>Description </b></td></tr>';

			for($i = 1; $i <= $totalLinks; $i++){

				$link= mysqli_fetch_array($linksResult, MYSQL_ASSOC);
	
				foreach ($link as $column => $val){
	
					if($column ==  'linkId'){
						echo '<input type = "hidden" name = "' .$column.$i. '" value = "' .$val. '">';
						$delete = $val;
					}else if($column ==  'mbId')
						echo '<input type = "hidden" name = "' .$column.$i. '" value = "' .$val. '">';
					else if($column ==  'extLinkTypeId'){
						$extTypeRes = runQuery('Select linkTypeId, name FROM ExternalLinkType');
						echo '<td> <select id = "' .$column.$i. '" name="' .$column.$i. '" title = "Please Select the ExternalLinkType. Contact mbadmin to add more"
							<option value =\'0\'>--- Select Link Type ---</option>';

							while($types = mysqli_fetch_array($extTypeRes ,MYSQL_ASSOC)){
	
								if($types['linkTypeId'] == $val)
									echo '<option selected = "selected" value="' .$types['linkTypeId'].'">'
										.$types['name'].'</option>';
								else
									echo '<option value="' .$types['linkTypeId'].'">' .$types['name'].' </option>';
							}
					}else
						echo '<td><input type="text" id = "' .$column.$i. '" name="' .$column.$i. '" value="' .$val. '" onchange = "document.forms[0].flag.value = true;"></td>';
				}// end of foreach loop
				echo '<td>&nbsp; </td><td><a href="javascript: confirmDelete(\'' .$delete.'\', \'' .$ref. '\');" ><img src="/style/webImages/delete-trans.png" alt="delete" title="Delete Collection" width="16" height="16" /></a></td>';

				echo '</tr>';
			} //end of for totalLinks
		}else{
			echo '<a href= "javascript: onclick = showTable();" id = "addlinks"><img src = "/style/webImages/plusIcon.png" alt="" title="And a new link" /> (Add External Links)</a>
	
				<input type = "hidden" name = "extLinksNum" value = 0>
				<input type = "hidden" name = "extNum" value = "0">
				<table id = "extlinks" cellpadding="0" cellspacing="0" border="0" style="display:none;"  width = "600">

				<tr><td><h3><b>External Links</b></h3></td>
					<td><a href= "javascript: onclick = addRow(\'extlinks\', \'' .$extTypes. '\');" name = "add" >
					<img src = "/style/webImages/plusAdd.gif" alt = "Add Row"  title="Click to Add a Row" /></a>
					<a href= "javascript: onclick = removeRow(\'extlinks\');" name = "remove" >
					<img src = "/style/webImages/minusRemove.gif" alt = "Remove Row"  title="Click to Remove a Row" /></a></td>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td width = "25%"><b>Type <span class="req">*</span></b></td>
		                        <td width = "25%"><label title = "this text will be displayed as a link"><b>Label<span class="req">*</span></b></label></td>
					<td width = "25%"><b>Url <span class="req">*</span></b></td>
					<td width = "25%"><b>Description</b></td>
				</tr>
	                        <tr>
					<td width = "25%"> <select id = "type1" name="type1" title = "Select the link type from the list. Contact Morphbank admin group to add more.">
					<option value ="0">--- Select Link Type ---</option>';
			 		$extTypeRes = runQuery('Select linkTypeId, name FROM ExternalLinkType');
	
					while($types = mysqli_fetch_array($extTypeRes, MYSQL_ASSOC)){
	
						echo '<option value="' .$types['linkTypeId'].'">' .$types['name'].' </option>';
					}
	
					echo '<td width = "25%"><input type = "text" id = "label1" name = "label1" /></td>
                                		<td width = "25%"><input type = "text" id = "url1" name = "url1" title = "Please enter Absolute Url" /></td>
						<td width = "25%"><input type = "text" name = "description1" /></td>
						</tr></table>';
			}
	
                         freeResult($extTypeRes);
                         freeResult($linksResult);
	
		}

function Links($totalLinks){

        echo '<script language = "Javascript" type = "text/javascript">

                 function checkLinks(){

                        var checklist = "";';

                        for($i = 0; $i< $totalLinks; $i++){

                                echo '
                                        if(!document.forms[0].urlData' .$i. '.value){
                                                checklist += "Url' .$i. '" + "\n";
                                        }';
                        }

                        echo 'return checklist;
                }

        </script>';
}
?>
