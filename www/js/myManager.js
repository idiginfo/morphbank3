// JavaScript Document

var contentId = "contentId";
var content = document.getElementById(contentId);
var targetPage;
var previousId = "imageTab";
var previous = null;
var contentUrl = "/MyManager/content.php";
var filterUrl = "/MyManager/filterContent.php";
var massActionUrl = "/MyManager/massActionContent.php";
var previousPage = contentUrl+"?id=imageTab";
var mmUrl = "/MyManager/index.php";

var content = new HttpClient();
	
	content.isAsync = true;
	
	content.callback = function(result) {
		
		enableLinks();
		
		document.getElementById(contentId).innerHTML =result;	
		if (document.getElementById("num")) {
			var numString = document.getElementById("num").innerHTML;
			
			//setupCalendar(numString);
		}
		setFooter();
		var formValues = getFormValues();
	
		var sessionPage = "/MyManager/updateSession.php?"+formValues;
		updateSession(sessionPage);
	}
	
	
	
	function replaceContent(newPage) {
		document.getElementById('footer').style.visibility = "hidden";
		disableLinks();
		
		
		content.makeRequest(newPage,null);
	}



	function imagePostit(e, info) {
		if (!e) var e = window.event;
		startPostIt(e, info);
	
	}
	function enableLinks() {
		document.getElementById("allTab").onclick = function() {switchTab(contentUrl+'?id=allTab', 'allTab');};
		document.getElementById("imageTab").onclick = function() {switchTab(contentUrl+'?id=imageTab', 'imageTab');};
		document.getElementById("specimenTab").onclick = function() {switchTab(contentUrl+'?id=specimenTab', 'specimenTab');};
		document.getElementById("viewTab").onclick = function() {switchTab(contentUrl+'?id=viewTab', 'viewTab');};
		document.getElementById("localityTab").onclick = function() {switchTab(contentUrl+'?id=localityTab', 'localityTab');};
		document.getElementById("taxaTab").onclick = function() {switchTab(contentUrl+'?id=taxaTab', 'taxaTab');};
		document.getElementById("collectionTab").onclick = function() {switchTab(contentUrl+'?id=collectionTab', 'collectionTab');};
		document.getElementById("annotationTab").onclick = function() {switchTab(contentUrl+'?id=annotationTab', 'annotationTab');}; 
		//document.getElementById("matrixTab").onclick = function() {switchTab(contentUrl+'?id=matrixTab', 'matrixTab');};  
		document.getElementById("pubTab").onclick = function() {switchTab(contentUrl+'?id=pubTab', 'pubTab');};  
	}
	
	function disableLinks() {
		document.getElementById("allTab").onclick = function(){}; 
		document.getElementById("imageTab").onclick =function(){};
		document.getElementById("specimenTab").onclick =function(){};
		document.getElementById("viewTab").onclick =function(){};
		document.getElementById("localityTab").onclick =function(){};
		document.getElementById("taxaTab").onclick =function(){};
		document.getElementById("collectionTab").onclick =function(){};
		document.getElementById("annotationTab").onclick =function(){};
		//document.getElementById("matrixTab").onclick =function(){};
		document.getElementById("pubTab").onclick =function(){};
	}
	

