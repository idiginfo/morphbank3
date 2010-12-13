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
