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
			Configuration and database install completed.<br />
			Chmod /configuration directory so it is not writable.<br />
			</div>
			<?php } else { ?>
			<div class="searchError"><?php echo $msg; ?></div>
			<form class="install" action="" method="post">
			<input type="hidden" name="install" value="true" />
				<fieldset>
					<legend>Administration User</legend>
					<ol>
						<li><label for="first_name">First Name <em>*</em></label> <input type="text" id="first_name" name="first_name" value="<?php echo $_POST['first_name'] ?>" /></li>
                        <li><label for="last_name">Last Name <em>*</em></label> <input type="text" id="last_name" name="last_name" value="<?php echo $_POST['last_name'] ?>" /></li>
                        <li><label for="uin">User Name <em>*</em></label> <input type="text" id="uin" name="uin" value="<?php echo $_POST['uin'] ?>" /></li>
                        <li><label for="pin">Password <em>*</em></label> <input type="password" id="pin" name="pin" value="<?php echo $_POST['pin'] ?>" /></li>
                        <li><label for="confirm_pin">Confirm Password <em>*</em></label> <input type="password" id="confirm_pin" name="confirm_pin" /></li>
                        <li><label for="email">Email Address <em>*</em></label> <input type="text" id="email" name="email" value="<?php echo $_POST['email'] ?>" /></li>
                        <li><label for="affiliation">Affiliation <em>*</em></label><input type="text" id="affiliation" name="affiliation" value="<?php echo $_POST['affiliation'] ?>" /></li>
                        <li><label for="country">Country <em>*</em></label>
                        <select id="country" name="country" style="width:250px;" title="Select a country">
                        <option value="">-- Select --</option>
                        <option value="AFGHANISTAN">AFGHANISTAN</option>
                        <option value="ALBANIA">ALBANIA</option>
                        <option value="ALGERIA">ALGERIA</option>
                        
                        <option value="AMERICAN SAMOA">AMERICAN SAMOA</option>
                        <option value="ANDORRA">ANDORRA</option>
                        <option value="ANGOLA">ANGOLA</option>
                        <option value="ANGUILLA">ANGUILLA</option>
                        <option value="ANTARCTICA">ANTARCTICA</option>
                        <option value="ANTIGUA AND BARBUDA">ANTIGUA AND BARBUDA</option>
                        <option value="ARGENTINA">ARGENTINA</option>
                        <option value="ARMENIA">ARMENIA</option>
                        <option value="ARUBA">ARUBA</option>
                        
                        <option value="AUSTRALIA">AUSTRALIA</option>
                        <option value="AUSTRIA">AUSTRIA</option>
                        <option value="AZERBAIJAN">AZERBAIJAN</option>
                        <option value="BAHAMAS">BAHAMAS</option>
                        <option value="BAHRAIN">BAHRAIN</option>
                        <option value="BANGLADESH">BANGLADESH</option>
                        <option value="BARBADOS">BARBADOS</option>
                        <option value="BELARUS">BELARUS</option>
                        <option value="BELGIUM">BELGIUM</option>
                        
                        <option value="BELIZE">BELIZE</option>
                        <option value="BENIN">BENIN</option>
                        <option value="BERMUDA">BERMUDA</option>
                        <option value="BHUTAN">BHUTAN</option>
                        <option value="BOLIVIA">BOLIVIA</option>
                        <option value="BOSNIA AND HERZEGOVINA">BOSNIA AND HERZEGOVINA</option>
                        <option value="BOTSWANA">BOTSWANA</option>
                        <option value="BOUVET ISLAND">BOUVET ISLAND</option>
                        <option value="BRAZIL">BRAZIL</option>
                        
                        <option value="BRITISH INDIAN OCEAN TERRITORY">BRITISH INDIAN OCEAN TERRITORY</option>
                        <option value="BRUNEI DARUSSALAM">BRUNEI DARUSSALAM</option>
                        <option value="BULGARIA">BULGARIA</option>
                        <option value="BURKINA FASO">BURKINA FASO</option>
                        <option value="BURUNDI">BURUNDI</option>
                        <option value="CAMBODIA">CAMBODIA</option>
                        <option value="CAMEROON">CAMEROON</option>
                        <option value="CANADA">CANADA</option>
                        <option value="CAPE VERDE">CAPE VERDE</option>
                        
                        <option value="CAYMAN ISLANDS">CAYMAN ISLANDS</option>
                        <option value="CENTRAL AFRICAN REPUBLIC">CENTRAL AFRICAN REPUBLIC</option>
                        <option value="CHAD">CHAD</option>
                        <option value="CHILE">CHILE</option>
                        <option value="CHINA">CHINA</option>
                        <option value="CHRISTMAS ISLAND">CHRISTMAS ISLAND</option>
                        <option value="COCOS (KEELING) ISLANDS">COCOS (KEELING) ISLANDS</option>
                        <option value="COLOMBIA">COLOMBIA</option>
                        <option value="COMOROS">COMOROS</option>
                        
                        <option value="CONGO">CONGO</option>
                        <option value="CONGO, DEMOCRATIC REPUBLIC OF ZAIRE">CONGO, DEMOCRATIC REPUBLIC OF ZAIRE</option>
                        <option value="COOK ISLANDS">COOK ISLANDS</option>
                        <option value="COSTA RICA">COSTA RICA</option>
                        <option value="COTE D'IVOIRE">COTE D'IVOIRE</option>
                        <option value="CROATIA">CROATIA</option>
                        <option value="CUBA">CUBA</option>
                        <option value="CYPRUS">CYPRUS</option>
                        <option value="CZECH REPUBLIC">CZECH REPUBLIC</option>
                        
                        <option value="DENMARK">DENMARK</option>
                        <option value="DJIBOUTI">DJIBOUTI</option>
                        <option value="DOMINICA">DOMINICA</option>
                        <option value="DOMINICAN REPUBLIC">DOMINICAN REPUBLIC</option>
                        <option value="EAST TIMOR">EAST TIMOR</option>
                        <option value="ECUADOR">ECUADOR</option>
                        <option value="EGYPT">EGYPT</option>
                        <option value="EL SALVADOR">EL SALVADOR</option>
                        <option value="EQUATORIAL GUINEA">EQUATORIAL GUINEA</option>
                        
                        <option value="ERITREA">ERITREA</option>
                        <option value="ESTONIA">ESTONIA</option>
                        <option value="ETHIOPIA">ETHIOPIA</option>
                        <option value="FALKLAND ISLANDS (MALVINAS)">FALKLAND ISLANDS (MALVINAS)</option>
                        <option value="FAROE ISLANDS">FAROE ISLANDS</option>
                        <option value="FIJI">FIJI</option>
                        <option value="FINLAND">FINLAND</option>
                        <option value="FRANCE">FRANCE</option>
                        <option value="FRENCH GUIANA">FRENCH GUIANA</option>
                        
                        <option value="FRENCH POLYNESIA">FRENCH POLYNESIA</option>
                        <option value="FRENCH SOUTHERN TERRITORIES">FRENCH SOUTHERN TERRITORIES</option>
                        <option value="GABON">GABON</option>
                        <option value="GAMBIA">GAMBIA</option>
                        <option value="GEORGIA">GEORGIA</option>
                        <option value="GERMANY">GERMANY</option>
                        <option value="GHANA">GHANA</option>
                        <option value="GIBRALTAR">GIBRALTAR</option>
                        <option value="GREECE">GREECE</option>
                        
                        <option value="GREENLAND">GREENLAND</option>
                        <option value="GRENADA">GRENADA</option>
                        <option value="GUADELOUPE">GUADELOUPE</option>
                        <option value="GUAM">GUAM</option>
                        <option value="GUATEMALA">GUATEMALA</option>
                        <option value="GUINEA">GUINEA</option>
                        <option value="GUINEA-BISSAU">GUINEA-BISSAU</option>
                        <option value="GUYANA">GUYANA</option>
                        <option value="HAITI">HAITI</option>
                        
                        <option value="HEARD ISLAND AND MCDONALD ISLANDS">HEARD ISLAND AND MCDONALD ISLANDS</option>
                        <option value="HOLY SEE (VATICAN CITY STATE)">HOLY SEE (VATICAN CITY STATE)</option>
                        <option value="HONDURAS">HONDURAS</option>
                        <option value="HONG KONG">HONG KONG</option>
                        <option value="HUNGARY">HUNGARY</option>
                        <option value="ICELAND">ICELAND</option>
                        <option value="INDIA">INDIA</option>
                        <option value="INDONESIA">INDONESIA</option>
                        <option value="IRAN, ISLAMIC REPUBLIC OF">IRAN, ISLAMIC REPUBLIC OF</option>
                        
                        <option value="IRAQ">IRAQ</option>
                        <option value="IRELAND">IRELAND</option>
                        <option value="ISRAEL">ISRAEL</option>
                        <option value="ITALY">ITALY</option>
                        <option value="JAMAICA">JAMAICA</option>
                        <option value="JAPAN">JAPAN</option>
                        <option value="JORDAN">JORDAN</option>
                        <option value="KAZAKSTAN">KAZAKSTAN</option>
                        <option value="KENYA">KENYA</option>
                        
                        <option value="KIRIBATI">KIRIBATI</option>
                        <option value="KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF">KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
                        <option value="KOREA, REPUBLIC OF">KOREA, REPUBLIC OF</option>
                        <option value="KUWAIT">KUWAIT</option>
                        <option value="KYRGYZSTAN">KYRGYZSTAN</option>
                        <option value="LAO PEOPLE'S DEMOCRATIC REPUBLIC">LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
                        <option value="LATVIA">LATVIA</option>
                        <option value="LEBANON">LEBANON</option>
                        <option value="LESOTHO">LESOTHO</option>
                        
                        <option value="LIBERIA">LIBERIA</option>
                        <option value="LIBYAN ARAB JAMAHIRIYA">LIBYAN ARAB JAMAHIRIYA</option>
                        <option value="LIECHTENSTEIN">LIECHTENSTEIN</option>
                        <option value="LITHUANIA">LITHUANIA</option>
                        <option value="LUXEMBOURG">LUXEMBOURG</option>
                        <option value="MACAU">MACAU</option>
                        <option value="MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF">MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
                        <option value="MADAGASCAR">MADAGASCAR</option>
                        <option value="MALAWI">MALAWI</option>
                        
                        <option value="MALAYSIA">MALAYSIA</option>
                        <option value="MALDIVES">MALDIVES</option>
                        <option value="MALI">MALI</option>
                        <option value="MALTA">MALTA</option>
                        <option value="MARSHALL ISLANDS">MARSHALL ISLANDS</option>
                        <option value="MARTINIQUE">MARTINIQUE</option>
                        <option value="MAURITANIA">MAURITANIA</option>
                        <option value="MAURITIUS">MAURITIUS</option>
                        <option value="MAYOTTE">MAYOTTE</option>
                        
                        <option value="MEXICO">MEXICO</option>
                        <option value="MICRONESIA, FEDERATED STATES OF">MICRONESIA, FEDERATED STATES OF</option>
                        <option value="MOLDOVA, REPUBLIC OF">MOLDOVA, REPUBLIC OF</option>
                        <option value="MONACO">MONACO</option>
                        <option value="MONGOLIA">MONGOLIA</option>
                        <option value="MONTSERRAT">MONTSERRAT</option>
                        <option value="MOROCCO">MOROCCO</option>
                        <option value="MOZAMBIQUE">MOZAMBIQUE</option>
                        <option value="MYANMAR">MYANMAR</option>
                        
                        <option value="NAMIBIA">NAMIBIA</option>
                        <option value="NAURU">NAURU</option>
                        <option value="NEPAL">NEPAL</option>
                        <option value="NETHERLANDS">NETHERLANDS</option>
                        <option value="NETHERLANDS ANTILLES">NETHERLANDS ANTILLES</option>
                        <option value="NEW CALEDONIA">NEW CALEDONIA</option>
                        <option value="NEW ZEALAND">NEW ZEALAND</option>
                        <option value="NICARAGUA">NICARAGUA</option>
                        <option value="NIGER">NIGER</option>
                        
                        <option value="NIGERIA">NIGERIA</option>
                        <option value="NIUE">NIUE</option>
                        <option value="NORFOLK ISLAND">NORFOLK ISLAND</option>
                        <option value="NORTHERN MARIANA ISLANDS">NORTHERN MARIANA ISLANDS</option>
                        <option value="NORWAY">NORWAY</option>
                        <option value="Not applicable">Not applicable</option>
                        <option value="OMAN">OMAN</option>
                        <option value="PAKISTAN">PAKISTAN</option>
                        <option value="PALAU">PALAU</option>
                        
                        <option value="PALESTINIAN TERRITORY, OCCUPIED">PALESTINIAN TERRITORY, OCCUPIED</option>
                        <option value="PANAMA">PANAMA</option>
                        <option value="PAPUA NEW GUINEA">PAPUA NEW GUINEA</option>
                        <option value="PARAGUAY">PARAGUAY</option>
                        <option value="PERU">PERU</option>
                        <option value="PHILIPPINES">PHILIPPINES</option>
                        <option value="PITCAIRN">PITCAIRN</option>
                        <option value="POLAND">POLAND</option>
                        <option value="PORTUGAL">PORTUGAL</option>
                        
                        <option value="PUERTO RICO">PUERTO RICO</option>
                        <option value="QATAR">QATAR</option>
                        <option value="REUNION">REUNION</option>
                        <option value="ROMANIA">ROMANIA</option>
                        <option value="RUSSIAN FEDERATION">RUSSIAN FEDERATION</option>
                        <option value="RWANDA">RWANDA</option>
                        <option value="SAINT HELENA">SAINT HELENA</option>
                        <option value="SAINT KITTS AND NEVIS">SAINT KITTS AND NEVIS</option>
                        <option value="SAINT LUCIA">SAINT LUCIA</option>
                        
                        <option value="SAINT PIERRE AND MIQUELON">SAINT PIERRE AND MIQUELON</option>
                        <option value="SAINT VINCENT AND THE GRENADINES">SAINT VINCENT AND THE GRENADINES</option>
                        <option value="SAMOA">SAMOA</option>
                        <option value="SAN MARINO">SAN MARINO</option>
                        <option value="SAO TOME AND PRINCIPE">SAO TOME AND PRINCIPE</option>
                        <option value="SAUDI ARABIA">SAUDI ARABIA</option>
                        <option value="SENEGAL">SENEGAL</option>
                        <option value="SEYCHELLES">SEYCHELLES</option>
                        <option value="SIERRA LEONE">SIERRA LEONE</option>
                        
                        <option value="SINGAPORE">SINGAPORE</option>
                        <option value="SLOVAKIA">SLOVAKIA</option>
                        <option value="SLOVENIA">SLOVENIA</option>
                        <option value="SOLOMON ISLANDS">SOLOMON ISLANDS</option>
                        <option value="SOMALIA">SOMALIA</option>
                        <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                        <option value="SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS">SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
                        <option value="SPAIN">SPAIN</option>
                        <option value="SRI LANKA">SRI LANKA</option>
                        
                        <option value="SUDAN">SUDAN</option>
                        <option value="SURINAME">SURINAME</option>
                        <option value="SVALBARD AND JAN MAYEN">SVALBARD AND JAN MAYEN</option>
                        <option value="SWAZILAND">SWAZILAND</option>
                        <option value="SWEDEN">SWEDEN</option>
                        <option value="SWITZERLAND">SWITZERLAND</option>
                        <option value="SYRIAN ARAB REPUBLIC">SYRIAN ARAB REPUBLIC</option>
                        <option value="TAIWAN, PROVINCE OF CHINA">TAIWAN, PROVINCE OF CHINA</option>
                        <option value="TAJIKISTAN">TAJIKISTAN</option>
                        
                        <option value="TANZANIA, UNITED REPUBLIC OF">TANZANIA, UNITED REPUBLIC OF</option>
                        <option value="THAILAND">THAILAND</option>
                        <option value="TOGO">TOGO</option>
                        <option value="TOKELAU">TOKELAU</option>
                        <option value="TONGA">TONGA</option>
                        <option value="TRINIDAD AND TOBAGO">TRINIDAD AND TOBAGO</option>
                        <option value="TUNISIA">TUNISIA</option>
                        <option value="TURKEY">TURKEY</option>
                        <option value="TURKMENISTAN">TURKMENISTAN</option>
                        
                        <option value="TURKS AND CAICOS ISLANDS">TURKS AND CAICOS ISLANDS</option>
                        <option value="TUVALU">TUVALU</option>
                        <option value="UGANDA">UGANDA</option>
                        <option value="UKRAINE">UKRAINE</option>
                        <option value="UNITED ARAB EMIRATES">UNITED ARAB EMIRATES</option>
                        <option value="UNITED KINGDOM">UNITED KINGDOM</option>
                        <option value="UNITED STATES">UNITED STATES</option>
                        <option value="UNITED STATES MINOR OUTLYING ISLANDS">UNITED STATES MINOR OUTLYING ISLANDS</option>
                        <option value="UNKNOWN">UNKNOWN</option>
                        
                        <option value="UNSPECIFIED">UNSPECIFIED</option>
                        <option value="URUGUAY">URUGUAY</option>
                        <option value="UZBEKISTAN">UZBEKISTAN</option>
                        <option value="VANUATU">VANUATU</option>
                        <option value="VENEZUELA">VENEZUELA</option>
                        <option value="VIET NAM">VIET NAM</option>
                        <option value="VIRGIN ISLANDS, BRITISH">VIRGIN ISLANDS, BRITISH</option>
                        <option value="VIRGIN ISLANDS, U.S.">VIRGIN ISLANDS, U.S.</option>
                        <option value="WALLIS AND FUTUNA">WALLIS AND FUTUNA</option>
                        
                        <option value="WESTERN SAHARA">WESTERN SAHARA</option>
                        <option value="YEMEN">YEMEN</option>
                        <option value="YUGOSLAVIA">YUGOSLAVIA</option>
                        <option value="ZAMBIA">ZAMBIA</option>
                        <option value="ZIMBABWE">ZIMBABWE</option>
                        </select></li>
					</ol>
				</fieldset>
				<fieldset>
					<legend>Database Details</legend>
					<ol>
						<li><label for="db_name">Database name <em>*</em></label> <input type="text" id="db_name" name="db_name" value="<?php echo $_POST['db_name'] ?>" /></li>
						<li><label for="db_user">Database User <em>*</em></label> <input type="text" id="db_user" name="db_user" value="<?php echo $_POST['db_user'] ?>" /></li>
						<li><label for="db_pass">Database Pass </label> <input type="password" id="db_pass" name="db_pass" value="<?php echo $_POST['db_pass'] ?>" /></li>
						<li><label for="db_host">Database Host <em>*</em></label> <input type="text" id="db_host" name="db_host" value="<?php echo empty($_POST['db_host']) ? 'localhost' : $_POST['db_host'] ?>" /></li>
						<li><label for="db_port">Database Port <em>*</em></label> <input type="text" id="db_port" name="db_port" value="<?php echo empty($_POST['db_port']) ? '3306' : $_POST['db_port'] ?>" /></li>
						<li><label for="db_port">Object Min Id <em>*</em></label> <input type="text" id="db_object_min_id" name="db_object_min_id" value="<?php echo $_POST['db_object_min_id'] ?>" /> (Provided by Morphbank)</li>
						<li><label for="db_port">Tsn Min Id <em>*</em></label> <input type="text" id="db_tsn_min_id" name="db_tsn_min_id" value="<?php echo $_POST['db_tsn_min_id'] ?>" /> (Provided by Morphbank)</li>
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
						<li><label for="app_welcome">Welcome Message <em>*</em></label> <input type="text" id="app_welcom" name="app_welcome" value="<?php echo $_POST['app_welcome'] ?>" /> (e.g. Welcome to Morphbank)</li>
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
