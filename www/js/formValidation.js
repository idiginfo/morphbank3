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
function checkAnnotationForm() {
	
	var formName = document.form1;
	var DetAnnotation, source, resources, title, comment, date;
	var message = "";
	//alert(formName.typeAnnotation.value);  alert("came here");
	if (formName.typeAnnotation.value == "Determination") {
		
		if (formName.typeDetAnnotation.value != "newdet") {
			var radioBool = false; 
			var radioLength = formName.Taxon.length;
			//alert(radioLength);
			if (radioLength) {
				for (var i=0; i < radioLength; i++) {
					if (formName.Taxon[i].checked) {
						radioBool = true;
					}					
				}
			} else {
				if (formName.Taxon.checked) {
					radioBool = true;
				}
			}	
			
			if (radioBool)
				DetAnnotation = true;
			else {
				DetAnnotation = false;
				message += "\nTaxonomic Name (click one)";
			}
				
		} else {
			if (formName.Determination.value != "") {
				DetAnnotation = true;
			} else {
				DetAnnotation = false;
				message += "\nNew Taxon Name";				
			}
		}
		
		if (formName.sourceOfId.value != "") {
			source = true;
		} else {
			source = false;		
			message += "\nSource of Identification";
		}
		
		if (formName.resourcesused.value != "") {
			resources = true;
		} else {
			resources = false;
			message += "\nResources Used";
		}
		
		if (formName.title.value != "") {
			title = true;
		} else {
			title = false;
			message += "\nTitle";
		}
		
		if (formName.comment.value != "") {
			comment = true;
		} else {
			comment = false;
			message += "\nComments";
		}
		
		if (formName.dateToPublish.value != "") {
			date = true;
		} else {
			date = false;
			message += "\nDate To Publilsh";
		}
		
	} else {
		DetAnnotation = true;
		source = true;
		resources = true;
		
		if (formName.title.value != "") {
			title = true;
		} else {
			title = false;
			message += "\nTitle";
		}
		
		if (formName.comment.value != "") {
			comment = true;
		} else {
			comment = false;
			message += "\nComments";
		}	
		
		if (formName.dateToPublish.value != "") {
			date = true;
		} else {
			date = false;
			message += "\nDate To Publilsh";
		}
		
	}
	
	if (DetAnnotation && source && resources && title && comment && date) {
		//alert("Form Ok");
		formName.submit();
		
	}
	else {
		alert("Please fill in all the required fields marked with a red asterisk: "+message);
	}
}
