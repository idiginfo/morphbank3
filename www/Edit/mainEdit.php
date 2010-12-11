#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
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
