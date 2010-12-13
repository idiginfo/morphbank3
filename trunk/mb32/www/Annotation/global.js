var Option;

window.onload = function() {
	var marker1 = new Image();
		marker1.src = "../style/webImages/UpperRightred-trans.png";
	var marker2 = new Image();
		marker2.src = "../style/webImages/LowerLeftred-trans.png";
	var marker3 = new Image();
		marker3.src = "../style/webImages/LowerRightred-trans.png";
	var marker4 = new Image();
		marker4.src = "../style/webImages/UpperLeftred-trans.png";
	
	var marker5 = new Image();
		marker5.src = "../style/webImages/UpperRightblack-trans.png";
	var marker6 = new Image();
		marker6.src = "../style/webImages/LowerLeftblack-trans.png";
	var marker7 = new Image();
		marker7.src = "../style/webImages/LowerRightblack-trans.png";
	var marker8 = new Image();
		marker8.src = "../style/webImages/UpperLeftblack-trans.png";
	
	var marker9 = new Image();
		marker9.src = "../style/webImages/UpperRightwhite-trans.png";
	var marker10 = new Image();
		marker10.src = "../style/webImages/LowerLeftwhite-trans.png";
	var marker11 = new Image();
		marker11.src = "../style/webImages/LowerRightwhite-trans.png";
	var marker12 = new Image();
		marker12.src = "../style/webImages/UpperLeftwhite-trans.png";
	if(self.setFooter) setFooter();
}


function mousePosX(e)
{
  var posx = 0;
  if (!e) var e = window.event;
  if (e.pageX)
    posx = e.pageX;
  else if (e.clientX && document.body.scrollLeft)
    posx = e.clientX + document.body.scrollLeft;
  else if (e.clientX && document.documentElement.scrollLeft)
    posx = e.clientX + document.documentElement.scrollLeft;
  else if (e.clientX)
    posx = e.clientX;
  return posx;
}

function mousePosY(e)
{
  var posy = 0;
  if (!e) var e = window.event;
  if (e.pageY)
    posy = e.pageY;
  else if (e.clientY && document.body.scrollTop)
    posy = e.clientY + document.body.scrollTop;
  else if (e.clientY && document.documentElement.scrollTop)
    posy = e.clientY + document.documentElement.scrollTop;
  else if (e.clientY)
    posy = e.clientY;
  return posy;
}

function realLeft(obj) {

	/*xOffset = eval(obj).offsetLeft;

	tempOb = eval(obj).offsetParent;*/
	
	xOffset = document.getElementById(obj).offsetLeft;

	tempOb = document.getElementById(obj).offsetParent;
	
  	while (tempOb != null) {

  		xOffset += tempOb.offsetLeft;

  		tempOb = tempOb.offsetParent;

  	}

	return xOffset;

}



function realTop(obj) {

	yOffset =document.getElementById(obj).offsetTop;

	tempOb = document.getElementById(obj).offsetParent;

	while (tempOb != null) {

  		yOffset += tempOb.offsetTop;

  		tempOb = tempOb.offsetParent;

  	}

	return yOffset;

}


function switchColor()
{
    var currentOption = Option;
	var currentTop = parseInt(document.getElementById("marker").style.top);
	var currentLeft = parseInt(document.getElementById("marker").style.left);
	
	
	Option = determineArrow();
	
	/* Figure out how much to shift the 120 x 120 image based on what the current state is vs the new state */
	if (currentOption.indexOf('1') != -1) {
		if (Option.indexOf('3') != -1) {
			document.getElementById("marker").style.left = (currentLeft - 120) + "px";
		}
		
		if (Option.indexOf('7') != -1) {
			document.getElementById("marker").style.top = (currentTop - 120) + "px";   			
		}
		if (Option.indexOf('5') != -1) {
			document.getElementById("marker").style.top = (currentTop - 120) + "px";   
			document.getElementById("marker").style.left = (currentLeft - 120) + "px"; 
		}
	}
		else if (currentOption.indexOf('3') != -1) {
			if (Option.indexOf('1') != -1) {
				document.getElementById("marker").style.left = (currentLeft + 120) + "px";
			}
			
			if (Option.indexOf('7') != -1) {
				document.getElementById("marker").style.top = (currentTop - 120) + "px"; 
				document.getElementById("marker").style.left = (currentLeft + 120) + "px";
			}
			if (Option.indexOf('5') != -1) {
				document.getElementById("marker").style.top = (currentTop - 120) + "px";   
			}		
		}
	
		else if (currentOption.indexOf('5') != -1) {
			if (Option.indexOf('1') != -1) {
				document.getElementById("marker").style.top = (currentTop + 120) + "px"; 
				document.getElementById("marker").style.left = (currentLeft + 120) + "px";
			}
			
			if (Option.indexOf('7') != -1) {
				document.getElementById("marker").style.left = (currentLeft + 120) + "px";
			}
			if (Option.indexOf('3') != -1) {
				document.getElementById("marker").style.top = (currentTop + 120) + "px";   
			}	
		}
		else if (currentOption.indexOf('7') != -1) {
			if (Option.indexOf('1') != -1) {
				document.getElementById("marker").style.top = (currentTop + 120) + "px"; 
			}
			
			if (Option.indexOf('3') != -1) {
				document.getElementById("marker").style.top = (currentTop + 120) + "px"; 
				document.getElementById("marker").style.left = (currentLeft - 120) + "px";
			}
			if (Option.indexOf('5') != -1) {
				document.getElementById("marker").style.left = (currentLeft - 120) + "px";   
			}	
		}
		

document.getElementById("marker").style.visibility="visible";  
 
}


