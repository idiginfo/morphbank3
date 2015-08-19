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

//this file creates the form for user provided values for
//given Otu  
//
//@author Karolina Maneva-Jakimoska
//@date created Aug 16th 2007
//


include_once('head.inc.php');


include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');


global $config;
checkIfLogged();

//get userId and groupId from ObjectInfo
$groupId = $objInfo->getUserGroupId();
$userId= $objInfo->getUserId();

$link = Adminlogin();

$title = 'Add OTU';
initHtml ($title,NULL, NULL);
echoHead (false, $title);
echoJavaScript();

echo '<div class="mainGenericContainer" style="width:700px">
         <h1>Add OTU</h1>
           <br/><br/>';


     $id = createCollection(null, $userId, $groupId, $title="New Collection Name",TRUE);
     $collection = new Collection($link,$id);
     
	 $tsnFlag = FALSE;
	 
	 // loop throught the post variables to see if it received values that were just MB id's or tsns in the form of Taxon=number or Otu=number
	 // and sets a flag to see which function needs to be called to extract the post variables to necessary array form.
	 foreach ($_POST as $k => $v) {
		$array = explode("=", $v);
		
		if (count($array) >= 2) {
			$tsnFlag = TRUE;
			break;	
		}
	}
	 
	 
     $arrayList = ($tsnFlag) ? getTsnArrayFromPost() : getIdArrayFromPost();
	 //var_dump($_POST);
	 //exit(0);
     $collection -> madeOtuFromCollection($arrayList);     
?>
              <form name="addOtu" method="post" action="commitOtu.php">
	         <table border="0">
                   <tr>
                     <td><b>Title: <span class="red">*</span> </b></td><td><input type="text" name="title" size="50" maxlength="50" title="Enter the full title for this OTU"/></td>
                   </tr>
                   <tr>
                     <td><b>Description: <span class="red">*</span> </b></td><td><textarea name="description" rows="4" cols="45" title="Enter complete description for the OTU" ></textarea></td>
                   </tr>
                   <tr>
                     <td><b>Short Title/Label: <span class="red">*</span> </b></td>
                     <td><input type="text" name="label" size="6" maxlength="6" title="Enter short title for the OTU as you want to be displayed in the matrix"/></td>
                   </tr>
                   <tr>
		    <td><b>Date To Publish: <span class="red">*</span> </b></td><td><input type="text" name="dateToPublish" value="<?echo $collection->getDateToPublish();?>" title="Enter date in yyyy-mm-dd format of when the OTU should become public">
                       <input type="hidden" name="id" value="<?echo $id;?>" />
                       <input type="hidden" name="userId" value="<?echo $userId;?>" />
                       <input type="hidden" name="groupId" value="<?echo $groupId;?>" />
                     </td>
                   </tr>
                </table>
                <br/><br/>
                <table >
                   <tr><td valign="top"><b>Included Taxa, OTUs and Specimens: </b></td>
                       <td>
                       <? ListOtus($link, $id); ?>
                       </td>
                   </tr>
                </table>
                <table align="right">
                  <tr>
                    <td><a href="javascript:checkAll();" class="button smallButton "title="Click to add OTU information to the database"><div>Submit</div></a>
                        <a href="<? echo $config->domain;?>Phylogenetics/Otu/cancelOtu.php?id=<? echo $id;?>" class="button smallButton" title="Click to cancel the action"><div>Cancel</div></a>
                    </td>
                  </tr>
                </table>
              </form>
<?
echo '</div>';


    //function to list all the otus
    function ListOtus($link, $otuId){

      $otu = new Otu($link, $otuId);
      $colObjects = $otu ->getObjects();

      echo '<table valign="center">';
      for($i=0;$i<count($colObjects);$i++){
        echo '<tr><td>[';
           if($colObjects[$i]->getObjectTypeId()!="TaxonConcept")
             echo $colObjects[$i]->getObjectId().'-'.$colObjects[$i]->getObjectTypeId()."]  ";
           else{
             $query = "select tsn from TaxonConcept where id=".$colObjects[$i]->getObjectId();
             $row = mysqli_fetch_array(mysqli_query($link,$query)); 
             echo $row['tsn']."-Taxon]  ";
           }
           echo $colObjects[$i]->getObjectTitle().'</td></tr>';
      }
      echo '</table>';
    }

    function echoJavaScript(){
      echo '<script type="text/javascript">

                function checkAll() {
					var submitFlag = false;
					
					if (document.addOtu.title.value == "")
						submitFlag = " Title";
					if (document.addOtu.description.value == "")
						submitFlag += "\n Description";
					if (document.addOtu.label.value == "")
						submitFlag += "\n Label";
						
					if (!submitFlag)
                   		document.addOtu.submit();
					else
						alert ("The following fields are required \n"+submitFlag);
                }
      </script>';
    }

finishHtml();

?>
