var timerID = null;
var timerOn = false;
var subTimer = null;
var subTimerOn = false;
var timerIDAnn = null;
var timerOnAnn = false;
var timecount = 600;				
var newwindow = '';

var iframeTop, iframeLeft, iframeWidth, iframHeight;

var IE = document.all?true:false;


var detect = navigator.userAgent.toLowerCase();
var browser, thestring, place;

if (checkIt('opera')) 
	browser = "Opera";	
else if (checkIt('safari')) 
	browser = "Safari"; 	

function checkIt(string) {
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}

function checkEnterKey(e, i) {		
	var characterCode;
	if(e && e.which) { //if which property of event object is supported (NN4)
			e = e;
			characterCode = e.which; //character code is contained in NN4 which property
	} else {
			e = event;
			characterCode = e.keyCode; //character code is contained in IE keyCode property
	}
	//alert(characterCode);
	if(characterCode == 13) {	
			//if generated character code is equal to ascii 13 (if enter key)
		//alert("checkEnterKey" + i);
		ajax_login_submit(); //submit the form
		//return false;
	} else {
			return false;
	}
} 
	
function checkOS() {
	if (checkIt('konqueror')) {
		browser = "Konqueror";
		OS = "Linux";
	}
	else if (checkIt('safari')) browser = "Safari";
	else if (checkIt('omniweb')) browser = "OmniWeb";
	else if (checkIt('opera')) browser = "Opera";
	else if (checkIt('webtv')) browser = "WebTV";
	else if (checkIt('icab')) browser = "iCab";
	else if (checkIt('msie')) browser = "Internet Explorer";
	else if (!checkIt('compatible')) {
		browser = "Netscape Navigator";
		version = detect.charAt(8);
	} else browser = "An unknown browser";
	
	if (!version) 
		version = detect.charAt(place + thestring.length);
	if (!OS) {
		if (checkIt('linux')) OS = "Linux";
		else if (checkIt('x11')) OS = "Unix";
		else if (checkIt('mac')) OS = "Mac";
		else if (checkIt('win')) OS = "Windows";
		else OS = "an unknown operating system";
	}
	return browser;
}


var loginAJAX = new HttpClient();
	
	loginAJAX.isAsync = true;
	loginAJAX.requestType = 'POST';
	var heightAdjust = false;
	//loginAJAX.setHeader('Content-type', 'application/x-www-form-urlencoded;charset=UTF-8;');
	
	loginAJAX.callback = function (result) {
		//alert(result);
		if (result != 'false') {
			
			/*
			var JSONFile = result;  // set the JSON string that comes from the server to a variable
			eval(JSONFile); // evaluate the JSON string to execute the string as code, and create a javascript object (objInfo) with value/property pairs
			
			alert(objInfo.loginBox);
			*/
			
			document.getElementById('ajaxHiddenDiv').innerHTML = result;
			
			document.getElementById('mainHeaderLoginId').innerHTML = document.getElementById('ajaxLoginInfoId').innerHTML;
			document.getElementById('groupSelectMenu').innerHTML = document.getElementById('ajaxGroupListId').innerHTML;
			document.getElementById('Tools').innerHTML = document.getElementById('ajaxToolMenuId').innerHTML;
			hide_login_ajax();
			
		} else {
			var loginMessage = document.getElementById('loginMessage');
			var loginBox = document.getElementById("ajaxLogin");
								   
			if (document.getElementById("errorNodeId")) {
				var h1Node = document.getElementById("errorNodeId");
				h1Node.parentNode.removeChild(h1Node);
			}
			
			if (!heightAdjust) {
				var loginBoxHeight = parseInt(loginBox.style.height);
					loginBoxHeight += 30;
				loginBox.style.height = loginBoxHeight + "px";
				heightAdjust = true; 
			}
			
			var errorNode = document.createElement('h1');
				errorNode.innerHTML = "Please try again<br />";
				errorNode.id = "errorNodeId";
				errorNode.className = "red";
				
			loginMessage.appendChild(errorNode);
			Spry.Effect.DoShake("errorNodeId");
			
		}
	
		
		
	}
	
	function ajax_login_submit() {
		var form_action_url = '';
		var payload = 'ajax=true';
		
		var loginForm = document.loginForm;
		
		for (var i = 0; i < loginForm.elements.length; i++) {
			if (payload != "")
				payload += "&";
			
			payload += encodeURIComponent(loginForm.elements[i].name) + "=" + encodeURIComponent(loginForm.elements[i].value);
		}
		
		//alert(payload);
		loginAJAX.makeRequest('/ajax/checkLogin.php', payload, 'application/x-www-form-urlencoded;charset=UTF-8;');
		return false;
	}

