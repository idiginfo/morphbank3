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

if (isset($_GET['pop'])){
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}


checkIfLogged();


$message = trim($_POST['message']) . "\n\n" . trim($_POST['comments']);

if ($message) {
	$userId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();

	//echo $userId."<br />".$groupId."<br />";
	$usermail = mysqli_fetch_array(runQuery('SELECT email FROM User WHERE id = ' . $userId));
	//$coordinator = mysqli_fetch_array(runQuery('SELECT user FROM UserGroup WHERE groups = ' .$groupId. ' AND UserGroupRole = "coordinator"'));
	//var_dump($usermail);
	//echo "<br />";
	//var_dump($coordinator);
	//$email = mysqli_fetch_array(runQuery('SELECT email FROM User WHERE id = ' .$coordinator['user']));

	//echo $usermail['email'] . '<br /> email: ' .$email['email'];

	//$to = $email['email'];
	$to = $config->email;
	$from = $usermail['email'];

	$headers = 'From: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();


	// The beginnig of HTML
	$title = 'Mail';
	initHtml($title, null, null);

	// Add the standard head section to all the HTML output.
	echoHead(false, $title);

	if ($_GET['pop']) {
		echo '<div class="popContainer" style="width: 500px;">';
	} else {

		echo '<div class="mainGenericContainer" style="width: 500px;">';
	}

	if (@mail($to, "Corrections", $message, $headers)) {
		?>

<h3>The following message has been sent.</h3>
<br />
<br />
<table>
	<tr>
		<td width="5%"></td>
		<td>From: <?php
		echo $from;
		?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td>To: <?php
		echo $to;
		?></td>
	</tr>
	<tr>
		<td><br />
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td>Subject: Morphbank corrections</td>
	</tr>
	<tr>
		<td><br />
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td>Correctios:</td>
	</tr>
	<tr>
		<td><br />
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td><?php
		echo str_replace("\n", '<br />', trim($_POST['message']));
		?></td>
	</tr>
	<tr>
		<td><br />
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td><?php
		echo str_replace("\n", '<br />', trim($_POST['comments']));
		?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<?php
		if (!$_GET['pop'])
		echo '<td align = "right"><a href = "javascript: top.location = \'' . $config->domain . 'MyManager/\'"
          class="button smallButton"><div>Ok</div></a></td>';
		else
		echo '<td align = "right"><a href = "javascript: window.close();" class="button smallButton"><div>Ok</div></a></td>';
		?>
	</tr>
</table>

		<?php
		//  $url = './?message=' .$message. '&pop=yes&sent=sent';
		//  header ("location: ".$url);
	}
} else {

	echo "Empty message";

	//$url = './?message=' .$message. '&sent=no';
	//header ("location: ".$url);
}
echo '</div>';
// Finish with end of HTML
finishHtml();
?>
