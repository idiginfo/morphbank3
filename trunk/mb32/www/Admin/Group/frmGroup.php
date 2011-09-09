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
?>

<form id="frmGroup" class="frmValidate" name="group" method="post" action="modifyGroup.php">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<table width="500px">
	<tr>
    	<td><b>Group Name</b></td>
    	<td><?php echo $row['groupname'] ?></td>
    </tr>
   	<tr title="Users assigned to this group">
   		<td valign="middle"><b>Group Users</b></td>
   		<td><textarea name="list" rows="8" cols="35" readonly><?php echo $userList ?></textarea></td>
   	</tr>
   	<tr>
   		<td><b>Group Status</b></td>
        <td><select name="groupstatus" title="Public or Not public"><?php echo $statusOptions; ?></select></td>
    </tr>
</table>
<br/><br/>
<table width="425px" align="right">
	<tr>
    	<td>
    		<input type="submit" class="button smallButton" title="Click to save the changes made to the group" value="Update" />
    		<a href="/Admin/Group/editGroupMembers.php?id=<?php echo $row['id'] ?>" class="button largeButton" title="Click to modify information about members in the group"><div>Modify members</div></a>
			<a href="/Admin/Group/addReviewer.php?&name=<?php echo $row['groupname']?>" class="button largeButton" title="Click to add a reviewer account"><div>Create reviewer</div></a>
			<a href="<?php echo $returnUrl ?>"  class="button smallButton" title="Click to return to previous page"><div>Return</div></a>
		</td>
	</tr>
</table>
</form>
