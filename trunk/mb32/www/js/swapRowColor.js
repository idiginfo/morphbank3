// JavaScript Document

var newColorHex = "c8f3d3";
var blueHex = "e5e5f5";
var whiteHex = "ffffff";

var newColor = HexToDec(newColorHex);	
var blueColor = HexToDec(blueHex);
var whiteColor = HexToDec(whiteHex);

function swapColor(id) {
	/* "id" is the id of the div to change color.  "checkBoxName" is the name of the checkbox */
	
	//var idName = "changeMe"+id;
	var idName = "row"+id;
	var checkBoxId = "box-"+id;
	
	var chkBox = document.getElementById(checkBoxId);
	var divToChange = document.getElementById(idName).style;
	
	var originalColor;
	
	if (id%2 == 0) {
		originalColor = blueColor;
		originalColorHex = "e5e5f5";
	}
	else {
		originalColor = whiteColor;
		originalColorHex = "ffffff";
	}
	
	//alert(idName+" "+checkBoxId+" "+newColor+" "+originalColor+" "+divToChange.backgroundColor);
	if (divToChange.backgroundColor==originalColor || divToChange.backgroundColor=="" || divToChange.backgroundColor=="#"+originalColorHex) {
		chkBox.checked=true;
		divToChange.backgroundColor=newColor;					
	}
	else {
		chkBox.checked=false;
		divToChange.backgroundColor=originalColor;					
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



function checkAllImages() {
	numPerPage = document.resultForm.elements.length;
	
	var divToChange;
	var count = 1;
	for (var x = 0; x < numPerPage; x++) {
		if (document.resultForm.elements[x].type == "checkbox") {
			document.resultForm.elements[x].checked = true;
			divToChange = "row"+count;
			document.getElementById(divToChange).style.backgroundColor=newColor;
			count++;
		}
	}
}

function unCheckAllImages() {
	numPerPage = document.resultForm.elements.length;
	
	var colorArray = new Array();
	colorArray[0] = blueColor;
	colorArray[1] = whiteColor;
	
	var divToChange;
	var count = 1;
	var index;
	for (var x = 0; x < numPerPage; x++) {
		if (document.resultForm.elements[x].type == "checkbox") {
			document.resultForm.elements[x].checked = false;
			index = count%2;
			divToChange = "row"+count;
			document.getElementById(divToChange).style.backgroundColor=colorArray[index];
			count++;
		}				
	}
}
