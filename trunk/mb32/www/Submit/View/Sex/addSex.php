<?php 
function addSex(){

        global $objInfo;

?>

<form name ="addSex" method="post" action="commitSex.php">

   <h1><b>Add Sex</b></h1>
   <br /><br />

   <table>
     <tr>
	<td><b>Sex</b></td>
	<td align="left"><input type="text" name="Sex" size="20" maxlength="64" /></td>
     </tr>
     <tr>
	<td><b>Description</b></td>
	<td align="left"><input type="text" name="description" size="40" maxlength="255" /></td>
     </tr>
   </table>

   <br /> 

   <table> 
     <tr>
        <td align = "right" width = "25%"> <a href="javascript: checkit();" class="button smallButton"><div>Submit</div></a>
        <a href="javascript: window.close();" class="button smallButton"><div>Close</div></a></td>
     </tr>
   </table>
</form>

<script language = "JavaScript" type ="text/javascript">

        function checkit() {
                var checklist ='';
                var flag = true;
                        box = document.forms[0].elements[0];
                        if (!box.value){ //checklist = box.name + '\n';
	                        alert('You have not filled in ' + box.name + '!');
	                        box.focus();
	                        flag = false;
                 }
                 if(flag)
                        document.addSex.submit();
          }

</script>
<?}?>