var dateAJAX = new HttpClient();
	
	dateAJAX.isAsync = true;
	
	dateAJAX.callback = function(result) {
		document.getElementById("HttpClientStatus").innerHTML = result;
		document.getElementById("updateLoaderId").style.display="none";
		//document.getElementById("HttpClientStatus").style.visibility="hidden";
		//document.getElementById("HttpClientStatus").style.display="block";
		
		if (document.getElementById("dateUpdated")) {
			var spanId = document.getElementById("dateUpdated").innerHTML;
			
			var spanIdArray = spanId.split('-');
			var rowColor = new Array();
			rowColor[0] = "#FFFFFF";
			rowColor[1] = "#E5E5F5";
			//alert(spanIdArray.length);
			
			var oneDate = spanId.split('|');
			var pipeLength = oneDate.length;
			var length = spanIdArray.length;
			
			if (pipeLength == 2) {
				var colorArray = oneDate[0].split('_');
				
				var displayDate = oneDate[1];
				//alert(displayDate);
				if (document.getElementById(oneDate[0])) {
						document.getElementById(oneDate[0]).innerHTML = displayDate;
				}
				var index = (parseInt(colorArray[1])%2);
				flashElement(oneDate[0], rowColor[index]);
				
				//Spry.Effect.Highlight(spanIdArray[0],{duration:  1300,  from: rowColor[index],  to:'#00FF00',restoreColor: rowColor[index],toggle:false});
			
			}
			else {
				var displayDate = spanIdArray[length-3]+"-"+spanIdArray[length-2]+"-"+spanIdArray[length-1];
				unCheckAllImages();	
				for (i=0; i < length-3; i++) { // last 3 is the date (yyyy-mm-dd)
					var colorArray = spanIdArray[i].split('_');
					var index = (parseInt(colorArray[1])%2);
					if (document.getElementById(spanIdArray[i])) {
						document.getElementById(spanIdArray[i]).innerHTML = displayDate;	
						flashElement(spanIdArray[i], rowColor[index]);
						//Spry.Effect.Highlight(spanIdArray[i],{duration:  1300,  from: rowColor[index],  to:'#00FF00',restoreColor: rowColor[index],toggle:false});
					}
				}
			}
		}
	}
	
	function updateDate(newPage) {
		document.getElementById("updateLoaderId").style.display="block";
		//alert(newPage);
		dateAJAX.makeRequest(newPage,null);
	}

var deleteAJAX = new HttpClient();

	deleteAJAX.isAsync = true;
	
	deleteAJAX.callback = function(result) {
		document.getElementById("updateLoaderId").style.display="none";
		document.getElementById("HttpClientStatus").innerHTML = result;	
		if (document.getElementById("deleteConfirm")) {
			var div = document.getElementById("deleteConfirm").innerHTML;
			Spry.Effect.DoFade(div, {duration: 800, from:  '100', to:  '0', toggle:false, finish: function(){document.getElementById(div).style.display = "none";} });
			
			//alert (div);
		}
		unCheckAllImages();	
		
	}
	
	function deleteObjects(newPage) {
		//document.getElementById('footer').style.visibility = "hidden";
		var confirmDelete = confirm("Delete Object?");
		if (confirmDelete) {
			document.getElementById("updateLoaderId").style.display="block";
			deleteAJAX.makeRequest(newPage,null);
		}
	}


var deleteMassAJAX = new HttpClient();

	deleteMassAJAX.isAsync = true;
	
	deleteMassAJAX.callback = function(result) {
		document.getElementById("updateLoaderId").style.display="none";
		document.getElementById("HttpClientStatus").innerHTML = result;	
		if (document.getElementById("deleteConfirm")) {
			var divString = document.getElementById("deleteConfirm").innerHTML;
			//alert(divString);
			var divArray = divString.split('_');
			var parent = document.getElementById("imagethumbspage");
			for (i=0; i < divArray.length; i++) {
				var div = document.getElementById(divArray[i]);
				//Spry.Effect.DropOut(divArray[i], {duration: 800, from: '100', to: '0', toggle: false, finish: function(){ div.style.display="none";} });
				Spry.Effect.DoFade(div, {duration: 800, from:  '100', to:  '0', toggle:false, finish: function(){ for (i=0; i < divArray.length; i++) {document.getElementById(divArray[i]).style.display="none";} } });
			}
			
		}
		unCheckAllImages();
		
	}
	
	function deleteMass() {
		//document.getElementById('footer').style.visibility = "hidden";
		var confirmDelete = confirm("Delete Objects?");
		if (confirmDelete) {
			document.getElementById("updateLoaderId").style.display="block";
			var idString = getCheckedObjects();
			var newPage = "/MyManager/deleteObjects.php?mass=true&"+idString+"&userId="+jsUserId;
			deleteMassAJAX.makeRequest(newPage,null);
			//alert(newPage);
		}
	}

	
