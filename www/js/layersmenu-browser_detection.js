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
<script language="JavaScript" type="text/javascript">
<!--
// PHP Layers Menu 3.0.2 (C) 2001-2004 Marco Pratesi - http://www.marcopratesi.it/

DOM = (document.getElementById) ? 1 : 0;
NS4 = (document.layers) ? 1 : 0;
// We need to explicitly detect Konqueror
// because Konqueror 3 sets IE = 1 ... AAAAAAAAAARGHHH!!!
Konqueror = (navigator.userAgent.indexOf("Konqueror") > -1) ? 1 : 0;
// We need to detect Konqueror 2.1 and 2.2 as they do not handle the window.onresize event
Konqueror21 = (navigator.userAgent.indexOf("Konqueror 2.1") > -1 || navigator.userAgent.indexOf("Konqueror/2.1") > -1) ? 1 : 0;
Konqueror22 = (navigator.userAgent.indexOf("Konqueror 2.2") > -1 || navigator.userAgent.indexOf("Konqueror/2.2") > -1) ? 1 : 0;
Konqueror2 = Konqueror21 || Konqueror22;
Opera = (navigator.userAgent.indexOf("Opera") > -1) ? 1 : 0;
Opera5 = (navigator.userAgent.indexOf("Opera 5") > -1 || navigator.userAgent.indexOf("Opera/5") > -1) ? 1 : 0;
Opera6 = (navigator.userAgent.indexOf("Opera 6") > -1 || navigator.userAgent.indexOf("Opera/6") > -1) ? 1 : 0;
Opera56 = Opera5 || Opera6;
IE = (document.all) ? 1 : 0;
IE4 = IE && !DOM;

// -->
</script>
