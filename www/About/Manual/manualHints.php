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
    <h1>User Manual Hints</h1>
    <div id=footerRibbon></div>
                            <!--<table class="manualContainer" cellspacing="0" width="100%">
                            <tr>-->

    <ul>
        <li>Use the table of Contents to find the topic or area of interest.</li>
        <li>Use Browser keyboard shortcuts to find a keyword (i.e. Internet Explorer,
            Mozilla Firefox use CTRL-F) and type in keyword. These vary according
            to the machine and browser being used.</li>
        <li>Use Links at the bottom of pages to jump to Contents or Next manual page.</li>
        <li>If a URL link is to website outside Morphbank, use "right click" (PC) or "control-click" (Mac) to open a new tab to the URL.</li >
        <li>Links to pages within this manual take the user directly to the desired page. Use "right click" (PC) or "control-click" (Mac) to open a tab to another manual page.</li>
        <li>Use the Contents button to choose another page in the Manual.
        </li>
        <li>Click the <img src="ManualImages/feedback.png" alt="feedback link" align="middle" />
            link at the <strong>top</strong> or <strong>bottom</strong> of any page in the My Manager interface of Morphbank.
            It opens the following window providing an easy and timely way for users to share their
            observations as they experience Morphbank. <!--It is also accessible at the <strong>bottom</strong> of every User Manual page.-->
            <p><img src="ManualImages/feedback_form.png" alt="screen shot of feedback form"></p>
        </li>
    </ul>

    <br />
    <div id=footerRibbon></div>
    <table align="right">
        <td><a href="<?php echo $config->domain; ?>About/Manual/systemRequire.php" class="button smallButton"><div>Next</div></a></td>
        <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
    </table>
</div>

<?php
//Finish with end of HTML	
finishHtml();
?>	
