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

