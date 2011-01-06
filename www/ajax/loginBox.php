<?php
include_once('head.inc.php');
?>

<form name = "loginForm" action="#" method="post" onsubmit="return false;">
	<span id="loginMessage"></span>
    <h2>User Name:</h2><br />
	<input type="text" name="username" size="30" />
    <br /><br />
	<h2>Password:</h2><br />
    <input type="password" name="password" size="30"  onkeyup="checkEnterKey(event, 0)" /><br /><br />
	<a href="javascript: hide_login_ajax();" class="button smallButton right"><div>Cancel</div></a>
    <a href="#" onclick="ajax_login_submit();" class="button smallButton right"><div>Login</div></a>    
</form>
