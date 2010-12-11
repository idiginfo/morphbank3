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

require_once('../configuration/app.server.php');

$startTime = time();
// Connect to MySQL server
$link = adminLogin();

if (!$link){
	printf("Can not connect to MySQL server. Errorcode: %s\n",mysqli_connect_error());
	exit; } // end if

	$treeTableName = 'Tree';


	echo date("H:i:s\n");
	rebuild_tree(0,1);
	echo date("H:i:s\n");

	function rebuild_tree($parent, $left) {
		// the right value of this node is the left value + 1
		$right = $left+1;

		global $treeTableName, $link;

		$sql = "SELECT tsn FROM ".$treeTableName." WHERE parent_tsn='".$parent."';";
		// get all children of this node
		$result = mysqli_query($link,$sql);
		while ($row = mysqli_fetch_array($result)) {
			// recursive execution of this function for each
			// child of this node
			// $right is the current right value, which is
			// incremented by the rebuild_tree function
			$right = rebuild_tree($row['tsn'], $right);
		}

		// we've got the left value, and now that we've processed
		// the children of this node we also know the right value
		if(! mysqli_query($link,'UPDATE '.$treeTableName.' SET lft='.$left.', rgt='.
		$right.' WHERE tsn="'.$parent.'";')){
			echo ("error message: ".mysqli_error($link));
		}
		// return the right value of this node + 1
		return $right+1;
	}

	?>