/*check user privs for edit; associated file /ajax/checkPriv.php*/
var checkUserAJAX = new HttpClient();
	
	checkUserAJAX.isAsync = true;

	checkUserAJAX.callback = function(result) {
		// evaluate the JSON string to create the object.  
		// result string is in the form of:    obj = {"name" : "value", "name" : "value"}
		eval(result);
		
		if (obj.response) {
				window.location.href = obj.url;
		} else {
			alert("Permission Denied");
		}
	}

/*check user privs for edit; associated file /ajax/checkPriv.php*/
function checkUser(url,objId, operation) {
		
		Spry.Utils.updateContent("ajaxHiddenDiv", "/ajax/checkUser.php?id="+objId, function(){
			if (document.getElementById("ajaxHiddenDiv").innerHTML == "TRUE") {
				openPopup(url);
			} else {
				alert ('You do not have permission to edit this object.\nMessage: '+document.getElementById("ajaxHiddenDiv").innerHTML);
			}
		});
		}
/*
	function checkUser(url,objId, operation) {
			checkUserAJAX.makeRequest('/ajax/checkUser.php?id='+objId+'&operation='+operation, null);
	}
*/

function expandMenu( targetId){
	if (document.getElementById){
		target = document.getElementById( targetId );
		if (target.style.display == "none"){
			target.style.display = "inline";
		} 
		else {
			target.style.display = "none";
		}
	}
}

function expandMenuOptions( targetId, linkElement, groupMenu ){
	if (document.getElementById){
		if (linkElement) {
			menuTarget = document.getElementById( "Tools" );
			
			// if the menu position needs to be calculated
			// i.e. if the menu is a submenu
			if (groupMenu != "notMenu") {
				var currTop=0;
				var obj = linkElement;
				
				// loop through the parents of the object and add their height together
				do {
					currTop += obj.offsetTop;
				} while (obj = obj.offsetParent);
				
				//alert(linkElement.offsetTop);
				document.getElementById( targetId ).style.left = (menuTarget.offsetLeft+menuTarget.offsetWidth)+"px";
				document.getElementById( targetId ).style.top = currTop+"px";
			}
			
			
			document.getElementById( targetId ).style.display = "block";
			
		}
		else {
			menuTarget = document.getElementById( targetId );
			menuTarget.style.display = "block";
		}
			
		iframeMenuTarget = document.getElementById( "iFrameMainMenuId" );
		
		
		
			
			var iframeTop = menuTarget.offsetTop;
			var iframeLeft = menuTarget.style.left;
			var iframeWidth = menuTarget.offsetWidth;
			var iframeHeight = menuTarget.offsetHeight;
			
			if (linkElement) {
				fixSubMenu(targetId);
			} 
				
			iframeMenuTarget.style.top = iframeTop+"px";
			iframeMenuTarget.style.left = iframeLeft;
			iframeMenuTarget.style.width = iframeWidth+"px";
			iframeMenuTarget.style.height = iframeHeight+"px";
			iframeMenuTarget.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';
			
			/*alert("top: "+iframeTop+" Left: "+iframeLeft+" Width: "+iframeWidth+" Height: "+iframeHeight);
			alert(detect+"\nBrowser:"+browser);*/
			
			if (browser != "Opera") {
				if(browser != "Safari")
					iframeMenuTarget.style.display = "block";
			}
			
		
		
	}
}

function fixSubMenu(targetId) {
	if (document.getElementById){
		var menuTarget = document.getElementById( targetId );
		var iframeMenuTarget = document.getElementById( "iFrameSubMenuId" );
		
		var iframeTop = menuTarget.offsetTop;
		var iframeLeft = menuTarget.style.left;
		var iframeWidth = menuTarget.offsetWidth;
		var iframeHeight = menuTarget.offsetHeight;
		
			
		iframeMenuTarget.style.top = iframeTop+"px";
		iframeMenuTarget.style.left = iframeLeft;
		iframeMenuTarget.style.width = iframeWidth+"px";
		iframeMenuTarget.style.height = iframeHeight+"px";
		iframeMenuTarget.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';
		
		/*alert("top: "+iframeTop+" Left: "+iframeLeft+" Width: "+iframeWidth+" Height: "+iframeHeight);
		alert(detect+"\nBrowser:"+browser);*/
		
		if (browser != "Opera") {
			if(browser != "Safari")
				iframeMenuTarget.style.display = "block";
		}
			
		
	}
	

}

