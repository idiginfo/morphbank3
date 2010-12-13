<form id="frmGroup" class="frmValidate" name="group" method="post" action="modifyGroup.php">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<table width="500px">
	<tr>
    	<td><b>Group Name <span class="req">*</span></b></td>
    	<td><input type="text" size="40" name="groupname" value ="<?php echo $row['groupname'] ?>" /></td>
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
