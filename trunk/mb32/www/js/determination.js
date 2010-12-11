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
