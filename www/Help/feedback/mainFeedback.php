<?php

function mainFeedback() {

global $link,  $objInfo, $codeArray;

echoEmailValidation();

$email = '';

if ($objInfo->getUserId() != NULL) {
	//echo 'test';
	$sql = 'SELECT email FROM User WHERE id = '.$objInfo->getUserId().' ';
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));		
	
	if ($result) {
		$row = mysqli_fetch_array($result);
		$email = $row['email'];		
	}
}

$codeArray = getSpamCode();


echo '
		<div class="mainGenericContainer" style="width:650px;">
			<h1>Feedback Form</h1><br />
			
			<form name="emailForm" action="feedbackAction.php" method="post" onSubmit="return ValidateForm()">
				<table class="blueBorder" width="100%">';
				if ($_GET['id'] == 1) {
					echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message sent successfully</div></br /></td>
					</tr>';
				} elseif ($_GET['id'] == 2) {
					echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Contact MorphBank Administrators.</div></br /></td>
					</tr>';
				
				} elseif ($_GET['id'] == 3) {
					echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Please re-type the security code at the bottom.</div></br /></td>
					</tr>';
				
				} elseif ($_GET['id'] >= 4) {
					echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Contact MorphBank Administrators.</div></br /></td>
					</tr>';
				
				}
				echo '	
					<tr>
						<th align="right">To: </th>
						<td>Morphbank Administrators</td>
					</tr>
					
					<tr>
						<th align="right">From: </th>
						<td><input type="text" name="from" size="30" value="'.$email.'" /> <em>(valid email address)</em></td>
					</tr>
					
					<tr>
						<th align="right">Subject: </th>
						<td><input type="text" name="subject" size="30" /></td>
					</tr>
					
					<tr>
						<th valign="top">Message:</th>
						<td><textarea cols="60" rows="15" name="message"></textarea></td>
					</tr>
				</table>
				<table border="0" width="100%">	
					<tr>
						<td>
							<br /><h3>Please type the letters in this picture into the box below. </h3><br /><em>(To prevent spam. Not case sensitive.)</em><br /><br />
						</td>
					</tr>
					<tr>
						<td>
							<img src="/style/webImages/codes/'.$codeArray['graphic'].'" alt="graphic" />
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" name="spamCode" value="" />
						</td>
					</tr>
					<tr>
						<td align="right"><a href="javascript: document.forms[0].submit();" class="button smallButton"><div>Submit</div></a></td>
					</tr>
				</table>
				
				<input type="hidden" name="spamId" value="'.$codeArray['id'].'" />
						
			</form>		
		
		
		</div>  ';

	if ($objInfo->getUserId() != NULL) 
		echoFocus("subject");
	else
		echoFocus("from");


}
function echoEmailValidation() {

echo '
	<script type="text/javascript" language = "Javascript">
	/**
	 * DHTML email validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
	 */
	 
	 function ValidateForm(){
		var formId = document.emailForm;
		var emailID = formId.from;
				
		if ((emailID.value==null)||(emailID.value=="")){
			alert("Please Enter your Email ID");
			emailID.focus();
			return false;
		}
		if (echeck(emailID.value)==false){
			emailID.value="";
			emailID.focus();
			return false;
		}
		if ((formId.subject.value==null) || (formId.subject.value=="")) {
			alert("Please Fill in subject line.");
			formId.subject.focus();
			return false;		
		}
		if ((formId.message.value==null) || (formId.message.value=="")) {
			alert("Please Fill in message text.");
			formId.message.focus();
			return false;		
		}
		
		return true
	 }
	
	function echeck(str) {
	
			var at="@"
			var dot="."
			var lat=str.indexOf(at)
			var lstr=str.length
			var ldot=str.indexOf(dot)
			if (str.indexOf(at)==-1){
			   alert("Invalid E-mail ID")
			   return false
			}
	
			if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
			   alert("Invalid E-mail ID")
			   return false
			}
	
			if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
				alert("Invalid E-mail ID")
				return false
			}
	
			 if (str.indexOf(at,(lat+1))!=-1){
				alert("Invalid E-mail ID")
				return false
			 }
	
			 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
				alert("Invalid E-mail ID")
				return false
			 }
	
			 if (str.indexOf(dot,(lat+2))==-1){
				alert("Invalid E-mail ID")
				return false
			 }
			
			 if (str.indexOf(" ")!=-1){
				alert("Invalid E-mail ID")
				return false
			 }
	
			 return true					
		}
	
	
	</script>';

}

function echoFocus($field) {
	echo '
	<script type="text/javascript" language = "Javascript">
		document.emailForm.'.$field.'.focus();
	</script>';
}

?>
