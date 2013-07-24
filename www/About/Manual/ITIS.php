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
include_once('head.inc.php');
$title = 'About - Manual';
initHtml($title, NULL, NULL);
echoHead(false, $title);
?>


<div class="mainGenericContainer" width="100%">
    <!--change the header below -->
    <h1>Integrated Taxonomic Information System (ITIS)</h1>
    <div id=footerRibbon></div>
                            <!--<table class="manualContainer" cellspacing="0" width="100%">
                            <tr>-->
    <td width="100%">

        <p>The <a href="http://www.itis.gov/" target="_blank">Integrated Taxonomic Information System (ITIS)</a>
            database maintained by the <a href="http://www.usda.gov/wps/portal/usdahome" target="_blank">United
                States Department of Agriculture (USDA)</a>. ITIS was selected as the taxonomic name
            server for Morphbank in 2004 because it represented the most complete comprehensive
            taxonomic name service available at the time. Also, the entire database could be
            downloaded locally making access to the data quick and efficient.
            ITIS is a consistent service. It has a high level of stability and a rigid review system.
            Since ITIS is maintained by the USDA, the probability that the service will be persistent
            for several years is high. Taxonomic names are entered into the system and panel of
            experts periodically review the names for quality assurance.
        </p>
        <div class="specialtext2">
            <img align="center" src="ManualImages/ITIS%20copy.jpg">
        </div>
        The Morphbank development team recognized early in the development of the
        system, the need for a Taxonomic Name Server that would supply the scientific
        names needed in determination of species. However, there were none available
        that contained all of the recognized names. ITIS was chosen because it
        <ol>
            <li>contained the most complete set of names at the time,
            </li>
            <li>has a formal method for adding new names and
            </li>
            <li>because the system is supported by the USDA ensuring the longevity of the system.
            </li>
        </ol>
        <p>Because the addition of new names to the ITIS system does take some time, the Morphbank team
            established a method to <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" target="_blank">add names</a> to Morphbank.
            This allows scientists use the taxonomic name of their choice to identify a specimen in Morphbank.
            Names added to Morphbank have 9-digit Morphbank <em>taxonomic serial numbers</em> (mtsn) beginning with 999 to
            differentiate them from ITIS tsns.
        </p>
        <div class="specialtext3">
            Currently, no taxonomic name updates are sent to ITIS and the ITIS taxon names in Morphbank are from the
            initial installation. There are plans to completely revise the workflow regarding taxonomy.
            Morphbank envisions a system for users to explicitly provide a link to a taxon name in an existing
            taxonomy (ITIS, uBio, USDA Plants, IPNI ...) to <em>identify</em> a given specimen. The taxon name
            and its relevant data may display with the specimen data and image uploaded to Morphbank. In essence,
            Morphbank intends to pull names from existing taxonomic name servers instead of uploading taxon names and
            taxonomic hierarchies.
        </div>
        <div id=footerRibbon></div>
        <table align="right">
            <td><a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" class="button smallButton"><div>Next</div></a></td>
            <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
        </table>
</div>

<?php
//Finish with end of HTML	
finishHtml();
?>	
