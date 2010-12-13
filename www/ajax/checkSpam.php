<?php




if (isset($_GET['id'])) {
	
	$sql = 'SELECT * FROM Spam WHERE id = '.$_GET['id'];
	
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$spamArray = mysqli_fetch_array($result);	
		
		if (strtolower($spamArray['code']) == strtolower($_GET['code'])) {
			
			$jsonString = "spamObj = { result : true }";
		} 
		else
			$jsonString = "spamObj = { result : false }";
	}
	else 
		$jsonString = "spamObj = { result : false }";
}
else 
	$jsonString = "spamObj = { result : false }";

echo $jsonString;

?>