function placeArrow(event,obj, width, height) {
	 
	 var curx = mousePosX(event) - realLeft(obj);
     var cury = mousePosY(event) - realTop(obj);

	
	Option = determineArrow();
	
	
	if (Option.indexOf('1') != -1) {
		document.getElementById("marker").style.top  = mousePosY(event) + "px";
        document.getElementById("marker").style.left = mousePosX(event)  + "px";
	}
		else if (Option.indexOf('3') != -1) {
			document.getElementById("marker").style.top  = mousePosY(event) + "px";
        	document.getElementById("marker").style.left = (mousePosX(event) - 120)  + "px";
			
		}
		else if (Option.indexOf('5') != -1) {
			document.getElementById("marker").style.top  = (mousePosY(event) - 120) + "px";
        	document.getElementById("marker").style.left = (mousePosX(event) - 120)  + "px";		
					
		}
		else if (Option.indexOf('7') != -1) {
			document.getElementById("marker").style.top  = (mousePosY(event) - 120) + "px";
        	document.getElementById("marker").style.left = mousePosX(event)  + "px";		
					
		}
		else if (Option.indexOf('Square') != -1) {
			document.getElementById("marker").style.top  = (mousePosY(event) - 120) + "px";
        	document.getElementById("marker").style.left = mousePosX(event)  + "px";
		}
    	else {
			document.getElementById("marker").style.top  = mousePosY(event) + "px";
        	document.getElementById("marker").style.left = mousePosX(event)  + "px";
		}
	 

document.getElementById("marker").style.visibility="visible";         

document.getElementById("xLocation").value= curx;

document.getElementById("yLocation").value= cury;
}