function startTime() {
	if (timerOn == false) {
			timerID=setTimeout( "hideAllMenus()" , timecount);
		timerOn = true;
	}

}			

function expandGroupMenu(linkElement, subMenu) {
	var groupMenu = document.getElementById("groupSelectMenu");
	
	if (linkElement) {
		
		expandMenuOptions('groupSelectMenu', linkElement);
	} else {
		groupMenu.style.left = 65+"px";
		groupMenu.style.top = 70+"px";	
		
		//if the group menu is not a submenu, then don't calculate top and left in expandMenuOptions
		expandMenuOptions('groupSelectMenu', true, subMenu);
	}	
}

function stopTime() {
		if (timerOn) {
				clearTimeout(timerID);
			timerID = null;
			timerOn = false;
		}
}

function startSubTime() {
	if (subTimerOn == false) {
			subTimer=setTimeout( "hideSubMenus()" , timecount);
		subTimerOn = true;
	}
	
	startTime();

}			

function stopSubTime() {
	if (subTimerOn) {
			clearTimeout(subTimer);
		subTimer = null;
		subTimerOn = false;
	}
	
	stopTime();
}

function hideSubMenus() {
	hideMenu ( 'groupSelectMenu');
	hideMenu ( 'submitSelectMenu');
	hideMenu ( 'editSelectMenu');
	hideMenu ( 'managerSelectMenu');
	//hideMenu ( 'iFrameMainMenuId');
	hideMenu ( 'iFrameSubMenuId');
}


function hideMenu ( menu) {
	if(document.getElementById(menu))
		document.getElementById(menu).style.display="none";				
}

function hideAllMenus() {
	hideMenu("About");
	hideMenu("Browse");
	hideMenu("Search");
	hideMenu("Tools");
	hideMenu("Help");
	hideMenu("iFrameMainMenuId");
}	
			
function openPopup ( url, w, h ) {	
	var width = (w) ? "width="+w : "width="+870; 
	var height = (h) ? "height="+h : "height="+650;
	var paramString = width+','+height+',location=no,resizable=yes,scrollbars=yes,left=0,top=0';
	
	/*alert( url+' '+paramString);*/
	
	newwindow=window.open( url,'',paramString);
	newwindow.focus();  					 
}

function openExtLink ( url ) {	
	
	var paramString = "location=yes,titlebar=yes,status=yes,directories=yes,toobar=yes,menubar=yes,resizable=yes,scrollbars=yes,left=50,top=0";
	
	newwindow=window.open( url,'',paramString);
	newwindow.focus();  					 
}
				
function loadInOpener(url) {					
	top.opener.location.href = url;		
	opener.focus();				
}

var changeGroupAJAX = new HttpClient();
	
	changeGroupAJAX.isAsync = true;	
	var ajaxDivId = "ajaxHiddenDiv";		
	var groupNameMenuId = "groupNameMenuId";
	
	changeGroupAJAX.callback = function (result) {	
		//alert(result);
		
		var ajaxDiv = document.getElementById(ajaxDivId);
								
		var groupNameMenu = document.getElementById(groupNameMenuId);

		document.getElementById(ajaxDivId).innerHTML = result;
		
		if (result != "FALSE") {
				//alert(document.getElementById('newGroupId').innerHTML);
				var newGroupName = document.getElementById('newGroupId').innerHTML;
				
				document.getElementById('Tools').innerHTML = document.getElementById('ajaxToolMenuId').innerHTML;
				document.getElementById(groupNameMenuId).innerHTML = newGroupName;
				
				Spry.Effect.DoShake(groupNameMenuId, {duration: 300, setup: function(){document.getElementById(groupNameMenuId).innerHTML = newGroupName; }, finish: function(){document.getElementById(groupNameMenuId).innerHTML = newGroupName; }});
				//groupChangeEffect.start();	
		}
			
			hideSubMenus();		
	}



function changeGroup(groupId) {
	var changeGroupUrl = "/ajax/changeGroup.php?groupId="+groupId;
	
	changeGroupAJAX.makeRequest(changeGroupUrl,null);
}


