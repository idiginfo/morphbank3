<?php


include_once('head.inc.php');

// The beginnig of HTML
$title =  'Can\'t Annotate';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
echo '<div class="mainGenericContainer" style="width:800px;">
<h2 class="red">Privledge TSN not high enough to annotate this object</h2><br /><br />
<a href="javascript:history.go(-2);" class="button smallButton"><div>Back</div></a>	

</div>';

// Finish with end of HTML
finishHtml();
?>
