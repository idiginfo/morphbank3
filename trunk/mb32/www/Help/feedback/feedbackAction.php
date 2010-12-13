<?php




if (isset($_POST['spamId'])) {
	$sql = 'SELECT * FROM Spam WHERE id = '.$_POST['spamId'];
	
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$spamArray = mysqli_fetch_array($result);	
		
		if (strtolower($spamArray['code']) == strtolower($_POST['spamCode'])) {
			if (mail('mbadmin@scs.fsu.edu', 'AUTOMATED MORPHBANK FEEDBACK EMAIL::  '.$_POST['subject'] , $_POST['message'], 'From: '.$_POST['from'].''))
				header('Location: index.php?id=1');
				
			else
				header('Location: index.php?id=2'); //mail send error
		} 
		else
			header('Location: index.php?id=3');	// wrong spam code
	}
	else 
		header('Location: index.php?id=4');	// Query messed up
}
else 
	header('Location: index.php?id=5');	// No $_POST['spamId'] set


?>
