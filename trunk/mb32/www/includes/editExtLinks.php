<?php

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
