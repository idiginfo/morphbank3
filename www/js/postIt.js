if (!IE)
	document.captureEvents(Event.MOUSEMOVE);

var tempX = 0;
var tempY = 0;
var timerOnPostIt = false;
var timerIdPostIt = null;
var postItDelay = 800;
var effectsBool = false;

// var postItToggle = <? if (isset($_POST['postItToggle'])) echo
// $_POST['postItToggle']; else echo 'true'; ?> ;
var postItToggle = true; // TODO get the value from the page!

function togglePostIt(value) {
	postItToggle = value;
}

var postItTargetId = "postItId";
var iFrameTargetId = "iFrameId";
postItTarget = document.getElementById(postItTargetId);
iFrameTarget = document.getElementById(iFrameTargetId);

function startPostIt(event, info) {
	if (document.getElementById) {
		document.getElementById(postItTargetId).style.visibility = "hidden";
		document.getElementById(postItTargetId).style.display = "inline";

		postItTarget.innerHTML = info;

		tempX = getMouseX(event);
		tempY = getMouseY(event);

		if (postItToggle != false) {
			if (timerOnPostIt == false) {
				timerIdPostIt = setTimeout("showPostIt(tempX, tempY)",
						postItDelay);
				timerOnPostIt = true;
			}
		}

	}
}

function startPostItSpry(event, info) {
	if (document.getElementById) {
		document.getElementById(postItTargetId).style.visibility = "hidden";
		document.getElementById(postItTargetId).style.display = "inline";

		Spry.Utils.updateContent(postItTargetId, info);

		/* postItTarget.innerHTML = info; */

		tempX = getMouseX(event);
		tempY = getMouseY(event);

		effectsBool = (IE) ? false : true;

		if (postItToggle != false) {
			if (timerOnPostIt == false) {
				timerIdPostIt = setTimeout("showPostIt(tempX, tempY)",
						postItDelay);
				timerOnPostIt = true;
			}
		}

	}
}

function showPostIt(mouseX, mouseY) {
	if (document.getElementById) {

		/*
		 * postItTarget.style.visibility = "hidden";
		 * postItTarget.style.display="inline";
		 */

		var postItHeight = postItTarget.offsetHeight;
		var postItWidth = postItTarget.offsetWidth;

		postItTarget.style.left = mouseX + "px";
		postItTarget.style.top = mouseY + "px";

		iFrameTarget.style.left = mouseX + "px";
		iFrameTarget.style.top = mouseY + "px";

		var postItTop = postItTarget.offsetTop;
		var postItLeft = postItTarget.offsetLeft;

		var x, y;
		// find the height of the viewport aka height of browser window;
		// the viewable part of the page. Always the same no matter how
		// much you scroll.
		if (self.innerHeight) // all except Explorer
		{
			x = self.innerWidth;
			y = self.innerHeight;
		} else if (document.documentElement
				&& document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
		{
			x = document.documentElement.clientWidth;
			y = document.documentElement.clientHeight;
		} else if (document.body) // other Explorers
		{
			x = document.body.clientWidth;
			y = document.body.clientHeight;
		}

		var windowHeight, windowWidth;
		// find out how much has been scrolled and add it to the size of
		// the viewport. Finding the distance from the top of the page
		// "<body>" to the bottom of the browser window.
		if (self.pageYOffset) // all except Explorer
		{
			windowWidth = self.pageXOffset + x;
			windowHeight = self.pageYOffset + y;
		} else if (document.documentElement
				&& document.documentElement.scrollTop)
		// Explorer 6 Strict
		{
			windowWidth = document.documentElement.scrollLeft + x;
			windowHeight = document.documentElement.scrollTop + y;
		} else if (document.body) // all other Explorers
		{
			windowWidth = document.body.scrollLeft + x;
			windowHeight = document.body.scrollTop + y;
		}

		/*
		 * alert("postItHeight:"+postItHeight+"\nTop of Body to bottom of
		 * window:"+windowHeight+"\nPostIt top:"+postItTop+"\ntempY"+tempY);
		 * alert("postItWidth:"+postItWidth+"\nWindow
		 * Width:"+windowWidth+"\nPostIt left:"+postItLeft);
		 */

		if ((postItHeight + postItTop) > windowHeight) {
			var tempY = (windowHeight - postItHeight) - 40;

			postItTarget.style.top = tempY + "px";
			iFrameTarget.style.top = tempY + "px";
		}

		if ((postItWidth + postItLeft) > windowWidth) {
			var tempX = (mouseX - postItWidth) - 40;

			postItTarget.style.left = tempX + "px";
			iFrameTarget.style.left = tempX + "px";
		}

		/**/

		iFrameTarget.style.width = postItWidth + "px";
		iFrameTarget.style.height = postItHeight + "px";
		iFrameTarget.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';

		if (effectsBool)
			Spry.Effect.DoFade(postItTargetId, {
				duration : 500,
				from : 0,
				to : 100,
				setup : function() {
					if (browser != "Opera")
						iFrameTarget.style.display = "inline";
				}
			});
		else {
			postItTarget.style.visibility = "visible";
			postItTarget.style.display = "inline";

			if (browser != "Opera")
				iFrameTarget.style.display = "inline";
		}
	}
}

function stopPostIt() {
	clearTimeout(timerIdPostIt);
	timerIdPostIt = null;
	timerOnPostIt = false;

	hidePostIt(postItTargetId);
	hideIFrame(iFrameTargetId);
}

function stopPostItSpry() {
	clearTimeout(timerIdPostIt);
	timerIdPostIt = null;
	timerOnPostIt = false;

	if (postItToggle) {
		if (effectsBool)
			hidePostItSpry(postItTargetId);
		else {
			hidePostIt(postItTargetId);
			hideIFrame(iFrameTargetId);
		}

	}
}

function hidePostIt(menu) {
	document.getElementById(menu).style.display = "none";
}

function hidePostItSpry(menu) {
	/*
	 * document.getElementById(menu).style.display="none"; hideIFrame(
	 * iFrameTargetId );
	 */
	Spry.Effect.DoFade(postItTargetId, {
		duration : 150,
		from : 100,
		to : 0,
		finish : function() {
			if (!IE) {
				document.getElementById(menu).style.display = "none";
			}
			hideIFrame(iFrameTargetId);
		}
	});
}

function hideIFrame(menu) {
	document.getElementById(menu).style.display = "none";

}

function getMouseX(event) {
	if (IE) { // grab the x-y pos.s if browser is IE
		tempX = event.clientX
				+ (document.documentElement.scrollLeft ? document.documentElement.scrollLeft
						: document.body.scrollLeft) + 20;
	}

	else { // grab the x-y pos.s if browser is other than IE
		tempX = event.pageX + 20;
	}
	return tempX;
}

function getMouseY(event) {
	if (IE) { // grab the x-y pos.s if browser is IE
		tempY = event.clientY
				+ (document.documentElement.scrollTop ? document.documentElement.scrollTop
						: document.body.scrollTop) + 20;
	}

	else { // grab the x-y pos.s if browser is other than IE
		tempY = event.pageY + 20;
	}
	return tempY;
}