var titleAJAX = new HttpClient();

	titleAJAX.isAsync = true;
	
	titleAJAX.callback = function(result) {
		var rowColor = new Array();
		rowColor[0] = "#E5E5F5";
		rowColor[1] = "#FFFFFF";
		
		document.getElementById("updateLoaderId").style.display="none";
		//document.getElementById("HttpClientStatus").style.display = "inline";
		document.getElementById("HttpClientStatus").innerHTML = result;	
		if (document.getElementById("titleConfirm")) {
			var divNum = document.getElementById("divNum").innerHTML;
			var index = (parseInt(divNum)%2);
			
			var div = document.getElementById("titleConfirm").innerHTML;
			document.getElementById("title"+divNum).innerHTML = document.getElementById("title").innerHTML;
			//alert(div+rowColor[index]);	
			//Spry.Effect.AppearFade(div, {duration: 800, from:  '100', to:  '0', toggle:false, finish: function(){document.getElementById(div).style.display = "none";} });
			flashElement(div, rowColor[index]);
			//alert (div);
		}
		
	}
	
	function updateTitle(newPage) {
		//document.getElementById('footer').style.visibility = "hidden";
			var reply = prompt("New Collection Title...");
			if (reply) {
				document.getElementById("updateLoaderId").style.display="block";
				newPage = newPage+"&title="+reply;
				titleAJAX.makeRequest(newPage,null);
			}
			
	}
	
var copyAJAX = new HttpClient();

	copyAJAX.isAsync = true;
	var blindToggle = false;
	var blindFailedToggle = false;
	
	copyAJAX.callback = function(result) {
		
		document.getElementById("updateLoaderId").style.display="none";
		document.getElementById("HttpClientStatus").innerHTML = result;	

		if (document.getElementById("trueTest")) {
			blurSuccessDown();		
			setTimeout("blurSuccessUp()", 1500);
		} else {
			blurFailedDown();		
			setTimeout("blurFailedUp()", 1500);			
		}
		
		unCheckAllImages();
	}
	
	function copyTo(type, value) {
			
		var tempArray = value.split('=');
		
		if (tempArray.length == 2)
			value = tempArray[1];
	
		document.getElementById("updateLoaderId").style.display="block";
		
		var idString = getCheckedObjects();
		var newPage = "/ajax/copyTo.php?type="+type+"&id="+value+"&"+idString+"&objectType="+tempArray[0];

		copyAJAX.makeRequest(newPage,null);
	}
	
	function blurSuccessDown() {
		if (blindToggle)
			Spry.Effect.DoBlind("ajaxSuccess", {duration: 500, from:  '0px', to:  '120px', toggle:true, setup: function(){document.getElementById("ajaxSuccess").style.display="inline";} });	
		else
			Spry.Effect.DoBlind("ajaxSuccess", {duration: 500, from:  '0px', to:  '120px' });
	}
	
	function blurSuccessUp() {
		Spry.Effect.DoBlind("ajaxSuccess", {duration: 500, from:  '120px', to:  '0px', toggle:true, finish: function(){document.getElementById("ajaxSuccess").style.display="none";} });	
		blindToggle = true;
	}
	
	function blurFailedDown() {
		if (blindFailedToggle)
			Spry.Effect.DoBlind("ajaxFailed", {duration: 500, from:  '0px', to:  '120px', toggle:true, setup: function(){document.getElementById("ajaxFailed").style.display="inline";} });	
		else
			Spry.Effect.DoBlind("ajaxFailed", {duration: 500, from:  '0px', to:  '120px' });
	}
	
	function blurFailedUp() {
		Spry.Effect.DoBlind("ajaxFailed", {duration: 500, from:  '120px', to:  '0px', toggle:true, finish: function(){document.getElementById("ajaxFailed").style.display="none";} });	
		blindFailedToggle = true;
	}


var sessionAJAX = new HttpClient();

	sessionAJAX.isAsync = false;
	
	sessionAJAX.callback = function(result) {
		// don't need to do anything with the result.  Just need to send form variables to get updated in the session.
	}
	
	function updateSession(page) {
		//alert(page);	
		//document.getElementById('footer').style.visibility = "hidden";
		sessionAJAX.makeRequest(page,null);
	}




function searchTab(tab, keywords) {
	document.managerForm.keywords.value= keywords;
	switchTab(contentUrl+"?id="+tab, tab);

}
	

