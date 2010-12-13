function CheckValues(form) {
	if (form.first_Name.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in first name");
		return false;
	}
	if (form.last_Name.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in last name");
		return false;
	}
	if (form.uin.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in username");
		return false;
	}
	if (form.pin.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in password");
		return false;
	}
	if (form.email.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in e-mail");
		return false;

	}
	if (form.affiliation.value.length == 0) {
		alert("You must fill in all required fields (*)!. Fill in affiliation");
		return false;
	}
	if (form.privilegetsn.value.length == 0) {
		alert("You must fill in all requiredfields (*)!. Privelege TSN is required");
		return false;
	}
	if (form.primarytsn.value.length == 0) {
		alert("You must fill in all required fields (*)!. Primary TSN is required");
		return false;
	}

	if ((form.email.value.indexOf(".") < 2)
			|| (form.email.value.indexOf("@")) < 0) {
		alert("Your e-mail address is not in a right format");
		return false;
	}
	return true;
}
