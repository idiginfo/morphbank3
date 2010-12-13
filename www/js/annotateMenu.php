<?php
echo '
<script language="JavaScript" type="text/javascript">
	
	function startTimeAnn() {
		if (timerOnAnn == false) {
				timerIDAnn=setTimeout( "hideAllMenusAnn()" , timecount);
			timerOnAnn = true;
		}

	}	
	
	function stopTimeAnn() {
			if (timerOnAnn) {
					clearTimeout(timerIDAnn);
				timerIDAnn = null;
				timerOnAnn = false;
			}
	}
	
	function hideAllMenusAnn() {
		hideMenu("collection");
		hideMenu("mail");
		hideMenu("view");
		hideMenu("image");
		hideMenu("annotation");
	}
</script>';

?>
