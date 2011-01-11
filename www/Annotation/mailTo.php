<?php
/************************************************************************************************
 *  Author: David A. Gaitros                                                                     *
 *  Date:   3/2/2006                                                                             *
 *  Description:  PhP script to send a link for a MorphBank object to a user.                    *
 *                                                                                               *
 *  Modified: 3/3/2006 to include .php header files and static path names.                      *
 ************************************************************************************************/

include_once('../config.inc.php');
include_once($includeDirectory.'head.inc.php');

include_once($securityDirectory . 'admin.inc.php');
global $buttonsDirectory;
global $domainName;
global $objInfo;
$Pagetitle = "Send Email";
initHtml();
echoHead(false, $Pagetitle);

//   checkIfLogged();

//  if(isset($_SESSION['userId']))
//     $userid = $_SESSION['userId'];
//  else exit;
$userid = $objInfo->getUserId();
$groupid = $objInfo->getUserGroupId();
if (isset($_GET['objectId']))
//
$objectId = $_GET['objectId'];
else
exit;

$subject = "MorphBank - Image Object ";
$link = Adminlogin();
$results = mysqli_query($link, "select * from User where id=" . $userid);
$numrows = mysqli_num_rows($results);
if ($numrows < 1)
exit;
$row = mysqli_fetch_array($results);
$from = $row['email'];
$name = $row['name'];
$url = $domainName . "Show/index.php?id=" . $objectId;
?>
<form action="emailScript.php" method=post><?php
echo '<div class="mainGenericContainer" id="email" style="width:650px;">';
?>
<TABLE BORDER=0>
	<TR>
		<TD><B>To: </B></TD>
		<TD align="left"><input type="text" name="to" size="64 "
			maxlength="64"
			title="Enter the eMail Address of the person you with to send this link to. ">
	
	</tr>
	<TR>
		<TD><B>Subject:</B></TD>
		<TD align="left"><?php
		echo $subject
		?></td>
	</TR>
	<TR>
		<TD><B>From:</B></TD>
		<TD align="left"><?php
		echo $name . " " . $email
		?></td>
	</TR>
	<TR>
		<TD><B>URL: </B></TD>
		<TD align="left"><?php
		echo $url
		?></td>
	</TR>
	<TR>
		<TD><B>Body:</B></TD>
		<TD><textarea name="body" rows="10" cols="50" wrap="physical"></textarea></TD>
	</TR>
	<TR>
		<TD><a href="javascript:document.forms[0].submit();"
			class="button smallButton" title="Hit to send email">
		<div>Submit</div>
		</a></td>
		<TD><a href="javascript:history.go(-1)" class="button smallButton">
		<div>Cancel</div>
		</a></TD>
	</TR>
	<TR>
		<TD><input type="hidden" name="subject"
			value="<?php
  echo $subject;
?>"></TD>
	</TR>
	<TR>
		<TD><input type="hidden" name="url" value="<?php
  echo $url;
?>"></TD>
	</TR>
	<TR>
		<TD><input type="hidden" name="from" value="<?php
  echo $from;
?>"></TD>
	</TR>

</TABLE>
</FORM>
<?php
echo "</div>";
?>