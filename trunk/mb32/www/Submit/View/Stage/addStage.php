<?php 
function addStage(){

?>

<form name ="addStage" method="post" action="commitStage.php">

   <h1><b>Add Developmental Stage</b></h1>
   <br /><br />

   <table>
     <tr>
	<td><b>Developmental Stage</b></td>
	<td align="left"><input type="text" name="DevelopmentalStage" size="20" maxlength="64" /></td>
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
                  var flag= true;

                  for (i=0;i<1;i++) {
			
		  	box = document.forms[0].elements[i];
		  	if (!box.value){ checklist += box.name + '\n';
		              flag = false;
		  	}
	  	  }

		  if(!flag){
		  	alert('You have not filled in \n' + checklist);
		  	box.focus();
		  }
                 if(flag)
                        document.addStage.submit();

	 }

</script>
<? } ?>
