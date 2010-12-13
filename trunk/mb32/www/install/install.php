<?php
require_once('install_functions.php');
$msg = installApp();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Morphbank :: Biodiversity Image Repository</title>
<!-- Mimic Internet Explorer 7 -->
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<!-- compliance patch for microsoft browsers -->
<!--[if IE]>
<script src="/ie7/ie7-standard-p.js" type="text/javascript"></script>
<![endif]--> 
<link rel="stylesheet" title="Default" href="/style/morphbank2.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/style/mb2_install.css" type="text/css" media="screen"/>
<link rel="shortcut icon" href="/style/webImages/mLogo16.ico" />
</head>
<body>
	<div id="main">
		<div class="mainHeader">
			<div class="mainHeaderLogo"><img border="0" src="/style/webImages/mbLogoHeader.png" alt="logo" /></div>
			<div class="mainHeaderTitle">Morphbank Installation</div>
		</div>
		<div class="mainRibbon"></div>
		<div class="mainGenericContainer">
			<h1>Installation</h1>
			<div id=footerRibbon></div>
			<br /><br />
			<?php 
			if (isset($_POST['submit']) && empty($msg)) { ?>
			<div>
			Configuration and database install completed. For security reasons, you should 
			delete the install folder.
			</div>
			<?php } else { ?>
			<div class="searchError"><?php echo $msg; ?></div>
			<form class="install" action="" method="post">
			<input type="hidden" name="install" value="true" />
				<fieldset>
					<legend>Database Details</legend>
					<ol>
						<li><label for="db_name">Database name <em>*</em></label> <input type="text" id="db_name" name="db_name" value="<?php echo $_POST['db_name'] ?>" /></li>
						<li><label for="db_user">Database User <em>*</em></label> <input type="text" id="db_user" name="db_user" value="<?php echo $_POST['db_user'] ?>" /></li>
						<li><label for="db_pass">Database Pass </label> <input type="password" id="db_pass" name="db_pass" value="<?php echo $_POST['db_pass'] ?>" /></li>
						<li><label for="db_host">Database Host <em>*</em></label> <input type="text" id="db_host" name="db_host" value="<?php echo empty($_POST['db_host']) ? 'localhost' : $_POST['db_host'] ?>" /></li>
						<li><label for="db_port">Database Port <em>*</em></label> <input type="text" id="db_port" name="db_port" value="<?php echo empty($_POST['db_port']) ? '3306' : $_POST['db_port'] ?>" /></li>
					</ol>
				</fieldset>
				<fieldset><legend>Application Server Details</legend>
					<ol>
						<li><label for="app_server">Application Server <em>*</em></label> <input type="text" id="app_server" name="app_server" value="<?php echo $_POST['app_server'] ?>" /> (e.g. morphbank4.sc.fsu.edu)</li>
						<li><label for="ftp_path">FTP Path <em>*</em></label> <input type="text" id="ftp_path" name="ftp_path" value="<?php echo $_POST['ftp_path'] ?>" /> (e.g. /data/ftpsite/</li>
					</ol>
				</fieldset>
				<fieldset><legend>Image Server Details</legend>
					<ol>
						<li><label for="img_server">Image Server <em>*</em></label> <input type="text" id="img_server" name="img_server" value="<?php echo $_POST['img_server'] ?>" /> (e.g. images.morphbank.net)</li>
						<li><label for="img_root_path">Image Root Path <em>*</em></label> <input type="text" id="img_root_path" name="img_root_path" value="<?php echo $_POST['img_root_path'] ?>" /> (e.g. /data/imagestore/)</li>
						<li><label for="img_magik_path">Image Magik Path <em>*</em></label> <input type="text" id="img_magik_path" name="img_magik_path" value="<?php echo $_POST['img_magik_path'] ?>" /> (e.g. /usr/bin/)</li>
						<li><label for="img_tmp_path">Image Tmp Path <em>*</em></label> <input type="text" id="img_tmp_path" name="img_tmp_path" value="<?php echo $_POST['img_tmp_path'] ?>" /> (e.g. /tmp/images/)</li>
					</ol>
				</fieldset>
				<fieldset><legend>Application Settings</legend>
					<ol>
						<li><label for="app_email">Administration Email <em>*</em></label> <input type="text" id="app_email" name="app_email" value="<?php echo $_POST['app_email'] ?>" /> (e.g. admin@morphbank.net)</li>
						<li><label for="app_name">Application Name <em>*</em></label> <input type="text" id="app_name" name="app_name" value="<?php echo $_POST['app_name'] ?>" /> (e.g. Morphbank)</li>
						<li><label for="app_timezone">Application Timezone <em>*</em></label> <select id="app_timeszone" name="app_timezone"><?php echo getTimezoneOptions($_POST['app_timezone']) ?></select> </li>
					</ol>
				</fieldset>
				<fieldset><legend>Mailing List</legend>
					Enabaled only if using Mailman program
					<ol>
						<li><label for="mail_enabled">Enabled </label>
							<input type="radio" id="mail_enabled" name="mail_enabled" checked="<?php echo $_POST['mail_enabled'] == 1 ? 'checked' : ''; ?>" value="1" /> Yes
							<input type="radio" id="mail_enabled" name="mail_enabled" checked="<?php echo $_POST['mail_enabled'] == 0 ? 'checked' : ''; ?>" value="0" /> No 
						</li>
						<li><label for="mail_url">Mail List URL </label> <input type="text" id="mail_url" name="mail_url" value="<?php echo $_POST['mail_url'] ?>" /> </li>
						<li><label for="mail_password_input">Mail List Password Input </label> <input type="text" id="mail_password_input" name="mail_password_input" value="<?php echo $_POST['mail_password_input'] ?>" /> (e.g. adminpw)</li>
						<li><label for="mail_password">Mail List Password </label> <input type="text" id="mail_password" name="mail_password" /> </li>
					</ol>
				</fieldset>
				<input type="submit" value="Submit" name="submit" />
			</form>
			<?php } ?>
		</div>
	</div>
</body>
</html>
