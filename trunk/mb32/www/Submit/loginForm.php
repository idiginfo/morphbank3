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
