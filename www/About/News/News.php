<?php
function mainnewsText()
  {
       include('../../data/mbMenu_data.php');
      global $newsText;
      global $config;
      echo '<div id="main">';
      echo '<div class="mainGenericContainer">
      <h1 align="center"></h1>' . $newsText . '
      </div>';
      echo '</div>';
  }
  
  $newsText = showNews();
  
  function createNewsSql($howMany = null)
  {
      $sql = 'SELECT News.*, DATE_FORMAT(BaseObject.dateCreated, "%m-%d-%y") as date, ' . 'User.name as userIdName ' . 'From News ' . 'LEFT JOIN BaseObject ON News.id = BaseObject.id ' . 'LEFT JOIN User ON BaseObject.userId = User.id  ' . 'ORDER BY BaseObject.dateCreated DESC ';
      if ($howMany != null)
          $sql .= ' LIMIT 0, ' . $howMany;
      return $sql;
  }
  //this showNews function varies from the Introduction page show news function by the amount of text shown for each news item
  function showNews()
  {
      global $link, $config;
      
      $sql = createNewsSql();
      $result = mysqli_query($link, $sql);
      if (!$result) {
          return $sql . "\n" . mysqli_error($link);
      }
      if (!mysqli_num_rows($result))
          return 'There is no posted news';
      
      $newsOutput = "";
      while ($newsArray = mysqli_fetch_array($result)) {
          $newsOutput .= '<table><tr><td valign="top"><h2>' . $newsArray['title'] . ' </h2><a href="' . $config->domain . 'Show/?id=' . $newsArray['id'] . '"><img src="../../style/webImages/infoIcon.png" /></a> <br />(Posted on: ' . $newsArray['date'] . ')  </i><br /><br />';
          $newsWordsArray = explode(" ", $newsArray['body']);
          for ($i = 0; $i <= 1000; $i++)
              $newsOutput .= $newsWordsArray[$i] . " ";
          $newsOutput .= '<img src="../../style/webImages/spacer.jpg" />' . '</td>';
          
          if ($newsArray['image'] != '') {
              $newsOutput .= '<td><img src="/images/newsImages/' . $newsArray['image'] . '" width="200px" alt="news" />';
          }
          $newsOutput .= '</td></div></tr><div id="footerRibbon"></table>';
      }
      
      mysqli_free_result($result);
      
      return $newsOutput;
  }
?>
