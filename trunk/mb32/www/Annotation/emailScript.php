<?php

include_once('head.inc.php');

initHtml();
echo '<div class="mainGenericContainer">';

$to = $_POST['to'];
$body = $_POST['body'];
$from = "From: " . $_POST['from'];
$url = $_POST['url'];
$subject = $_POST['subject'];

$status = mail($to, $subject, $url . " " . $body, $from);

if ($status) {
	echo "Email sent successfully<BR>";
	echo "TO: " . $to . "<BR>";
	echo "FROM: " . $from . "<BR>";
	echo "URL: " . $url . "<BR>";
	echo "SUBJECT: " . $subject . "<BR>";
} else
echo "Email failed to send<BR>";
?>
<TABLE>
	<TD>
	
	
	<TR>
		<A HREF="javascript:history.go(-2);" class="button smallButton">
		<div>Return</div>
		</a>
		</TD>
	</TR>
	</body>
	</html>
	</div>
