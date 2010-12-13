<script language = "JavaScript" type ="text/javascript">
	
	function pop(child, url) {

		if(child == 'TSN' || child == 'Locality' || child == 'Specimen' || child == 'View'){

			child = window.open(url, child, 'directories=0,dependent=1,menubar=0,resizable=yes,top=20,left=20,width=950, height=800,scrollbars=yes, hotkeys =1', 'popup');

		}else{
			child = window.open(url, child, 'directories=0,dependent=1,menubar=0,resizable=yes,top=20,left=20,width=550,height=250,scrollbars=yes, hotkeys =1', 'popup');

			if (window.focus) {
				child.focus();
			} 
		}
	}

        function updateTSN(value, value2) {
	            
		if(document.forms[0].viewTSN){
			document.forms[0].viewTSN.value=value;                
			document.forms[0].viewTSName.value=value2;                
		}else{
			if(document.forms[0].TSN)
				document.forms[0].TSN.value=value;    
			else
				document.forms[0].DeterminationId.value=value;                
		        document.forms[0].Determination.value=value2;                
		}
	}

	function update(child, value, name) {
	            
		if(child == "Sex"){
			document.forms[0].Sex.options[1] = new Option(name, value, '', 'true'); 
			//document.forms[0].elements[4].options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Form"){
		        document.forms[0].Form.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Stage"){
		        document.forms[0].DevelopmentalStage.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Part"){
		        document.forms[0].SpecimenPart.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Angle"){
		        document.forms[0].ViewAngle.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "ImagingTechnique"){
		        document.forms[0].ImagingTechnique.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Preparation"){
		        document.forms[0].ImagingPreparationTechnique.options[1] = new Option(name, value, '', 'true'); 
		}else if(child == "Location"){
			document.forms[0].LocalityId.value=value; 
		        document.forms[0].Locality.value=name;   
		}

	}


</script>