function show_login_ajax() {
	grayOut(true);
	//alert("Success!");
	
	var tbody = document.getElementsByTagName("body")[0];
	var tnode = document.createElement('div');
		tnode.style.position = 'absolute';
		tnode.style.top = '0px';
		tnode.style.left = '300px'; 
		tnode.style.width = '300px';
		//tnode.style.height = '200px';
		tnode.style.zIndex = 51;
		tnode.id = "ajaxLogin";
		//tnode.style.overflow='hidden'; // Try to avoid making scroll bars            
    tnode.style.visibility='hidden'; 
	tbody.appendChild(tnode);
	
	var loginBox = document.getElementById('ajaxLogin');
	
	var loginBoxUrl = '/ajax/loginBox.php';
	
	Spry.Utils.updateContent('ajaxLogin', loginBoxUrl, 
							 
		function () { // callback/finish function			
			Spry.Effect.DoBlind('ajaxLogin', {duration: 400, from: "0%", to: "100%", toggle: true});
      // Need short delay to give form time to load before focus
      setTimeout("try{document.getElementById('username').focus();}catch(error){}",100); 
		} 
	);			
}

function hide_login_ajax() {
	Spry.Effect.DoBlind('ajaxLogin', {duration: 400, from: "100%", to: "0%", toggle: true, 
						finish: function() {
							var el = document.getElementById('ajaxLogin');
							//alert(el.parentNode.removeChild);
							try {
								el.parentNode.removeChild(el);
							} catch(e) {
								el.style.display="none";
							}
							grayOut(false);	
							
						}});
	
}

function grayOut(vis, options) {
  // Pass true to gray out screen, false to ungray
  // options are optional.  This is a JSON object with the following (optional) properties
  // opacity:0-100         // Lower number = less grayout higher = more of a blackout 
  // zindex: #             // HTML elements with a higher zindex appear on top of the gray out
  // bgcolor: (#xxxxxx)    // Standard RGB Hex color code
  // grayOut(true, {'zindex':'50', 'bgcolor':'#0000FF', 'opacity':'70'});
  // Because options is JSON opacity/zindex/bgcolor are all optional and can appear
  // in any order.  Pass only the properties you need to set.
  var options = options || {}; 
  var zindex = options.zindex || 50;
  var opacity = options.opacity || 60;
  var opaque = (opacity / 100);
  var bgcolor = options.bgcolor || '#000000';
  var dark=document.getElementById('darkenScreenObject');
  if (!dark) {
    // The dark layer doesn't exist, it's never been created.  So we'll
    // create it here and apply some basic styles.
    // If you are getting errors in IE see: http://support.microsoft.com/default.aspx/kb/927917
    var tbody = document.getElementsByTagName("body")[0];
    var tnode = document.createElement('div');           // Create the layer.
        tnode.style.position='absolute';                 // Position absolutely
        tnode.style.top='0px';                           // In the top
        tnode.style.left='0px';                          // Left corner of the page
        tnode.style.overflow='hidden';                   // Try to avoid making scroll bars            
        tnode.style.display='none';                      // Start out Hidden
        tnode.id='darkenScreenObject';                   // Name it so we can find it later
    tbody.appendChild(tnode);                            // Add it to the web page
    dark=document.getElementById('darkenScreenObject');  // Get the object.
  }
  if (vis) {
    // Calculate the page width and height 
    if( document.body && ( document.body.scrollWidth || document.body.scrollHeight ) ) {
        var pageWidth = document.body.scrollWidth+'px';
        var pageHeight = document.body.scrollHeight+'px';
    } else if( document.body.offsetWidth ) {
      var pageWidth = document.body.offsetWidth+'px';
      var pageHeight = document.body.offsetHeight+'px';
    } else {
       var pageWidth='100%';
       var pageHeight='100%';
    }   
    //set the shader to cover the entire page and make it visible.
    dark.style.opacity=opaque;                      
    dark.style.MozOpacity=opaque;                   
    dark.style.filter='alpha(opacity='+opacity+')'; 
    dark.style.zIndex=zindex;        
    dark.style.backgroundColor=bgcolor;  
    dark.style.width= pageWidth;
    dark.style.height= pageHeight;
    dark.style.display='block';                          
  } else {
     dark.style.display='none';
  }
}

function checkTsn (url, objectTSN, userTSN) {
			
	Spry.Utils.updateContent("ajaxHiddenDiv", "/ajax/checkTsn.php?objectTSN="+objectTSN+"&userTSN="+userTSN, 
		function () {
			if (document.getElementById("ajaxHiddenDiv").innerHTML == "TRUE") {
				window.location.href = url;
			} else {
				alert ("You do not have proper privledges to add a name at this level.");
			}
		}
	);
	
	

}

