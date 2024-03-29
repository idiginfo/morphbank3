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

  // simply echos the following contents to the web browser.
  // This helps keep the main scripts simpler to read.
  
  function mainBrowse()
  {
      global $config, $mainMenuOptions, $config;
?>
  
      <div class="mainGenericContainer" style="width: 800px; ">
        <h1 class="bold" style="text-align: left;">Browse...</h1><br /><br />
        <table width="100%" class="mainBrowseTable">
          <tr>
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByImage/'; ?>" class="browseNav">
                <img src="/style/webImages/browseImages-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Images
              </a>
            </td>  
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByName/'; ?>" class="browseNav">
                <img src="/style/webImages/browseAlpha-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Alphabetical Taxon Name
              </a>              
            </td>
          </tr>
          <tr>
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByTaxon/'; ?>" class="browseNav">
                <img src="/style/webImages/browsehiearchy-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Taxon Hierarchy
              </a>
            </td>  
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByLocation/'; ?>" class="browseNav">
                <img src="/style/webImages/browseLocality-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Locality
              </a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByView/'; ?>" class="browseNav">
                <img src="/style/webImages/browseView-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;View
              </a>
            </td>  
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByCollection/'; ?>" class="browseNav">
                <img src="/style/webImages/browseCollections8-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Collections
              </a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="<?php echo $config->domain . 'Browse/BySpecimen/'; ?>" class="browseNav">
                <img src="/style/webImages/browseSpecimen-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Specimen
              </a>
            </td>  
            <td>
              <a href="<?php echo $config->domain . 'Browse/ByPublication/'; ?>" class="browseNav">
                <img src="/style/webImages/browsePublications-trans.png" align="middle" width="80" height="80" alt="Images" />
                &nbsp;&nbsp;Publication
              </a>
            </td>
          </tr>
        </table>
      </div>
        
  
      
  <?php
  }
?>
