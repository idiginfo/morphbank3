<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

  global $config;
  function mainIndexPage()
  {
      
      include("mbMenu_data.php");
      global $link, $config;
      
      
      //returns the number of rows in the Image table which equals to the number of images in application  
      $sql = 'SELECT COUNT(*) as total FROM Image';
      $result = mysqli_query($link, $sql);
      if (!$result) {
          return $sql . "\n" . mysqli_error($link);
      }
      $num_images = mysqli_fetch_array($result);
      
      //html elements are below for all containers; modify here to change page content
      echo '
  <div class="mainNewsContainer">
     <div id="left">
       <h3>Featured from <a href="' . $config->domain . 'MyManager/"> ' . $num_images['total'] . ' images</a></h3>
          <br /><br />';
      
      //swf slideshow found in includes/swf; xml found in data/xml
      echo '        
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" 
  WIDTH="550" 
  HEIGHT="400" 
  id="slideshow" 
  ALIGN="">
<PARAM NAME=movie VALUE="' . $config->domain . 'includes/swf/slideshow.swf?xml_source=' . $config->domain . 'data/xml/sample.xml&license=KUBZ7-F2G3B6FWELRTO9DN6IKN49JK">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#000000>
<PARAM NAME="wmode" VALUE="transparent">

<EMBED src="' . $config->domain . 'includes/swf/slideshow.swf?xml_source=' . $config->domain . 'data/xml/sample.xml&license=KUBZ7-F2G3B6FWELRTO9DN6IKN49JK" 
  quality=high 
  bgcolor=#000000  
  WIDTH="550" 
  HEIGHT="400" 
  wmode="transparent"
  NAME="slideshow" 
  ALIGN="" 
  TYPE="application/x-shockwave-flash" 
  PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
</EMBED>
</OBJECT>
';
      echo '
          <br />
          <br />
          <b>click images below to <a href="' . $config->domain . 'MyManager/">browse</a> new uploads and collections</b>
           <center>
       <table>
         <tr>
              <td><a href="/?id=464608" title="Anglerfish"><img src="http://morphbank.net/?id=114691&imgType=thumb" height="80"/></a>
          <br />Anglerfish</td>
              <td><a href="/?id=464705" title="Chromista"><img src="http://morphbank.net/?id=227161&imgType=jpg" height="80" /></a>
          <br />Chromista</td>
              <td><a href="/?id=464706" title="Manduca blackburni"><img src="http://morphbank.net/?id=234147&imgType=thumb" height="80" /></a>
          <br /><i>M. blackburni</i></td>
              <td><a href="/?id=237144" title="Aristolochia schultzeana"><img src="http://morphbank.net/?id=401844&imgType=thumb" height="80"/></a>
          <br />Aristolochia</td>
              <td><a href="/?id=77655" title="Cynipidae"><img src="http://morphbank.net/?id=70268&imgType=thumb" height="80"/></a>
          <br />Cynipidae</td>
        </tr>
      </table>
          </center>

     </div>
    <div id="right">
        <h3>News and Updates</h3>
        <img src="/style/webImages/blueHR-trans.png" class="blueHR"  width="200" alt="" />';
    if ($config->disableSite == 1) {
      echo '<p style="font-weight:bold;color:red">Morphbank is currently disabled for updates and submissions.</p>';
    }
    echo showNews($link);
    echo '(<a href="' . $config->domain . 'About/News">see all past news</a>)
      </div>
      </div>';
  }
  
  //create news function; no need to touch below this line for changing page content
  function createNewsSql($howMany = null)
  {
      $sql = 'SELECT News.*, DATE_FORMAT(BaseObject.dateCreated, "%m-%d-%y") as date, ' . 'User.name as userIdName ' . 'From News ' . 'LEFT JOIN BaseObject ON News.id = BaseObject.id ' . 'LEFT JOIN User ON BaseObject.userId = User.id  ' . 'ORDER BY BaseObject.dateCreated DESC ' . 'LIMIT 4';
      if ($howMany != null)
          $sql .= ' LIMIT 0, ' . $howMany;
      return $sql;
  }
  
  function showNews($link)
  {
      global $config;
      
      $sql = createNewsSql();
      $result = mysqli_query($link, $sql);
      if (!$result) {
          return $sql . "\n" . mysqli_error($link);
      }
      if (!mysqli_num_rows($result))
          return 'There is no posted news';
      
      //$newsOutput = '<p>';
      $newsOutput = "";
      while ($newsArray = mysqli_fetch_array($result)) {
          $newsOutput .= '<table><tr><td><a href="' . $config->domain . 'Show/?id=' . $newsArray['id'] . '"><b>' . $newsArray['title'] . '</b></a><br />';
          
          $newsWordsArray = explode(" ", $newsArray['body']);
          for ($i = 0; $i <= 50; $i++)
              $newsOutput .= $newsWordsArray[$i] . " ";
          $newsOutput .= '...' . '</td>';
          
          if ($newsArray['image'] != '') {
              $alt = !empty($newsArray['imageText']) ? $newsArray['imageText'] : 'news';
              $newsOutput .= '<td><img src="' . $newsArray['image'] . '" border="0" width="100px" alt="'.$alt.'" />';
          }
          $newsOutput .= '<br /><i>(Posted:' . $newsArray['date'] . ')</i></td></tr></table><hr />';

      }
      
      mysqli_free_result($result);
      return $newsOutput;
  }
?>