function switchTab(page, elementId) {
	if (document.operationForm.goTo)
		document.operationForm.goTo.value = "";
		
	document.resultForm.currentTab.value = elementId;
		
	var formValues = getFormValues();
	targetPage = page+"&"+formValues;
	
	var sessionPage = "/MyManager/updateSession.php?"+formValues;

	updateSession(sessionPage);
	var element = document.getElementById(elementId);
	
	if (previous)
		previous.className = "TabbedPanelsTab";
	else
		document.getElementById(previousId).className = "TabbedPanelsTab";
	element.className = "TabbedPanelsTabSelected";
	
	
	if (element.blur)
		element.blur();
	
	previous = element;
	
	
	blind();
	setTimeout( "blindDown()" , 600);
	
	if (page.indexOf("image") != -1) 
		updateImageFilters("image");
		
	else if (page.indexOf("all") != -1) 
		updateImageFilters("all");
		
	else if (page.indexOf("view") != -1) 
		updateImageFilters("view");	
		
	else if (page.indexOf("locality") != -1) 
		updateImageFilters("locality");
		
	else if (page.indexOf("specimen") != -1) 
		updateImageFilters("specimen");
		
	else if (page.indexOf("collection") != -1) 
		updateImageFilters("collection");
		
	else if (page.indexOf("annotation") != -1) 
		updateImageFilters("annotation");
		
	else if (page.indexOf("matrix") != -1) 
		updateImageFilters("matrix");
		
	else if (page.indexOf("pub") != -1) 
		updateImageFilters("pub");
		
	else if (page.indexOf("taxa") != -1) 
		updateImageFilters("taxa");
		
		
		
	previousPage = page;
	
	
}

function changePage(page) {
	targetPage = page;
	
	blind();
	setTimeout( "blindDown();" , 600);
}

function updateImageFilters(id) {
	var formVars = getFormValues();
	
	// fading the filters not working great on all platforms
	//blindImageFilters();
	//Spry.Utils.updateContent("imageFilters", filterUrl+"?filterId="+id+"&"+formVars, function () {blindImageFilters();});
	
	if (document.getElementById("imageFilters"))
		Spry.Utils.updateContent("imageFilters", filterUrl+"?filterId="+id+"&"+formVars);
	
	if (document.getElementById("massActionOptions"))
		Spry.Utils.updateContent("massActionOptions", massActionUrl+"?actionId="+id+"&"+formVars);
	
}

function blindImageFilters() {
	imageFilter.start();
	//Spry.Effect.DoFade("imageFilters", {duration: 500, from:  '100', to:  '0', toggle:true});
	//Spry.Effect.AppearFade("massActionOptions", {duration: 500, from:  '100', to:  '0', toggle:true});
}

function blindDown() {
	Spry.Utils.updateContent(contentId, targetPage, function () {blind();});
			
}


function blindDownContent(string) {	
	Spry.Utils.updateContent(contentId, string);	
}

function blind() {	
	Spry.Effect.DoFade(contentId, {duration: 500, from:  '100', to:  '0', toggle:true});
	//Spry.Effect.Blind(contentId, {duration: 500, from:  '100%', to:  '0%', toggle:true});	
}

function flashElement(id, color) {
	Spry.Effect.DoHighlight(id,{duration:  700,  from: color,  to:'#00FF00',restoreColor: color, toggle:false, finish: function(){
		Spry.Effect.DoHighlight(id,{duration:  700,  from: color,  to:'#00FF00',restoreColor: color, toggle:false}); } 
	});
}

function manager_checkEnterGoToPage (e) {
	var characterCode = manager_returnKeyCode(e);
	
	if(characterCode == 13) {
		//if generated character code is equal to ascii 13 (if enter key)
		manager_goToPage();
		return false;
	} else {
		return true;
	}
}


function manager_checkEnter (e) {
	var characterCode = manager_returnKeyCode(e);
	
	if(characterCode == 13) {
		//if generated character code is equal to ascii 13 (if enter key)
		manager_submitForm();
		return false;
	} else {
		return true;
	}
}

function manager_returnKeyCode(e) {
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return false;
	
	return keycode;
}

function manager_goToPage(pageNum) {
	if (pageNum)
		document.operationForm.goTo.value=pageNum;
		
	var formValues = getFormValues();
	var updatedPage = previousPage+"&"+formValues
	var sessionPage = "/MyManager/updateSession.php?"+formValues;;
	updateSession(sessionPage);
	changePage(updatedPage);
}

