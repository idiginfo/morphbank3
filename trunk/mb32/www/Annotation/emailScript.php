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
