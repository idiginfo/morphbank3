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

	
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Login - Request a New Morphbank Account</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<a name="newAccount"></a> 
<p><img src="ManualImages/log_in.png" />
</p>
<ul>
<li>Those with a Morphbank account, enter current User Name and Password > click <strong>Login.</strong></li>
<li>For a new user account, from 
<a href="<?php echo $config->domain; ?>About/Introduction/" target="_blank">http://www.morphbank.net/About/Introduction/</a>,
go to: 
<br />
<strong> Header Menu > Tools > Login > click the <font color="blue">user account</font> link</strong> to access
a Morphbank user account application (see next) or contact 
<strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.</li>
</ul>

<h2>Application Form for New Morphbank Account</h2>
<p>All fields with an <font color="red">*</font> are
required fields.
</p>
<img src="ManualImages/new_account2.png" hspace="20" />
<br />
<br />
<h2>Instructions for New Morphbank Account Application:</h2>
<ul>
<li><strong>Name</strong>: Enter your legal name the way that you wish it to appear in
Morphbank. First and Last names are required. Your name and personal information will be considered
confidential and not released to the public.</li>
<li><strong>Username and Password</strong>: You can request a username and password. If
there is a duplicate already in the system, javascript alerts you and you simply choose
a different username or password, as indicated.</li>
<li><strong>Email Address</strong>: There are times when users of Morphbank may wish to
contact you or send you Morphbank data. Morphbank may also send you email regarding updates to the database or information about new features. Enter the email where you
wish to receive such correspondence. This email can be used by you to send a request to the database
for help with a forgotten password or username.</li>
<li><strong>Phone Number</strong>: Phone numbers will not be entered into the database. This
will be used to contact you in case there are questions about the
application.</li>
<li><strong>Institute or Affiliation</strong>: Enter the university, museum, or other institute with
which you are associated.</li>
<li><strong>Address</strong>: Enter the complete mailing address where you wish to receive
hard copy correspondence from Morphbank. The state and country
are selected from drop-down lists.</li>
<!--<li><strong>Privilege, Primary, Secondary Taxon</strong>: These are categories used to group individuals as well as
to grant permissions to parts of the software.
<p>
<em>How they are used is currently undergoing revision to make them less restrictive.</em>

</p>
To insure accuracy, taxonomic <em>names need to be selected</em> from the Taxon Name Search screen (see below).
 Traverse through the levels until the appropriate scientific name is found. Then click the select icon , it will
automatically direct the user back to the add specimen screen and the appropriate name will be filled in.</li>-->

<!--<div class="specialtext3">
If a new taxon name needs to be added select the <img src="ManualImages/add_new_taxon_button.gif" /> button that is
visible from the family level. The <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php">Add Taxon Name</a> screen will popup. (This option is
only available for authorized users.) New applicants will need to interrupt the
application process and contact 
<strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong> if they need a particular
Taxon added before choosing their Privilege/Primary/Secondary TSN. For
complete instructions on this process see the <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php">Add Taxon Name</a> section of
this manual.
</div>
-->
<li><strong>Resume/CV</strong>: Your resume/CV will provide Morphbank with the verification of
your expertise. This is required so the proper privileges can be
assigned to your account. Using the Browse button, select the file
that contains a copy of your resume/CV. Graduate students obtain their own accounts in this same manner.</li>
<li><strong>Subscribe</strong> to the <strong>Morphbank mailing list</strong>. The box is selected by default. If you
do not want to be on the Morphbank mailing list click on the box to remove the check. Note this means you will not be notified of any upcoming changes to the software or additional features added. Note Morphbank rarely sends emails.
</li>
</ul>
<p>
When the form has been verified for correctness, click the Submit button to send
your request to Morphbank. A message confirming the submission will be seen
on the screen as in this example.
</p>
<img src="ManualImages/application_notice.png" hspace="20" />
<br />
<br />
Once the account has been generated, you will receive notification through
the email you entered on this form.
		  <br />
			<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/updateAccount.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