function manager_submitForm() {
	document.operationForm.goTo.value="";
	var formValues = getFormValues();
	var updatedPage = previousPage+"&"+formValues+"&resetOffset=on";
	var sessionPage = "/MyManager/updateSession.php?"+formValues;
	//alert(updatedPage);
	//replaceContent(updatedPage);
	updateSession(sessionPage);
	changePage(updatedPage);
	
}

function manager_resetForm() {
	var managerForm = document.managerForm;
	var operationForm = document.operationForm;	
	
	managerForm.keywords.value = "";
	operationForm.goTo.value = "";
	managerForm.listField1.value = "";
	managerForm.listField2.value = "";
	managerForm.listField3.value = "";
	
	if (managerForm.orderAsc1)
		managerForm.orderAsc1[0].checked=true;
		
	if (managerForm.orderAsc2)
		managerForm.orderAsc2[0].checked=true;
		
	if (managerForm.orderAsc3)
		managerForm.orderAsc3[0].checked=true;
	
	if (managerForm.taxaOtuToggle)
		managerForm.taxaOtuToggle[0].checked=true;
		
	if (managerForm.characterCollectionToggle)
		managerForm.characterCollectionToggle[0].checked=true;
		
	if (managerForm.limit_contributor)
		managerForm.limit_contributor.checked=false;
		
	if (managerForm.limit_submitter)
		managerForm.limit_submitter.checked=false;
	
	if (managerForm.limit_current)
		managerForm.limit_current.checked=false;
	
	if (managerForm.limit_any)
		managerForm.limit_any.checked=false;
	
	manager_submitForm();
}



function massCheckboxOperation() {
	
	var selectBoxValue = document.operationForm.massOperation.value;
	if (selectBoxValue == "changeDate") {
		showCalendarMass();
	}
	else if (selectBoxValue == "publishNow") {
		publishNowMass();	
	}
	else if (selectBoxValue == "copyToNewCollection") {
		var reply = prompt("Collection Title...");
		if (reply) {
			document.resultForm.action="../includes/copyToNewCollection.php?title="+reply;
			document.resultForm.submit();		
		} else
			alert("You must choose a collection title");
	}
	
	else if (selectBoxValue == "createPhyloChar") {
		document.resultForm.action="../includes/createPhyloChar.php";
		document.resultForm.submit();		
	
	} 
	
	else if (selectBoxValue == "createOTU") {
		var numPerPage = document.resultForm.elements.length;
		var checkBoxBool = false;
		
		for (var x = 0; x < numPerPage; x++) {
			if (document.resultForm.elements[x].type == "checkbox") {
				if (document.resultForm.elements[x].checked == true) {	
					checkBoxBool = true;
					break;
				}
			}
		}
		
		if (checkBoxBool) {
			document.resultForm.action="../Phylogenetics/Otu/addOtu.php";
			document.resultForm.submit();		
		} else
			alert("You must select at least one record to create an OTU.  Please use the checkbox");
	
	} 
	
	else if (selectBoxValue == "deleteObjects") {
		deleteMass();	
	}
	
	/*
	else if (selectBoxValue.indexOf("characterId") != -1) {
		document.resultForm.action="../includes/copyToCharacter.php?"+selectBoxValue;
		document.resultForm.submit();		
	}
	*/
	
	else if (selectBoxValue.indexOf("characterId") != -1) {
		copyTo("Character", selectBoxValue);	
	}
	
	else if (selectBoxValue.indexOf("otuId") != -1) {
		copyTo("Otu", selectBoxValue);	
	}

	/*
	else if (parseInt(selectBoxValue) != "NaN") {
		document.resultForm.action="../includes/copyToCollection.php?id="+selectBoxValue;
		document.resultForm.submit();		
	}*/
	
	else if (parseInt(selectBoxValue) != "NaN") {
		copyTo("Collection", selectBoxValue);	
	}
	
	
	
	//alert(idString+"\n"+spanString);
	//window.location.href=idString+spanString;
}


