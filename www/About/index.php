<?php

include_once('../data/mbMenu_data.php');

$introductionHref = $mainMenuOptions[1]['href'];

header('Location: '.$introductionHref);
?>
