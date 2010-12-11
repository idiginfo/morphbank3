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
include_once('head.inc.php');
require_once ('mirrorClass.inc.php');

include_once('tsnFunctions.php');

checkIfLogged();
groups();
$link = Adminlogin();

$time = time();

//if(checkAuthorization('Specimen', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Add', $link)){

	$mirrorTsns = 		trim($_POST['mirrorTsns']);
	$mirrorTsn = 		trim($_POST['DeterminationId']);
	//$server = 		trim($_POST['Server']);

	$updatedDate = mysqli_fetch_array(runQuery("SELECT updatedDate FROM ServerInfo WHERE serverId = " .$objInfo->getPreferredServerId(). ";"));

	//Gets mirror info from the database based on the preferredServer of the User
	$mirrorInfo = mysqli_fetch_array(runQuery("SELECT url, basePath, port, login, passwd FROM ServerInfo WHERE mirrorGroup = " .$objInfo->getUserGroupId(). ";"));

	//Creates a mirror object with the mirror information obtained from Database.
	$mirror_object = new mirror($mirrorInfo['url'], $mirrorInfo['basePath'], $mirrorInfo['port'], $mirrorInfo['login'], $mirrorInfo['passwd']);
	
	if($mirrorTsn == 0){

		if($updatedDate['updatedDate'] == '0000-00-00'){

			$imgResult = runQuery("SELECT accessNum FROM Image, BaseObject WHERE BaseObject.id = Image.id AND objectTypeId = 'Image' AND BaseObject.dateToPublish < NOW() ORDER BY accessNum ASC;");

		}else{
			$imgResult = runQuery("SELECT accessNum FROM Image, BaseObject WHERE BaseObject.id = Image.id AND objectTypeId = \'Image\' AND BaseObject.dateToPublish < NOW() AND BaseObject.dateToPublish > " .$updatedDate['updatedDate']. " ORDER BY accessNum ASC;");
		}

	}else{

		$tsn = mysqli_fetch_array(runQuery("SELECT lft, rgt FROM Tree WHERE tsn = " .$mirrorTsn. ";" ));

		$childRes = runQuery("SELECT tsn FROM Tree WHERE lft > " .$tsn['lft']. " AND rgt < " .$tsn['rgt']. " ORDER BY unit_name1;");
		//$children = mysqli_fetch_array(runQuery("SELECT tsn FROM Tree WHERE lft > " .$tsn['lft']. " AND rgt < " .$tsn['rgt']. " ORDER BY unit_name1;"));		

		while($children = mysqli_fetch_array($childRes,MYSQL_ASSOC)){
			
			//$specSql = "SELECT distinct(id) FROM Specimen WHERE tsnId = " .$children['tsn']. ";";

			$specIdRes = runQuery("SELECT distinct(id) FROM Specimen WHERE tsnId = " .$children['tsn']. ";");

			while($specId = mysqli_fetch_array($specIdRes, MYSQL_ASSOC)){

				$sql = "SELECT Image.id FROM Image, BaseObject WHERE BaseObject.id = Image.id AND specimenId  = " .$specId['id']. " AND BaseObject.dateToPublish < NOW() AND BaseObject.dateToPublish > " .$updatedDate['updatedDate']. " ORDER BY accessNum ASC;";

				//$imgRes = runQuery($sql);
				$imgRes = mysqli_query($link, $sql) or die (mysql_error());

				while($accessNum = mysqli_fetch_array($imgRes)){
					
					//echo $accessNum['id'] .'<br />';
					$mirror_object->addImage($accessNum['id']);
				}// end of accessNum
			}// end of specimen Ids
		}//end of while children
	} //end of else

	$bar = new mirrorProgressBar();
	$bar->addListener(array(&$bar, 'notify'), 'onTick');

	// The beginnig of HTML
        $title = 'Mirror';
        initHtml( $title, NULL, NULL);

        // Add the standard head section to all the HTML output.
        echoHead( false, $title);

        echo '<div class="main">
                <div class="minHeight">&nbsp;</div>
                        <div class="mainGenericContainer" style="width: 600px;">';

			echo "<style type=\"text/css\">" .$bar->getStyle(). "</style>" .$bar->getImageUpdateScript();

//echo 'Mirror Info:  ' .$mirrorInfo['url']. ',     ' .$mirrorInfo['basePath']. ',    ' .$mirrorInfo['port']. ',     ' .$mirrorInfo['login']. ',     ' .$mirrorInfo['passwd'];

			$bar->setMaximum($mirror_object->fileListSize());
			$bar->imageDisplay();
			$overwrite = TRUE;
			$mirror_object->createMirror($overwrite, $bar);

		echo "This process took: ".time()-$time." seconds";
                echo '</div>';

// update ServerInfo table by appending tsn to the tsns field.

	//$tsnUpdate = 'INSERT INTO ServerInfo tsns VALUES ("' .$mirrorTsn. '| ' .date('Y-m-d'). '");';
	$tsnUpdate = 'UPDATE ServerInfo SET tsns =  "' . $mirrorTsn. '| ' .date('Y-m-d'). '" WHERE serverId = ' .$objInfo->getPreferredServerId(). ';';

	$res = mysqli_query($link, $tsnUpdate) or die(mysqli_error($link));

        // Finish with end of HTML
        finishHtml();
?>
