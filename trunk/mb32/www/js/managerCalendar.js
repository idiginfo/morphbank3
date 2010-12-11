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
// JavaScript Document

function dateStatusHandler(date) {
	var today = new Date();
	//alert("date: "+date.getTime()+"Today: "+today.getTime())
	
	
	
	if (date.getFullYear() == today.getFullYear() && date.getMonth() == today.getMonth() && date.getDate() == today.getDate()) {
		return true;	
	} else
		return (date.getTime() < today.getTime() && (date.getDate() != today.getDate() || date.getMonth() != today.getMonth() || date.getFullYear() != today.getFullYear())) ? true : false;
	
}


function setupCalendar(num) {
	var d = new Date();
	
	//var startDate = new Date(d.getTime() + 1*24*60*60*1000);
	var startDate = new Date();
	
	startDate.setMonth(startDate.getMonth()+1);
	
	var numArray = num.split('-');
	
	//alert(startDate);
	
	for(i=0;i<numArray.length-1;i++) {
		Calendar.setup({
					inputField     :    "dateField_"+numArray[i],
					ifFormat       :    "%Y-%m-%d",
					displayArea    :    "dateTest_"+numArray[i],
					daFormat       :    "%Y-%m-%d",
					date           : startDate,
					range          :    [d.getFullYear(), 2999],
					
					dateStatusFunc : dateStatusHandler,
					onClose		   : myClosed
				});
		Calendar.setup({
					inputField     :    "dateField_"+numArray[i],
					ifFormat       :    "%Y-%m-%d",
					displayArea    :    "dateTest_"+numArray[i],
					button         :    "calId"+numArray[i],
					daFormat       :    "%Y-%m-%d",
					date           : startDate,
					range          :    [d.getFullYear(), 2999],
					
					dateStatusFunc : dateStatusHandler,
					onClose		   : myClosed
				});
	}
}

function myClosed(cal) {
	var newDate = cal.date.print(cal.daFormat);
	
	var divToChange;
	var count = 1;
	var page = jsDomainName+"MyManager/updateDate.php?";
	
	var objectId = "id="+cal.mbObjectId;
	var dateString = "&date="+newDate;
	var userString = "&userId="+jsUserId;
	var groupString = "&groupId="+jsGroupId;
	var spanId = "&spanId="+cal.mbSpanId;
	//alert(idString+"\n"+objectString+"\n"+dateString);
	//window.location.href=idString+spanString+dateString;
	if (cal.dateClicked) {
		//alert(page+objectId+dateString+userString+groupString+spanId);
		updateDate(page+objectId+dateString+userString+groupString+spanId);
	
	}
	cal.hide();
	return true;
}


function myClosedMass(cal) {
	var newDate = cal.date.print(cal.daFormat);	
	
	var divToChange;
	var count = 1;
	var idString = jsDomainName+"MyManager/updateDate.php?";
	var objectString=getCheckedObjects();
	
	var dateString= "&date="+newDate;
	var userString = "&userId="+jsUserId;
	var groupString = "&groupId="+jsGroupId;
	//alert(idString+"\n"+objectString+"\n"+dateString);
	//window.location.href=idString+spanString+dateString;
	if (cal.dateClicked)
		updateDate(idString+objectString+dateString+userString+groupString);
	cal.hide();
	return true;
}

function publishNowMass() {
	var date = new Date();
	
	var todaysDateString = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	//alert(dateString);
	
	var idString = jsDomainName+"MyManager/updateDate.php?";
	var objectString=getCheckedObjects();
	
	var dateString= "&date="+todaysDateString;
	var userString = "&userId="+jsUserId;
	var groupString = "&groupId="+jsGroupId;
	//alert(idString+"\n"+objectString+"\n"+dateString);
	//window.location.href=idString+spanString+dateString;
	
	updateDate(idString+objectString+dateString+userString+groupString);

	
}


function getCheckedObjects() {
	var idString = "idString=";
	var spanString = "&spanString=";
	var divString = "&divString="
	var numPerPage = document.resultForm.elements.length;
	
	for (var x = 0; x < numPerPage; x++) {
		if (document.resultForm.elements[x].type == "checkbox") {
			if (document.resultForm.elements[x].checked == true) {
				idString =  idString+document.resultForm.elements[x].value;
				spanString = spanString+document.resultForm.elements[x].id;
				idString = idString+"_";
				spanString = spanString+"_";
				var tempArray = new Array();
				tempArray = document.resultForm.elements[x].id.split('-');
				divString = divString+"row"+tempArray[1]+"_";				
			}
		}
	}
	
	return idString+spanString+divString;
	
}

function showCalendarMass() {
	var d = new Date();
	var startDate = new Date();
	
	startDate.setMonth(startDate.getMonth()+6);
	var cal = new Calendar("0", startDate, onSelectSteve, myClosedMass);
	cal.setDateStatusHandler(dateStatusHandler);
	cal.setRange(d.getFullYear(), 2999);
	cal.setDateFormat("%Y-%m-%d");
	cal.daFormat = "%Y-%m-%d";
	
	cal.create();
	cal.showAt(600,175);	
	
}

function showCalendar(id, spanId, element) {
	var d = new Date();
	var startDate = new Date();
	
	startDate.setMonth(startDate.getMonth()+6);
	//alert(startDate);
	var cal = new Calendar("0", startDate, onSelectSteve, myClosed);
	cal.setDateStatusHandler(dateStatusHandler);
	cal.setRange(d.getFullYear(), 2999);
	cal.setDateFormat("%Y-%m-%d");
	cal.daFormat = "%Y-%m-%d";
	cal.mbObjectId = id;
	cal.mbSpanId = spanId;
	
	cal.create();
	cal.showAtElement(element);	
	
}


function onSelectSteve(cal) {
	//var p = cal.params;
	var update = cal.dateClicked;
	
	if (update && cal.dateClicked)
		cal.callCloseHandler();
}
