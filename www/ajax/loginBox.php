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
?>

<form name = "loginForm" action="#" method="post" onsubmit="return false;">
	<span id="loginMessage"></span>
    <!--
	<table border="0" cellpadding="5">
		<tr>
			<td colspan="3" align="right"><h1><strong>Morphbank Login</strong></h1><br /><br /></td>
		</tr>
		<tr>
			<td align="right"><h2>User Name:</h2></td>
			<td colspan="2"><input type="text" name="username" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><h2>Password:</h2></td>
			<td colspan="2"><input type="password" name="password" size="30" onkeypress="checkEnterKey(event, 0)" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="#" onclick="ajax_login_submit();" class="button smallButton"><div>Login</div></a></td>
			<td><a href="javascript: hide_login_ajax();" class="button smallButton"><div>Cancel</div></a></td>
		</tr>
	</table>

	<br /> <br />

	<h3>Request a morphbank </h3><a href = "<? echo $config->domain; ?>Admin/userapplication.php">user account</a> 
	<br/><br/><h3>Forgot your username/password?</h3>
	<a href = " <? echo $config->domain; ?>Admin/User/userpassword.php"> Click here</a>		
    -->
       
    <h2>User Name:</h2><br />
	<input type="text" name="username" size="30" />
    <br /><br />
	<h2>Password:</h2><br />
    <input type="password" name="password" size="30"  onkeyup="checkEnterKey(event, 0)" /><br /><br />
	<a href="javascript: hide_login_ajax();" class="button smallButton right"><div>Cancel</div></a>
    <a href="#" onclick="ajax_login_submit();" class="button smallButton right"><div>Login</div></a>    
</form>