function getFormValues() {
	var returnValue;
	
	var managerForm = document.managerForm;
	var operationForm = document.operationForm;
	var resultForm = document.resultForm;
	
	
	if (managerForm.keywords)
		returnValue = "keywords="+managerForm.keywords.value;
	
	if (operationForm.numPerPage)
		returnValue = returnValue+"&numPerPage="+operationForm.numPerPage.value;
		
	if (operationForm.goTo)
		returnValue = returnValue+"&goTo="+operationForm.goTo.value;
		
	if (managerForm.listField1)
		returnValue = returnValue+"&listField1="+managerForm.listField1.value;
	
	if (managerForm.listField2)
		returnValue = returnValue+"&listField2="+managerForm.listField2.value;
	
	if (managerForm.listField3)
		returnValue = returnValue+"&listField3="+managerForm.listField3.value;
		
	if (managerForm.orderAsc1) {
		if (managerForm.orderAsc1[0].checked)
			returnValue = returnValue+"&orderAsc1="+managerForm.orderAsc1[0].value;
		else
			returnValue = returnValue+"&orderAsc1="+managerForm.orderAsc1[1].value;
	}
	
	if (managerForm.orderAsc2) {
		if (managerForm.orderAsc2[0].checked)
			returnValue = returnValue+"&orderAsc2="+managerForm.orderAsc2[0].value;
		else
			returnValue = returnValue+"&orderAsc2="+managerForm.orderAsc2[1].value;
	}
	
	if (managerForm.orderAsc3) {
		if (managerForm.orderAsc3[0].checked)
			returnValue = returnValue+"&orderAsc3="+managerForm.orderAsc3[0].value;
		else
			returnValue = returnValue+"&orderAsc3="+managerForm.orderAsc3[1].value;
	}
	
	if (resultForm.currentTab) 
		returnValue = returnValue+"&tab="+resultForm.currentTab.value;
	
	if (managerForm.taxaOtuToggle) {
		for (i=0; i < managerForm.taxaOtuToggle.length; i++) {
			if (managerForm.taxaOtuToggle[i].checked) {
				returnValue = returnValue+"&taxaOtuToggle="+managerForm.taxaOtuToggle[i].value;
				break;
			}
		}
	}
		
	if (managerForm.characterCollectionToggle) {
		for (i=0; i < managerForm.characterCollectionToggle.length; i++) {
			if (managerForm.characterCollectionToggle[i].checked) {
				returnValue = returnValue+"&characterCollectionToggle="+managerForm.characterCollectionToggle[i].value;
				break;
			}
		}
	}
	
	if (managerForm.limit_contributor) {
		if (managerForm.limit_contributor.checked == true)
			returnValue = returnValue+"&limit_contributor=true";
	}
	
	if (managerForm.limit_current) {
		if (managerForm.limit_current.checked == true)
			returnValue = returnValue+"&limit_current=true";
	}
	
	if (managerForm.limit_submitter) {
		if (managerForm.limit_submitter.checked == true)
			returnValue = returnValue+"&limit_submitter=true";
	}
	
	if (managerForm.limit_any) {
		if (managerForm.limit_any.checked == true)
			returnValue = returnValue+"&limit_any=true";
	}
	
	return returnValue;
}


function copyCollectionCharacter(e, id, source) {
	var copyDiv = document.getElementById('copyCollection');
	var characterLink = document.getElementById('copyCharacterLinkId');
	var colletionLink = document.getElementById('copyCollectionLinkId');
	
	var xyArray = getIconXY(e);
	
	copyDiv.style.top = (xyArray[1]-40)+"px";
	copyDiv.style.left = (xyArray[0]-210)+"px";
	
	characterLink.href = "/includes/copyCollection.php?id="+id+"&character=true&source="+source;
	colletionLink.href = '/includes/copyCollection.php?id='+id+"&source="+source;
	
	copyDiv.style.display="block";

}

function closeCopyCollection() {
	document.getElementById('copyCollection').style.display="none";	
}


function getIconXY(e) {
	
	mouseX = getMouseX(e);
	mouseY = getMouseY(e);
	
	var returnArray = new Array();
	
	returnArray[0] = mouseX;
	returnArray[1] = mouseY;
	
	return returnArray;
	
	
}

function mmRedirect(tab, keywords) {
	var sessionPage = "/MyManager/updateSession.php?tab="+tab;
	
	updateSession(sessionPage) ;
 
	window.location.href = mmUrl;
}

