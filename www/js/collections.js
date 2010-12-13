var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {
		junkdrawer.restoreListOrder("boxes")
		
		dragsort.makeListSortable(document.getElementById("boxes"),
				saveOrder)		
		
		sliders = carpeGetElementsByClass(carpeSliderClassName) // Find the horizontal sliders.
		for (i = 0; i < sliders.length; i++) {
			sliders[i].onmousedown = slide // Attach event listener.
		}
		displays = carpeGetElementsByClass(carpeSliderDisplayClassName) // Find the displays.
		for (i = 0; i < displays.length; i++) {
			displays[i].onfocus = focusDisplay // Attach event listener.
		}
		uncheckall();
		setFooter();		
		
		// only set up collapsible panels if they exist
		if (document.getElementById) {
			if (document.getElementById('collectionList'))	
				var cp1 = new Spry.Widget.CollapsiblePanel("collectionList");
			
			if (document.getElementById('characterList'))	
				var cp1 = new Spry.Widget.CollapsiblePanel("characterList");
				
			if (document.getElementById('otuList'))	
				var cp1 = new Spry.Widget.CollapsiblePanel("otuList");
		}
		
	}

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
	}

	function speak(id, what) {
		var element = document.getElementById(id);
		element.innerHTML = 'Clicked ' + what;
	}

	function saveOrder(item) {
		var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register('dragend', function() {
			ToolMan.cookies().set("list-" + id, 
					junkdrawer.serializeList(list), 365)
		})
	}

