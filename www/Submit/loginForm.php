<!-- 
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
-->

<form name="login" id="frmLogin" action="login.php" method="post">
<table border="0" cellpadding="5">
	<tr>
		<td colspan="3" align="right"><h1><strong><?php echo $config->appName; ?> Login</strong></h1><br /><br /></td>
	</tr>
	<tr>
		<td align="right"><h2>User Name:</h2> <span class="req">*</span></td>
		<td colspan="2"><input type="text" name="username" size="30" /></td>
	</tr>
	<tr>
		<td align="right"><h2>Password:</h2> <span class="req">*</span></td>
		<td colspan="2"><input type="password" name="password" size="30" /></td>
	</tr>
</table>
<?php echo frmSubmitButton('Submit'); ?>
<br /> <br />
<h3>Request a user account</h3> <a href="/Admin/User/new">Click Here</a> 
<br/><br/>
<h3>Forgot your username/password?</h3> <a href="/Admin/User/userpassword.php">Click here</a>		
</form>
