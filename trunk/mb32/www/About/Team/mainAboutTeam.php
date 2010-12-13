<?php

$aboutTeamHref = $config->domain . 'About/Team/';
// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function mainAboutTeam()
{
	global $config;

	echo '<a name="Top"></a>';
	echo '<div class="mainGenericContainer" style="width:760px">
    <h1 align="center">'.$config->appName.' team</h1>
    <img src="/style/webImages/blueHR-trans.png" width="735" height="5" class="blueHR" alt="" />' . showListOfCategory() . showListOfTeam().
	//mainGenericContainer
    '</div>';
}

function showListOfCategory()
{
	global $link, $aboutTeamHref;
	$sql = 'SELECT category, categoryOrder FROM MBTeam GROUP BY category ORDER BY categoryOrder';
	$result = mysqli_query($link, $sql);

	$categoryOutput = '
    <table><tr>';
	while ($record = mysqli_fetch_array($result)) {
		$categoryOutput .= '<td width="25%" align="center">
                <a class="categoryLinks" href="' . $aboutTeamHref . '#' . $record['categoryOrder'] . '">' . $record['category'] . '</a>
              </td>';
	}


	$categoryOutput .= '</tr></table>';


	return $categoryOutput;
}


function showListOfTeam()
{
	global $link, $config, $aboutTeamHref;

	$picturesDirectory = $config->domain . 'About/Team/pictures/';

	$sql = "SELECT * FROM MBTeam ORDER BY categoryOrder, rand()";
	$result = mysqli_query($link, $sql);
	if (!$result) {
		return '<div class="error"><br />Error, please contact the administration group<br /><br />' . $sql . "<br /><br />" . mysql_error() . '</div>';
		exit;
	}

	$teamOutput = '';
	$category = null;
	while ($mbTeamArray = mysqli_fetch_array($result)) {
		if ($mbTeamArray['category'] != $category) {
			$category = $mbTeamArray['category'];
			$teamOutput .= '<a name="' . $mbTeamArray['categoryOrder'] . '"></a><br/><br/>
              <table width ="100%" border="0"> 
              <tr>
                <td align="left" width="95%"><h2>' . $category . '</h2></td>
                <td align="left" width="5%">
                  <a href="' . $aboutTeamHref . '#Top">
                  <img border="0" alt="top" src="/style/webImages/up-nautilus.png" title="go to top" />
                  </a>
                </td>
              </tr>
              </table>';
		}
		$teamOutput .= '<table class="peopleCategoryContainter" border="0">
      <tr>
      	<td class="peopleRow1">
      		 <div class="peopleItem" id="id-' . $mbTeamArray['id'] . '">
          <div class="peopleInfo">';

		if (strlen($mbTeamArray['image_name']) > 0)
		$teamOutput .= '<div class="peopleImage"><img src="' . $picturesDirectory . $mbTeamArray['image_name'] . '" alt="[Image of ' . $mbTeamArray['fname'] . ']" width="100px" height="117px"/></div>';

		$teamOutput .= '<h2 class="peopleData">' . $mbTeamArray['fname'] . ' ' . $mbTeamArray['lname'] . ' </h2>' . '<div class="peopleData">  <span class="peopleLabel">Office:</span><span class="peopleDataItem">' . $mbTeamArray['office'] . '</span></div>' . '  <div class="peopleData"><span class="peopleLabel">Telephone:</span><span class="peopleDataItem">' . $mbTeamArray['telephone'] . '</span></div>' . '  <div class="peopleData"><span class="peopleLabel">Web page:</span>';

		if (strlen($mbTeamArray['web']) > 0)
		$teamOutput .= '<span class="peopleData"><a href="' . $mbTeamArray['web'] . '">' . $mbTeamArray['web'] . '</a></span>';
		$teamOutput .= '             </div>' . '  <div class="peopleData"><span class="peopleLabel">Email:</span><span class="peopleData">
          <img class="privemail" src="' . $config->domain . 'includes/mail.php?id=' . $mbTeamArray['id'] . '&amp;team=yes" alt="email"/></span></div>' . '  </div>';

		if (strlen($mbTeamArray['description']) > 0)
		$teamOutput .= ' <div class="peopleDescription">' . $mbTeamArray['description'] . '</div>';
		else
		$teamOutput .= ' <div class="peopleDescription"></div>';

		$teamOutput .= '</div><!-- peopleItem -->';
		$teamOutput .= '</td></tr>
          </table>';
	}

	return $teamOutput;
}
?>
