<?php

$href = "../../About/HowToContribute/index.php";
header('Location: '.$href);



include_once('head.inc.php');

include_once('mainAboutHowToCont.php');

// The beginnig of HTML
$title =  'How to contribute';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainAboutHowToContribute();

// Finish with end of HTML
finishHtml();
?>
