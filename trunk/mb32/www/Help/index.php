<?php

include_once('../data/mbMenu_data.php');
$introductionHref = $mainMenuOptions[0]['href'];
//echo $introductionHref;
header('Location: '.$introductionHref);
echo '<a href="feedback">feedback</a>';
?>