<!-- 
	/* Function to submit form and change a hidden form variable called toolFlag, flagData and imageSize */
	
	function toggleIcons (value, imgSize) {
		var collectionForm = document.collectionForm;
		
		if (value) {
			collectionForm.iconFlag.value = "true";
			submitForm('','',imgSize);
			
		} else { 
			collectionForm.iconFlag.value = "false";
			submitForm('','',imgSize);
		}	
	}
	
	function confirmAction (flagValue, flagData, imgSize) {
		
		var message = "";
		
		if (flagValue == "label")
			message = "Label Checked Objects?";
		else if (flagValue == "delete")
			message = "Delete Selected Objects From Collection?";
		else
			message = "Ok?";
			
		var answer = confirm(message);
			
		if (answer)
			submitForm ( flagValue, flagData, imgSize);
			
	}
	
	
	function submitForm ( flagValue, flagData, imgSize ) {
		document.collectionForm.toolFlag.value = flagValue;
		document.collectionForm.flagData.value = flagData;
		document.collectionForm.imgSize.value = document.collectionForm.testSlider.value;
		document.collectionForm.submit(junkdrawer.inspectListOrder('boxes'));	
	}
	
	function openTitleEdit ( url ) {					
		 editWindow=window.open( url,"", "width=500,height=200,location=no,resizable=yes,scrollbars=yes,left=200,top=200");
		 editWindow.focus();  					 
	}
	
	/* Menu Functions */
	var timerIDCollection = null;
	var timerOnCollection = false;
	var timecountCollection = 600;	
	
	function startCollectionTime() {
		if (timerOnCollection == false) {
				timerIDCollection=setTimeout( "hideAllCollectionMenus()" , timecountCollection);
			timerOnCollection = true;
		}

	}			

	function stopCollectionTime() {
		if (timerOnCollection) {
				clearTimeout(timerIDCollection);
			timerIDCollection = null;
			timerOnCollection = false;
		}
	}

	function hideAllCollectionMenus() {
		hideMenu("copyMenu");
		hideMenu("moveMenu");
		hideMenu("labelMenu");
	}	
	
	
	var newColorHex = "c8f3d3";
	var originalColorHex = "eeeeff";
	
	var newColor = HexToDec(newColorHex);	
	var originalColor = HexToDec(originalColorHex);
	/*var originalColor = "rgb(238,238,255)";   
	
	new color possibilities     eeeefe
	RED:   e04949  e85d5d  e55454                         
	GREEN:  19e04c           
	YELLOW: f3f146  f2f06a  f4f388  f5f496                   
	
	*/
	
	/* Checks all the check boxes in the collectionForm Form */
	function checkall(icons)	{
		var checkBox = document.collectionForm.elements;
		
		if (icons) {
			for(i=0; i<checkBox.length; i++) {
				if(checkBox[i].type=="checkbox") {
					checkBox[i].checked=true;
					checkBox[i].parentNode.parentNode.style.backgroundColor=newColor;
				}
			}
		} else {
			for(i=0; i<checkBox.length; i++) {
				if(checkBox[i].type=="checkbox") {
					checkBox[i].checked=true;
					checkBox[i].parentNode.parentNode.parentNode.parentNode.parentNode.style.backgroundColor=newColor;
				}
			}
		
		
		}
	}
	/*
	function createState(characterId) {
			
		var checkBox = document.collectionForm.elements;
		var string = "";
			
		for(i=0; i<checkBox.length; i++) {
			if(checkBox[i].type=="checkbox") {
				if (checkBox[i].checked) {
					string = string+checkBox[i].value+"-";
				}
				
			}
		}
			
		//alert(string);	
		var url = jsDomainName+"myCollection/createState.php?idString="+string+"&characterId="+characterId;
		
		editWindow=window.open( url,"", "width=500,height=200,location=no,resizable=yes,scrollbars=yes,left=200,top=200");
		submitForm('default','default',80);
		editWindow.focus();  					 
	
	
	}
	*/
	
	function showCreateState() {
		document.getElementById("createStatePopup").style.display="block";	
			
		if(document.getElementById("stateTitleId").focus)
			document.getElementById("stateTitleId").focus();
	}
	
	function createState() {
			
		var checkBox = document.collectionForm.elements;
		var string = "";
			
		for(i=0; i<checkBox.length; i++) {
			if(checkBox[i].type=="checkbox") {
				if (checkBox[i].checked) {
					string = string+checkBox[i].value+"-";
				}
				
			}
		}
		
		string = string.substring(0, (string.length)-1);
		
		var stateTitle = document.collectionForm.stateTitle.value;
		var stateValue = document.collectionForm.stateValue.value;
		
		var flagDataString = string+"_"+stateTitle+"_"+stateValue;
		
		//alert("string:"+string+"\n statetitle: "+stateTitle+"\n statevalue:"+stateValue);
		
		//alert(flagDataString);
		
		submitForm('createState', flagDataString, '');	
	
	}
	
	function checkEnter (e) {
		var characterCode = returnKeyCode(e);
		
		if(characterCode == 13) {
			//if generated character code is equal to ascii 13 (if enter key)
			createState();
			return false;
		} else {
			return true;
		}
	}
	
	function returnKeyCode(e) {
		var keycode;
		if (window.event) keycode = window.event.keyCode;
		else if (e) keycode = e.which;
		else return false;
		
		return keycode;
	}
	
	/* Un-Checks all the check boxes in the collectionForm Form */
	function uncheckall(icons) {
		var checkBox = document.collectionForm.elements;
		
		if (icons) {
			for(i=0; i<checkBox.length; i++) {
				if(checkBox[i].type=="checkbox") {
					checkBox[i].checked=false;
					checkBox[i].parentNode.parentNode.style.backgroundColor=originalColor;
				}
			}
		} else {
			for(i=0; i<checkBox.length; i++) {
				if(checkBox[i].type=="checkbox") {
					checkBox[i].checked=false;
					checkBox[i].parentNode.parentNode.parentNode.parentNode.parentNode.style.backgroundColor=originalColor;
				}
			}
		
		
		}
	}
	
	function swapColor(e_id, i_id, icons) {
		/* e_id is the li element's id and i_id is the checkbox input's id 
		var elementId = document.getElementsByTagName('li')[e_id].style;*/
		
		if (icons)
			var elementId = e_id.parentNode.parentNode.style;
		else
			var elementId = e_id.parentNode.parentNode.parentNode.parentNode.parentNode.style;
		var inputId = document.getElementById(i_id);
		/*alert(elementId.backgroundColor+IE);*/
		if (elementId.backgroundColor==originalColor || elementId.backgroundColor=="" || elementId.backgroundColor=="#"+originalColorHex) {
			inputId.checked=true;
			elementId.backgroundColor=newColor;
			elementId.color="#000000";
		}
		else {
			inputId.checked=false;
			elementId.backgroundColor=originalColor;
			elementId.color="#000000";
			
		}		
	}
	
	function HexToDec(hexNumber)	{
		hexNumber = hexNumber.toUpperCase();
		
		a = GiveDec(hexNumber.substring(0, 1));
		b = GiveDec(hexNumber.substring(1, 2));
		c = GiveDec(hexNumber.substring(2, 3));
		d = GiveDec(hexNumber.substring(3, 4));
		e = GiveDec(hexNumber.substring(4, 5));
		f = GiveDec(hexNumber.substring(5, 6));
		
		x = (a * 16) + b;
		y = (c * 16) + d;
		z = (e * 16) + f;
		
		if (IE)
			return "rgb("+x+","+y+","+z+")";
		else
			return "rgb("+x+", "+y+", "+z+")"; 
	}
	
	function GiveDec(Hex) {
	   if(Hex == "A")
		  Value = 10;
	   else
	   if(Hex == "B")
		  Value = 11;
	   else
	   if(Hex == "C")
		  Value = 12;
	   else
	   if(Hex == "D")
		  Value = 13;
	   else
	   if(Hex == "E")
		  Value = 14;
	   else
	   if(Hex == "F")
		  Value = 15;
	   else
		  Value = eval(Hex);
	
	   return Value;
	}
	
/* Code to use AJAX to update the default thumb of a collection/character */
var updateThumbAJAX = new HttpClient();

	updateThumbAJAX.isAsync = true;
	
	updateThumbAJAX.callback = function(result) {
		document.getElementById("updateLoaderId").style.display="none";
		document.getElementById("HttpClientStatus").innerHTML = result;	
		
		if (document.getElementById("updateSuccess")) {
			blurSuccessDown();		
			setTimeout("blurSuccessUp()", 1500);
		} else {
			blurFailedDown();		
			setTimeout("blurFailedUp()", 1500);			
		}
		
		uncheckall(true);
		
	}
	
	function setThumb(collectionId) {
		var page = jsDomainName+"myCollection/updateThumb.php?collectionId="+collectionId;
		
		var newPage = page+"&thumbId="+getFirstCheckedTile();
		
		//alert(newPage);
		document.getElementById("updateLoaderId").style.display="block";
		updateThumbAJAX.makeRequest(newPage,null);
	}
	
	function getFirstCheckedTile() {
		var collectionForm = document.collectionForm;
		var numPerPage = collectionForm.elements.length;
		
		for (var x = 0; x < numPerPage; x++) {
			if (collectionForm.elements[x].type == "checkbox") {
				if (collectionForm.elements[x].checked == true) {
					return collectionForm.elements[x].value; 
				}
			}
		}
	}
