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


function switchColor()
{


if(document.form1.arrowc[2].checked) Option = "yellow";
   else if(document.form1.arrowc[3].checked) Option="green";
     else if (document.form1.arrowc[1].checked) Option ="blue";
     else Option="default";
 
if(Option =="yellow")
     {
      document.getElementById("yellowarrow").style.visibility="visible";
      document.getElementById("greenarrow").style.visibility="hidden";
      document.getElementById("darrow").style.visibility="hidden";
      document.getElementById("bluearrow1").style.visibility="hidden";
     }
  else if(Option =="green")
       {
        document.getElementById("greenarrow").style.visibility="visible";
        document.getElementById("yellowarrow").style.visibility="hidden";
        document.getElementById("darrow").style.visibility="hidden";
        document.getElementById("bluearrow1").style.visibility="hidden";
       }
 else if(Option =="blue")
      {
            document.getElementById("greenarrow").style.visibility="hidden";
            document.getElementById("yellowarrow").style.visibility="hidden";
            document.getElementById("darrow").style.visibility="hidden";
            document.getElementById("bluearrow1").style.visibility="visible"; 
       }
   else
        {   
            document.getElementById("greenarrow").style.visibility="hidden";
            document.getElementById("yellowarrow").style.visibility="hidden";
            document.getElementById("bluearrow1").style.visibility="hidden";
            document.getElementById("darrow").style.visibility="visible";
         }

}


