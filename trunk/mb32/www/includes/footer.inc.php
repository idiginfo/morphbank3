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
global $config;
echo '<div id="footer"><div id="footerRibbon">&nbsp;</div>';
echo '<div style="float:left;"><a href="http://www.nsf.gov">'
.'<img src="/style/webImages/nsf-trans.png" width="50" height="50" alt="NSF" title="NSF" />'
.'</a></div>';
echo '<div style="float:right;"><a href="http://www.fsu.edu">'
.'<img src="/style/webImages/fsu-trans.png" width="50" height="50" alt="FSU" title="FSU" />'
.'</a></div>';
echo '<div class="footerContent">'
.'<a href="'.$config->domain.'About/Copyright/">Copyright Policy</a>'
.' - <a href="'.$config->domain.'About/Team">Contact</a>'
.' - <a href="'.$config->domain.'About/AboutMB/">About Morphbank</a>';
echo '<br /><a href="'.$config->domain.'About/Manual/">Online User Manual</a>'
.' - <a href="'.$config->domain.'Help/Documents/">Documents</a>'
.' - <a href="'.$config->domain.'Help/feedback/">Feedback</a>';
echo '</div></div>';

