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
function toggle(userInput)

{
	el = document.getElementById("ddiv");
	XML1 = document.getElementById("XML");

	if (userInput == "Determination") {

		Spry.Utils.updateContent("checkPrivId",
				"/ajax/checkPriv.php?id=575002");

		if (document.getElementById("checkPrivId").innerHTML == "FALSE") {
			window.location = "/Annotation/notPrivledge.php";
			return;
		}

		el.style.display = "block";
		//document.forms[0].title.value="Determination";
	} else if (userInput == "TaxonName") {
		//alert("taxon name location is" + document.forms[0].taxon_name.value);
		location.href = document.forms[0].taxon_name.value;
	} else {
		el.style.display = "none";
		document.forms[0].title.value = "";
	}

	if (userInput == "XML")
		XML1.style.display = "block";
	else
		XML1.style.display = "none";
	setFooter();
}

function togglePS(userInput)

{
	el = document.getElementById("prepost");
	if (userInput == "agreewq" || userInput == "newdet")
		el.style.display = "block";
	else
		el.style.display = "none";
}