function IMG1_onclick(event,obj, width, height) {
      document.getElementById("yellowarrow").style.visibility="hidden";
      document.getElementById("greenarrow").style.visibility="hidden";
      document.getElementById("bluearrow1").style.visibility="hidden";
      document.getElementById("darrow").style.visibility="hidden";

     var Option; 

     if(document.form1.arrowc[1].checked) Option = "blue";
       else if(document.form1.arrowc[2].checked) Option="yellow";
         else if(document.form1.arrowc[3].checked) Option="green";
           else Option="default";
 
     if(Option =="yellow")
         {
          document.getElementById("yellowarrow").style.visibility="visible";
         }
       else if(Option =="green")
           {
            document.getElementById("greenarrow").style.visibility="visible";
           }
         else if(Option =="blue")
          {
             document.getElementById("bluearrow1").style.visibility="visible";
          }
        else
            {  
               document.getElementById("darrow").style.visibility="visible";
            }
     var curx = mousePosX(event) - realLeft(obj);
     var cury = mousePosY(event) - realTop(obj);



if(curx < ((width/2)+1)  && cury < ((height/2)+1))
           {
             document.getElementById("darrow").src        = "../style/webImages/UpperLeft-trans.png";
             document.getElementById("darrow").style.top  = mousePosY(event) + "px";
             document.getElementById("darrow").style.left = mousePosX(event)  + "px";
             document.getElementById("bluearrow1").src      = "../style/webImages/UpperLeftBlue-trans.png";
             document.getElementById("bluearrow1").style.top = mousePosY(event) + "px";
             document.getElementById("bluearrow1").style.left= mousePosX(event) + "px";
             document.getElementById("yellowarrow").src    = "../style/webImages/UpperLeftYellow-trans.png";
             document.getElementById("yellowarrow").style.top= mousePosY(event) + "px";
             document.getElementById("yellowarrow").style.left= mousePosX(event)  + "px";
             document.getElementById("greenarrow").src = "../style/webImages/UpperLeftGreen-trans.png";
             document.getElementById("greenarrow").style.top= mousePosY(event) + "px";
             document.getElementById("greenarrow").style.left= mousePosX(event)  + "px";
            
          }
      else if(curx < ((width/2)+1) && cury >= (height/2)) 
           {
             document.getElementById("darrow").src = "../style/webImages/LowerLeft-trans.png";
             document.getElementById("darrow").style.top= (mousePosY(event) - 60) + "px";
             document.getElementById("darrow").style.left=mousePosX(event) + "px";
             document.getElementById("bluearrow1").src = "../style/webImages/LowerLeftBlue-trans.png";
             document.getElementById("bluearrow1").style.top= (mousePosY(event) - 60) + "px";
             document.getElementById("bluearrow1").style.left=mousePosX(event) + "px";
             document.getElementById("yellowarrow").src = "../style/webImages/LowerLeftYellow-trans.png";
             document.getElementById("yellowarrow").style.top= (mousePosY(event) - 60) + "px";
             document.getElementById("yellowarrow").style.left=mousePosX(event) + "px";
             document.getElementById("greenarrow").src = "../style/webImages/LowerLeftGreen-trans.png";
             document.getElementById("greenarrow").style.top= (mousePosY(event) - 60) + "px";
             document.getElementById("greenarrow").style.left=mousePosX(event) + "px";
           }
     else if(curx >= (width/2) && cury < ((height/2)+1))
           {
             document.getElementById("darrow").src = "../style/webImages/UpperRight-trans.png";
             document.getElementById("darrow").style.top= mousePosY(event) + "px";
             document.getElementById("darrow").style.left= (mousePosX(event) - 60) + "px";
             document.getElementById("bluearrow1").src = "../style/webImages/UpperRightBlue-trans.png";
             document.getElementById("bluearrow1").style.top= mousePosY(event) + "px";
             document.getElementById("bluearrow1").style.left= (mousePosX(event) - 60) + "px";
             document.getElementById("yellowarrow").src = "../style/webImages/UpperRightYellow-trans.png";
             document.getElementById("yellowarrow").style.top= mousePosY(event) + "px";
             document.getElementById("yellowarrow").style.left= (mousePosX(event) - 60) + "px";
             document.getElementById("greenarrow").src = "../style/webImages/UpperRightGreen-trans.png";
             document.getElementById("greenarrow").style.top= mousePosY(event) + "px";
             document.getElementById("greenarrow").style.left= (mousePosX(event) - 60) + "px";
           }
     else 
           {
             document.getElementById("darrow").src = "../style/webImages/LowerRight-trans.png";
             document.getElementById("darrow").style.top=(mousePosY(event) -  60) + "px";
             document.getElementById("darrow").style.left=(mousePosX(event) - 60) + "px";
             document.getElementById("bluearrow1").src = "../style/webImages/LowerRightBlue-trans.png";
             document.getElementById("bluearrow1").style.top=(mousePosY(event) -  60) + "px";
             document.getElementById("bluearrow1").style.left=(mousePosX(event) - 60) + "px";
             document.getElementById("yellowarrow").src = "../style/webImages/LowerRightYellow-trans.png";
             document.getElementById("yellowarrow").style.top=(mousePosY(event) -  60) + "px";
             document.getElementById("yellowarrow").style.left=(mousePosX(event) - 60) + "px";
             document.getElementById("greenarrow").src = "../style/webImages/LowerRightGreen-trans.png";
             document.getElementById("greenarrow").style.top=(mousePosY(event) -  60) + "px";
             document.getElementById("greenarrow").style.left=(mousePosX(event) - 60) + "px";
            }

document.getElementById("xLocation").value= Math.round( (( (curx + 5) / document.getElementById("animage").width) * 100) *100)/100;

document.getElementById("yLocation").value= Math.round( (( (cury + 5) / document.getElementById("animage").height) *100) *100)/100;

//alert(document.getElementById("xLocation").value + " " + document.getElementById("yLocation").value);

//alert(realLeft(IMG1) + ", " + realTop(IMG1));

}



function realLeft(obj) {

	xOffset = eval(obj).offsetLeft;

	tempOb = eval(obj).offsetParent;

  	while (tempOb != null) {

  		xOffset += tempOb.offsetLeft;

  		tempOb = tempOb.offsetParent;

  	}

	return xOffset;

}



function realTop(obj) {

	yOffset = eval(obj).offsetTop;

	tempOb = eval(obj).offsetParent;

	while (tempOb != null) {

  		yOffset += tempOb.offsetTop;

  		tempOb = tempOb.offsetParent;

  	}

	return yOffset;

}



function removearrow(){

document.getElementById("darrow").style.visibility="hidden";
document.getElementbyId("bluearrow1").style.visibility="hidden";
document.getElementById("yellowarrow").style.visibility="hidden";
document.getElementById("greenarrow").style.visibility="hidden";
document.getElementById("xLocation").value="";

document.getElementById("yLocation").value="";

}



function openAddWin(id, user)

{
var addUrl = "http://morphbank.scs.fsu.edu/~gaitros/Morphbank2.2/Annotation/ckhadd.php?id=" + id + "&userid=" + user;

window.open(addUrl);


}

function closeWindow(){
	window.close();
}

function backAnnotation(id){
window.location = "annotation.php?id=" + id;
}


