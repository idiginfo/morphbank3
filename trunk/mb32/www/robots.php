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
header("Content-Type: text/plain");
header("Pragma: no-cache");
header("Expires: 0");

/**
 * Variables
 */
$timestamp     = date('Y-m-d H:i:s');
$ipAddress     = $_SERVER["REMOTE_ADDR"];
$referrer      = $_SERVER["HTTP_REFERER"];
$userAgent     = $_SERVER["HTTP_USER_AGENT"];
$requestUri    = $_SERVER["REQUEST_URI"];
$queryString   = $_SERVER["QUERY_STRING"];
$isCrawler     = false;
$crawlerServer = "";
$delimiter     = "|";
$idString      = "";

$db = connect();
$sql = "select b.id, ba.id as baid, ba.ipa, ba.total_requests from Bot_Agent b 
		left join Bot_Access ba on ba.user_agent_id = b.id 
		where b.user_agent = ? and ba.ipa = ?";
$row = $db->getRow($sql, null, array($userAgent, $ipAddress), null, MDB2_FETCHMODE_ASSOC);
isMdb2Error($row, "Error selecting Bot_Agent information");

if (empty($row)) {
	$sql = "insert into Bot_Agent (user_agent) values (?)";
	$stmt = $db->prepare($sql);
	$id = $stmt->execute(array($userAgent));
	isMdb2Error($id, "Error inserting into Bot_Agent");
	
	$sql = "insert into Bot_Access (user_agent_id, ipa, last_request) values (?, ?, ?)";
	$stmt = $db->prepare($sql);
	$affRows = $stmt->execute(array($id, $ipAddress, $db->mdbNow()));
	isMdb2Error($affRows, "Error inserting into Bot_Access");
} else {
	$sql = "update Bot_Access set total_requests=total_requests+1, last_request = '".$db->mdbNow()."' where id = " . $row['baid'];
	$affRows = $db->query($sql);
	isMdb2Error($affRows, "Error updating Bot_Access information");
}


function checkCrawlerUA () {
	GLOBAL $userAgent;
	GLOBAL $crawlerServer;
	$crawlerServer = "";
	$crawlers = array("Googlebot","Mediapartners","Slurp","MSNbot","Ask","Teoma");
	foreach ($crawlers as $crawler) {
		if (stristr($userAgent,$crawler)) {
			if (stristr($crawler,"Googlebot") ||
				stristr($crawler,"Mediapartners")) {
				$crawlerServer = ".googlebot.com";
			} // Google
			if (stristr($crawler,"Slurp")) {
				$crawlerServer = ".crawl.yahoo.net";
			} // Yahoo
			if (stristr($crawler,"MSNbot")) {
				$crawlerServer = ".search.live.com";
			} // MSN/Live
			if (stristr($crawler,"Ask") || stristr($crawler,"Teoma")) {
				$crawlerServer = ".ask.com";
			} // Ask
		}
	} // foreach crawlers
	if (!empty($crawlerServer)) return TRUE;
	return FALSE;
} // end function checkCrawlerUA

function checkCrawlerIP ($idString) {
	GLOBAL $ipAddy;
	GLOBAL $crawlerIps;
	GLOBAL $delimiter;
	GLOBAL $timestamp;
	GLOBAL $userAgent;
	GLOBAL $crawlerServer;
	$isCrawler = checkCrawlerUA();
	if ($isCrawler === FALSE) return FALSE;
	if (empty($crawlerServer)) return FALSE;

	// DEBUG: $crawlerServer = ".national-net.com";
	// Use your ISPs host name for testing with a spoofed user agent name
	
	$crawlerIpsContent = @file_get_contents($crawlerIps);
	if (!empty($crawlerIpsContent)) {
		if (stristr($crawlerIpsContent, "\n$ipAddy$delimiter")) {
			return TRUE;
		}
	}
	$crawlerHost = @gethostbyaddr($ipAddy);
	if (!stristr($crawlerHost,$crawlerServer)) {
		return FALSE;
	}
	if ("$crawlerHost" == "$ipAddy") {
		return FALSE;
	}
	$ipAddyRev = @gethostbyname($crawlerHost);
	if ("$ipAddyRev" != "$ipAddy") {
		return FALSE;
	}
	$crawlerIpsContent .= "\n" .$ipAddy .$delimiter
	.$timestamp .$delimiter
	.$crawlerHost .$delimiter
	.$idString .$delimiter
	.$userAgent .$delimiter;
	$lOk = writeLocalFile ($crawlerIps, $crawlerIpsContent);
	return TRUE;
} // end function checkCrawlerIP


function getCrawlerName () {
	GLOBAL $userAgent;
	$crawlerName = "";
	if (stristr($userAgent,"Googlebot")) $crawlerName = "Googlebot";
	if (stristr($userAgent,"Googlebot-Mobile")) $crawlerName = "Googlebot-Mobile";
	if (stristr($userAgent,"Googlebot-Image")) $crawlerName = "Googlebot-Image";
	if (stristr($userAgent,"Mediapartners-Google")) $crawlerName = "Mediapartners-Google";
	if (stristr($userAgent,"Adsbot-Google")) $crawlerName = "AdsBot-Google";
	if (stristr($userAgent,"Slurp")) $crawlerName = "Slurp";
	if (stristr($userAgent,"Ask") &&
	stristr($userAgent,"Teoma")) $crawlerName = "Teoma";
	if (stristr($userAgent,"MSNbot")) $crawlerName = "msnbot";
	// if (stristr($userAgent,"somebot")) $crawlerName = "somebot";
	
	// Unknown crawler:
	if (empty($crawlerName)) $crawlerName = "*";
	return $crawlerName;
} // end function getCrawlerName

?>
User-agent: *
Disallow: /images/jpeg
Disallow: /images/tiff
Disallow: /style
Disallow: /Submit
Disallow: /Edit
Disallow: /Admin
Disallow: /Annotation
Disallow: /includes
Disallow: /Mirror
