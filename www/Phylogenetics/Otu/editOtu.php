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
<?php

//this file creates the form for editing OTU information
//
//@author Karolina Maneva-Jakimoska
//@date created Sep 26th 2007
//

if (isset($_GET['pop'])) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

global $config;
checkIfLogged();

//get userId and groupId from ObjectInfo
$groupId = $objInfo->getUserGroupId();
$userId= $objInfo->getUserId();

if(!isset($_GET['prevURL']))
     $url = $_SERVER['HTTP_REFERER'];
else
     $url = $_GET['prevURL'];

$link = Adminlogin();

$title = 'Edit OTU';
initHtml ($title,NULL, NULL);
echoHead (false, $title);
echoJavaScript();

echo '<div class="mainGenericContainer" style="width:700px">
         <h1>Edit OTU</h1>
           <br/><br/>';
       if(isset($_GET['id']) && $_GET['id']!=null){
	 if(!ObjectForUser($link,$_GET['id'],$userId)){
            echo '<span style="color:#17256B" ><h3>This object is contributed/submitted by a different user. You do not have privileges to edit it. However you can send an e-mail to the mbadmin team with suggested changes.</h3><br/><br/>';
	    echo '<table width="700"><tr>
                            <td align = "right"><a href = "'.$returnUrl.'" class="button smallButton"><div>Return</div></a>
                            </td></tr>
                         </table>
                   </div>';
	    finishHtml();
	    exit();
	 }
	 else
	   $id = $_GET['id'];
       }
     $otu = new Otu($link,$id);
     
     if(isset($_GET['fe']) && $_GET['fe']==1)
         echo '<span style="color:red"><b>Some error occured while editing this record some information might not have been changed, please try again</b></span><br/><br/>';
     if(isset($_GET['fe']) && $_GET['fe']==0)
     echo '<span style="color:#17256B"><b>OTU information updated successfuly.</b></span><br/><br/>';

?>
              <form name="editOtu" method="post" action="commitEditOtu.php">
	         <table border="0">
                   <tr>
                     <td><b>Title: </b></td><td><input type="text" name="title" size="50" maxlength="50" value = "<?echo $otu->getName();?>" title="Edit title for this OTU"/></td>
                   </tr>
                   <tr>
                     <td><b>Description: </b></td><td><textarea name="description" rows="4" cols="45"  title="Edit description for the OTU" ><?echo $otu->getDescription();?></textarea></td>
                   </tr>
                   <tr>
                     <td><b>Short Title/Label: </b></td>
                     <td><input type="text" name="label" size="6" maxlength="6" value = "<?echo $otu->getLabel();?>" title="Edit short title for the OTU as you want to be displayed in the matrix"/></td>
                   </tr>
                   <tr>
		    <td><b>Date To Publish: </b></td><td><input type="text" name="dateToPublish" value="<?echo $otu->getDateToPublish();?>" title="Edit date in yyyy-mm-dd format of when the OTU should become public">
                       <input type="hidden" name="id" value="<?echo $id;?>" />
                       <input type="hidden" name="userId" value="<?echo $userId;?>" />
                       <input type="hidden" name="groupId" value="<?echo $groupId;?>" />
                       <input type="hidden" name="url" value="<?echo $url;?>" />
                     </td>
                   </tr>
                </table>
                <br/><br/>
                <table >
                   <tr><td valign="top"><b>Included Taxa, OTU's and Specimens: </b></td>
                       <td>
                       <? ListOtus($link, $id); ?>
                       </td>
                   </tr>
                </table>
                <table align="right">
                  <tr>
                    <td><a href="javascript:checkAll();" class="button smallButton "title="Click to add OTU information to the database"><div>Submit</div></a>
                      <a href="<? echo $url;?>" class="button smallButton" title="Click to return to the previous page"><div>Return</div></a>
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

      echo '<table valign="center" width="auto">';
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
    function ObjectForUser($link,$id,$userId){
         $query ="select userId, submittedBy from BaseObject where id=".$id;
         //echo $query;
         $result = mysqli_query($link,$query);
         if(mysqli_num_rows($result)<1)
            return false;
         else{
            $row = mysqli_fetch_array($result);
            if($row['userId']==$userId || $row['submittedBy']==$userId)
               return true;
            else
               return false;
         }
    }
    
    function echoJavaScript(){
      echo '<script type="text/javascript">

                function checkAll(){
                   document.editOtu.submit();
                }
      </script>';
    }

finishHtml();

?>
