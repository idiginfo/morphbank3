// JavaScript Document

var checkSpamAJAX = new HttpClient();
	
	checkSpamAJAX.isAsync = true;
	
	checkSpamAJAX.callback = function(result) {
		
		// set the JSON string that comes from the server to a variable.  String is in the form of "varName = {name: value, functionName: function(){}, name: value etc.....}
		var JSONFile = result;  
		
		// evaluate the JSON string to execute the string as code, and create a javascript object (spamObj) with value/property pairs
		eval(JSONFile); 
		
		if(spamObj.result == false) {
			alert("Please type the security code again. ");
		}		
		else if(document.forms[0].first_name.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in first name");
		}
	    else if(document.forms[0].last_name.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in last name");
		}
		else if(document.forms[0].uin.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in username");
		}
	    else if(document.forms[0].email.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in e-mail");
		}
		else if(document.forms[0].phone.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in phone number");
		}
	    else if(document.forms[0].affiliation.value.length==0){
			alert("You must fill in all the required fields (*)!. Fill in affiliation");
		}
	    else if(document.forms[0].privilegetsn.value.length==0){
			alert("You must fill in all the requiredfields (*)!. Privelege TSN is required");
		}
	    else if(document.forms[0].primarytsn.value.length==0){
			alert("You must fill in all the required fields (*)!. Primary TSN is required");
		}

		else if(document.forms[0].userResume.value.length==0){
			alert("Your resume/CV is required for verification of your expertise. Please upload your resume");
		}
		else if(document.forms[0].email.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
				alert("Your e-mail address is not in a right format");
		}
	   else {  
		  document.newUserForm.submit();
	  }
	
	
	}
	
	function checkSpam() {
		var id = document.newUserForm.spamId.value;
		var code = document.newUserForm.spamCode.value;
		
		var url = "/ajax/checkSpam.php?id="+id+"&code="+code;
		
		checkSpamAJAX.makeRequest(url, null);
	}
