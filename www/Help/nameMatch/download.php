<?php
session_start();
/**
 * Output taxon terms as csv
 */
if ($_REQUEST['action'] == 'matched') {
	$contents = $_SESSION['cvs_matched_terms'];
	$fileName = "matched_terms.csv";
} else {
	$contents = $_SESSION['cvs_unmatched_terms'];
	$fileName = "unmatched_terms.csv";
}

header("Content-type:application/csv");
header("Content-disposition:inline;filename=".$fileName);

$out = fopen('php://output', 'w');
foreach ($contents as $data) {
	fputcsv($out, $data, ',', '"');
}
fclose($out);
