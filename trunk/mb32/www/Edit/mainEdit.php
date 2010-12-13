<?php
  // simply echos the following contents to the web browser.
  // This helps keep the main scripts simpler to read.
  
  function mainEdit()
  {
      include('mbEdit_data.php');
      
      echo '<h1> <strong><font size =6> Edit my records </font></strong></h1><br /><br />';
      foreach ($uploadMenu as $menu) {
          echo '<div class="introNavText">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="introNav" href="' . $menu['href'] . '">' . $menu['name'] . '</a></div> ';
      }
      /*
       echo '<br /> <br /><h1> <strong><font size = 6> View and request changes to </strong></font></h1><br /><br />';
       
       foreach($supportMenu as $menu) {
       
       echo '<div class="introNavText">
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="introNav" href="'.$menu['href'].'">'.$menu['name'].'</a></div> ';
       }
       */
  }
  //For now added space but should be put in css in future
?>
