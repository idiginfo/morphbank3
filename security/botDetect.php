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

$config->isBot = checkCrawler($_SERVER["HTTP_USER_AGENT"]);

/**
 * Checks if crawler using host name and ip
 */
function checkCrawler () {
    $ipAddress = getIP();
    
	$crawlerServer = crawlerDetect($userAgent);
	if ($isCrawler === false) return false;
	
	$crawlerHost = @gethostbyaddr($ipAddress);
	if (!stristr($crawlerHost,$crawlerServer)) {
		return false;
	}
	
	if ("$crawlerHost" == "$ipAddress") {
		return false;
	}
	
	$reverseIp = @gethostbyname($crawlerHost);
	if ("$reverseIp" != "$ipAddress") {
		return false;
	}
	
	return true;
}

/**
 * Array of known crawlers
 * @param $userAgent
 */
function crawlerDetect($userAgent) {
  // Contains crawler and server
  $crawlers = array(
  	'Google' => '.googlebot.com',
    'Mediapartners' => '.googlebot.com',
    'msnbot' => '.search.live.com',
    'Ask' => '.ask.com',
    'Teoma' => '.ask.com',
    'Slurp' => '.crawl.yahoo.net'
  );

  foreach ($crawlers as $agent => $server) {
    if (stristr($userAgent, $agent)) {
      return($server);
    }
  }

  return false;
}