function determineArrow() {
	if(document.form1.arrowmark[0].checked && document.form1.color[2].checked) {
		Option = "redArrow1";
		document.form1.arrowc.value="redArrow1";
		document.getElementById("marker").src = "../style/webImages/UpperLeftred-trans.png";
	}  
		else if(document.form1.arrowmark[1].checked && document.form1.color[2].checked) {
			Option = "redArrow3";
			document.form1.arrowc.value="redArrow3";
			document.getElementById("marker").src = "../style/webImages/UpperRightred-trans.png";
		}
		
		else if(document.form1.arrowmark[2].checked && document.form1.color[2].checked) {
			Option="redArrow5";
			document.form1.arrowc.value="redArrow5";
			document.getElementById("marker").src = "../style/webImages/LowerRightred-trans.png";
		}
		else if (document.form1.arrowmark[3].checked && document.form1.color[2].checked) { 
			Option ="redArrow7";
			document.form1.arrowc.value="redArrow7";
			document.getElementById("marker").src = "../style/webImages/LowerLeftred-trans.png";
		}
		else if(document.form1.arrowmark[0].checked && document.form1.color[0].checked) { 
			Option="blackArrow1";
			document.form1.arrowc.value="blackArrow1";
			document.getElementById("marker").src = "../style/webImages/UpperLeftblack-trans.png";
		}   	 
		else if(document.form1.arrowmark[1].checked && document.form1.color[0].checked) { 
			Option="blackArrow3";
			document.form1.arrowc.value="blackArrow3";
			document.getElementById("marker").src = "../style/webImages/UpperRightblack-trans.png";
		}
		else if(document.form1.arrowmark[2].checked && document.form1.color[0].checked) { 
			Option="blackArrow5";
			document.form1.arrowc.value="blackArrow5";
			document.getElementById("marker").src = "../style/webImages/LowerRightblack-trans.png";
		}        	 
		else if(document.form1.arrowmark[3].checked && document.form1.color[0].checked) { 
			Option="blackArrow7";
			document.form1.arrowc.value="blackArrow7";
			document.getElementById("marker").src = "../style/webImages/LowerLeftblack-trans.png";
		}
		else if(document.form1.arrowmark[0].checked && document.form1.color[1].checked) { 
			Option="whiteArrow1";
			document.form1.arrowc.value="whiteArrow1";
			document.getElementById("marker").src = "../style/webImages/UpperLeftwhite-trans.png";
		}
		else if(document.form1.arrowmark[1].checked && document.form1.color[1].checked) { 
			Option="whiteArrow3";
			document.form1.arrowc.value="whiteArrow3";
			document.getElementById("marker").src = "../style/webImages/UpperRightwhite-trans.png";
		}
		else if(document.form1.arrowmark[2].checked && document.form1.color[1].checked) { 
			Option="whiteArrow5";
			document.form1.arrowc.value="whiteArrow5";
			document.getElementById("marker").src = "../style/webImages/LowerRightwhite-trans.png";
		}
		else if(document.form1.arrowmark[3].checked && document.form1.color[1].checked) { 
			Option="whiteArrow7";
			document.form1.arrowc.value="whiteArrow7";
			document.getElementById("marker").src = "../style/webImages/LowerLeftwhite-trans.png";
		}
		else if(document.form1.arrowmark[4].checked && document.form1.color[1].checked) { 
			Option="whiteSquareSm";
			document.form1.arrowc.value="whiteSquareSm";
			document.getElementById("marker").src = "../style/webImages/squareSmallwhite-trans.png";
		}
		else if(document.form1.arrowmark[5].checked && document.form1.color[1].checked) { 
			Option="whiteSquareMd";
			document.form1.arrowc.value="whiteSquareMd";
			document.getElementById("marker").src = "../style/webImages/squareMediumwhite-trans.png";
		}
		else if(document.form1.arrowmark[6].checked && document.form1.color[1].checked) { 
			Option="whiteSquareBg";
			document.form1.arrowc.value="whiteSquareBg";
			document.getElementById("marker").src = "../style/webImages/squareBigwhite-trans.png";
		}
		else if(document.form1.arrowmark[4].checked && document.form1.color[0].checked) { 
			Option="blackSquareSm";
			document.form1.arrowc.value="blackSquareSm";
			document.getElementById("marker").src = "../style/webImages/squareSmallblack-trans.png";
		}
		else if(document.form1.arrowmark[5].checked && document.form1.color[0].checked) { 
			Option="blackSquareMd";
			document.form1.arrowc.value="blackSquareMd";
			document.getElementById("marker").src = "../style/webImages/squareMediumblack-trans.png";
		}
		else if(document.form1.arrowmark[6].checked && document.form1.color[0].checked) { 
			Option="blackSquareBg";
			document.form1.arrowc.value="blackSquareBg";
			document.getElementById("marker").src = "../style/webImages/squareBigblack-trans.png";
		}
		else if(document.form1.arrowmark[4].checked && document.form1.color[2].checked) { 
			Option="redSquareSm";
			document.form1.arrowc.value="redSquareSm";
			document.getElementById("marker").src = "../style/webImages/squareSmallred-trans.png";
		}
		else if(document.form1.arrowmark[5].checked && document.form1.color[2].checked) { 
			Option="redSquareMd";
			document.form1.arrowc.value="redSquareMd";
			document.getElementById("marker").src = "../style/webImages/squareMediumred-trans.png";
		}
		else if(document.form1.arrowmark[6].checked && document.form1.color[2].checked) { 
			Option="redSquareBg";
			document.form1.arrowc.value="redSquareBg";             	                  	 
			document.getElementById("marker").src = "../style/webImages/squareBigred-trans.png";
		}          	
	else { 
		Option="default";
		document.form1.arrowc.value="default";
		document.getElementById("marker").src = "../style/webImages/UpperLeftred-trans.png";
	}
	
	return Option;

}

function findX(obj)
{
  var curleft = 0;
  if (obj.offsetParent) {
    while (obj.offsetParent) {
    
      curleft += obj.offsetLeft
      obj = obj.offsetParent;
    }
  }
  else if (obj.x){
    curleft += obj.x;}
  return curleft;
}

// Get Absolute Y Position of HTML Element
function findY(obj)
{
  var curtop = 0;
  if(obj.offsetParent) {
    while (obj.offsetParent) {
      curtop += obj.offsetTop
      obj = obj.offsetParent;
    }
  }
  else if (obj.y)
    curtop += obj.y;
  return curtop;
}


function openAddWin(id, user) {
	window.open(addUrl);
}

function closeWindow(){
	window.close();
}

function backAnnotation(id){
window.location = "annotation.php?id=" + id;
}


