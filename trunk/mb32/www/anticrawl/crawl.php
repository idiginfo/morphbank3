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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

// How many pages allowed to hit in that amount of time
$maxHits = 10;

// How long in seconds is an acceptable amount of time
$maxTime = 2;

// How many pages are they allowed to hit before a human challenge is needed?
$maxQuota = 10;

$path = APPLICATION_PATH . "/www/anticrawl";

$db = connect();

if ($_SERVER["QUERY_STRING"] == "anticrawl") {
	if (function_exists("imagecreate")) {
		die("<b>ENABLED:</b> Your site is being protected from scrapers.");
	}
	else {
		die("<b>DISABLED:</b> Site not protected!  Please tell your web host GDlib is not enabled on your server.");
	}
}

if (basename($script) == "crawl.php") {
	echo "Do not run AntiCrawl directly...<br>\n";
	echo "Just place the .htaccess file in the same folder as the files you want to protect from content harvesters.";
	die();
}

if (function_exists("imagecreate")) {
		
	$uri = parse_url($_SERVER["REQUEST_URI"]);
	$script = $uri["path"];

	$rawIP = Network::getShortIP();
	$ip = Network::getLongIP();

	// Reset quota if a good challenge has been given
	if (array_key_exists("phrase", $_POST)) {
		$phrase = $_POST["phrase"];
		$redirect = $_POST["nextPage"];
		$lastIP = $_COOKIE["lastIP"];
		
		$sql = "update leech_quotas set total = 0, challenge = '' where ip > 0 and challenge != '' and (ip = ? or ip = ?) and challenge = ? limit 2";
		$data = array($ip, $lastIP, $phrase);
		$stmt = $db->prepare($sql);
		$affRows = $stmt->execute($data);
		isMdb2Error($affRows, "Update leech quotas");

		Output::redirect($redirect);
	}

	if ($_SERVER["QUERY_STRING"] == "anticrawl_ask") {
		Output::challenge();
	}

	if (!function_exists("ip2long")) {
		function ip2long($ip) {
			$ip = explode(".",$ip);

			if (!is_numeric(join(NULL,$ip)) or count($ip) != 4) {
				return false;
			}
			else {
				return $ip[3] + 256 * $ip[2] + 256 * 256 * $ip[1] + 256 * 256 * 256 * $ip[0];
			}
		}
	}

	$yesterday = strtotime("-1 day");
	$now = mktime();
	$expire = $now - $maxTime;

	// A local bot on the server spidering itself
	$localBot = $_SERVER["SERVER_ADDR"] == $_SERVER["REMOTE_ADDR"];

	if ($ip > 0 && !array_key_exists("bot", $_SERVER) && !$localBot) {
		$res = $db->exec("delete from leech_list where timestamp < '$expire'");
		isMdb2Error($res, "deleting from leech list");
		
		$res = $db->exec("delete from leech_quotas where lastFlush < '$yesterday'");
		isMdb2Error($res, "deleting from leech quotas");
		
		$sql = "select count(*) as count from leech_list where ip = ?";
		$hits = $db->getOne($sql, null, array($ip));
		isMdb2Error($count, "select count from leech list");
		
		$sql = "select * from leech_quotas where ip = ? limit 1";
		$row = $db->getRow($sql, null, array($ip), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($row, "selecting from leech quotas");
		
		$hostname = $row["hostname"];
		$quota    = $row["total"];
		$proxy    = $row["proxy"];

		if (!$hostname) {
			$hostname = Network::resolveHost($rawIP);
		}

		// Check for proxy on page #5
		if ($quota == 5) {
			$proxy = (Network::checkProxy($rawIP)) ? 1 : 0;
		}

		$listBust = false;
		$quotaBust = false;

		$listBust = $hits > $maxHits;
		$quotaBust = $quota > $maxQuota;

		$isSafe = (Bot::isSafe($hostname)) ? true : false;
		$isBot = Bot::isBot($_SERVER["HTTP_USER_AGENT"]);

		// Check to see if the visitor claims to be a bot
		if (!$isSafe) {
			// Deny fake bots
			if ($isBot) {
				Output::block();
			}

			if ($listBust or $quotaBust) {
				Output::rewrite();
			}
			
			$sql = "insert into leech_list SET ip = ?, timestamp = ?";
			$data = array($ip, $now);
			$stmt = $db->prepare($sql);
			$affRows = $stmt->execute($data);
			isMdb2Error($affRows, "Insert into leech list");
			
			$sql = "insert ignore into leech_quotas SET ip = ?, lastFlush = ?";
			$data = array($ip, $now);
			$stmt = $db->prepare($sql);
			$affRows = $stmt->execute($data);
			isMdb2Error($affRows, "Insert into leech list");
			
			$sql = "update leech_quotas set hostname = ?, isProxy = ?, total = total + 1 WHERE ip = ? LIMIT 1";
			$data = array($hostname, $proxy, $ip);
			$stmt = $db->prepare($sql);
			$affRows = $stmt->execute($data);
			isMdb2Error($affRows, "updating leech quotas");
		}
	}
}

class Bot {
	function isBot($userAgent) {
		$agents = array("google", "yahoo", "slurp", "msnbot", "jeeves", "teoma");

		foreach ($agents as $agent) {
			if (eregi($agent, $userAgent)) {
				return true;
			}
		}

		return false;
	}

	function isSafe($hostname) {
		$safe = array(
		// MSNBot
         "msn.com", "msn.net",
		// Yahoo!
         "inktomisearch.com", "yahoo.com", "yahoo.net",
		// Google
         "google.com", "googlebot.com", "googlesyndication.com",
		// Ask Jeeves
         "ask.com", "ask.info", "directhit.com", "askj.co.jp", "teoma.com", "askjeeves.com",
		// Article Submitters
         "isnare.com", "article-marketer.com"
         );

         foreach ($safe as $safeMatch) {
         	if (eregi($safeMatch . "$", $hostname)) {
         		return true;
         	}
         }
         return false;

	}
}

class Network {

	function getShortIP() {
		return $_SERVER["REMOTE_ADDR"];
	}

	function getLongIP() {
		return ip2long(Network::getShortIP());
	}

	function checkProxy($rawIP) {

		if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER) or array_key_exists("HTTP_VIA", $_SERVER)) {
			return true;
		}

		$fp = @fsockopen($rawIP, 80, $errno, $errstr, 5);
		if ($fp) {
			return true;
		}

		return false;
	}

	function resolveHost($ip, $dns="127.0.0.1", $timeout=1000) {
		// random transaction number (for routers etc to get the reply back)
		$data = rand(0, 99);
		// trim it to 2 bytes
		$data = substr($data, 0, 2);
		// request header
		$data .= "\1\0\0\1\0\0\0\0\0\0";
		// split IP up
		$bits = explode(".", $ip);
		// error checking
		if (count($bits) != 4) return "ERROR";
		// there is probably a better way to do this bit...
		// loop through each segment
		for ($x=3; $x>=0; $x--)
		{
			// needs a byte to indicate the length of each segment of the request
			switch (strlen($bits[$x]))
			{
				case 1: // 1 byte long segment
					$data .= "\1"; break;
				case 2: // 2 byte long segment
					$data .= "\2"; break;
				case 3: // 3 byte long segment
					$data .= "\3"; break;
				default: // segment is too big, invalid IP
					return "INVALID";
			}
			// and the segment itself
			$data .= $bits[$x];
		}
		// and the final bit of the request
		$data .= "\7in-addr\4arpa\0\0\x0C\0\1";
		// create UDP socket
		$handle = @fsockopen("udp://$dns", 53);
		// send our request (and store request size so we can cheat later)
		$requestsize=@fwrite($handle, $data);

		@socket_set_timeout($handle, $timeout - $timeout%1000, $timeout%1000);
		// hope we get a reply
		$response = @fread($handle, 1000);
		@fclose($handle);
		if ($response == "")
		return $ip;
		// find the response type
		$type = @unpack("s", substr($response, $requestsize+2));
		if ($type[1] == 0x0C00)  // answer
		{
			// set up our variables
			$host="";
			$len = 0;
			// set our pointer at the beginning of the hostname
			// uses the request size from earlier rather than work it out
			$position=$requestsize+12;
			// reconstruct hostname
			do
			{
				// get segment size
				$len = unpack("c", substr($response, $position));
				// null terminated string, so length 0 = finished
				if ($len[1] == 0)
				// return the hostname, without the trailing .
				return substr($host, 0, strlen($host) -1);
				// add segment to our host
				$host .= substr($response, $position+1, $len[1]) . ".";
				// move pointer on to the next segment
				$position += $len[1] + 1;
			}
			while ($len != 0);
			// error - return the hostname we constructed (without the . on the end)
			return $ip;
		}
		return $ip;
	}

}


class Output {

	function redirect($url) {
		header("Location:" . $url);
		die();
	}

	function rewrite() {
		global $path;

		$urlPath = eregi_replace("^" . $_SERVER["DOCUMENT_ROOT"] . "/?", "", $path);
		$docURL = "http://" . $_SERVER["HTTP_HOST"];

		$template = file_get_contents($path . "/anticrawl.html");

		// Figure out variables
		$image = '<img src="' . $docURL . "/" . $urlPath . '/crawl.php?anticrawl_ask">';
		$page = $docURL . $_SERVER["REQUEST_URI"];
		$crawlScript = $docURL . "/" . $urlPath . "/crawl.php";

		// Apply variables to the template
		$template = str_replace('{image}', $image, $template);
		$template = str_replace('{page}', $page, $template);
		$template = str_replace('{crawlScript}', $crawlScript, $template);

		setcookie("lastIP", Network::getShortIP(), strtotime("+1 year"), "/");

		echo $template;
		die();
	}

	function block() {

		header("HTTP/1.0 403 Forbidden");

		$message = array();
		$message[] = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">';
		$message[] = '<HTML><HEAD>';
		$message[] = '<TITLE>403 Forbidden</TITLE>';
		$message[] = '</HEAD><BODY>';
		$message[] = '<H1>Forbidden</H1>';
		$message[] = 'You don\'t have permission to access ' . $_SERVER["REQUEST_URI"];
		$message[] = 'on this server.<P>';
		$message[] = '</BODY></HTML>';

		return implode("\n", $message);

		die();

	}

	function challenge() {
		global $db;
		
		$c = new GridChallenge();

		// Write challenge text to database
		$string = $c->toString();
		$longIP = Network::getLongIP();
		
		$sql = "update leech_quotas set challenge = ? where ip = ? limit 1";
		$data = array($string, $longIP);
		$stmt = $db->prepare($sql);
		$affRows = $stmt->execute($data);
		isMdb2Error($affRows, "Update leech quotas");

		$c->output();
		die();
	}

}

class Challenge {

	var $image;

	var $width;
	var $height;

	var $string;

	function Challenge() {

		$set = array_merge(
		range("A", "Z"),
		range("a", "z"),
		range("1", "9")
		);

		$size = 6;

		$string = "";
		for ($i=0;$i<$size;$i++) {
			$string .= $set[array_rand($set)];
		}

		$this->string = $string;

		$files = Folder::toArray();
		$files = array_filter($files, array("Folder", "fontFilter"));
		$this->fontList = $files;

		$this->font = $files[array_rand($files)];

		$this->create($string);

		// Normal dots
		$this->dots();

		// Small dots
		$this->dots(50, 2, 5, 128);

		$this->arcs();
		$this->text($string);
	}

	function toString() {
		return $this->string;
	}

	function output() {
		header("Content-type:image/png");
		imagepng($this->image);
	}

	function dots($number=10, $minSize=8, $maxSize=24, $colorLimit=255) {

		for ($i=0;$i<$number;$i++) {

			$color = $this->randomColor(128, $colorLimit);

			// Get a random X and Y coordinate
			$x = rand(0, $this->width);
			$y = rand(0, $this->height);

			$size = rand($minSize, $maxSize);

			// resource image, int cx, int cy, int w, int h, int color
			imagefilledellipse($this->image, $x, $y, $size, $size, $color);
		}
	}

	function arcs($number=10) {

		$minLength = 0;
		$maxLength = max($this->width, $this->height);

		for ($i=0;$i<$number;$i++) {

			$x = rand(0, $this->width);
			$y = rand(0, $this->height);

			$width = rand($minLength, $maxLength);
			$height = rand($minLength, $maxLength);

			$start = rand(0, 360);
			$end = rand(0, 360);

			$color = $this->randomColor();
			imagearc($this->image, $x, $y, $width, $height, $start, $end, $color);
		}
	}

	function randomColor($lower=128, $upper=255) {
		$red = mt_rand($lower, $upper);
		$green = mt_rand($lower, $upper);
		$blue = mt_rand($lower, $upper);

		return imagecolorallocate($this->image, $red, $green, $blue);
	}

	function create($string) {

		$length = strlen($string);
		$charWidth = 35;

		$this->charWidth = $charWidth;

		$imagelength = $length * $charWidth + 16;
		$imageheight = 75;

		$this->width = $imagelength;
		$this->height = $imageheight;

		$this->image  = imagecreate($imagelength, $imageheight);

		$bgcolor     = $this->randomColor(200, 255);

	}

	function text($string) {

		$stringcolor = $this->randomColor(0, 150);
		$linecolor   = imagecolorallocate($this->image, 0, 0, 0);

		/*
		 imagettftext($image, 25, 0, 8, 22,
		 $stringcolor,
		 $font,
		 $string);
		 */

		// Add each letter

		for ($i=0;$i<strlen($string);$i++) {

			$size = rand(25, 60);

			$angle = rand(-20, 20);

			$char = $string[$i];

			$x = $this->charWidth * $i;
			$y = $this->height - 10;

			//$font = $this->font;
			$font = $this->fontList[array_rand($this->fontList)];

			imagettftext($this->image, $size, $angle,
			$x, $y,
			$stringcolor,
			$font,
			$char);

			//imagettftext($image,
			 
		}

	}

}

class GridChallenge extends Challenge {

	function create($string) {

		$this->charWidth = 5;
		$this->charHeight = 7;

		$this->width = 160;
		$this->height = 100;

		$this->image = imagecreate($this->width, $this->height);
		imagecolorallocate($this->image, 255, 255, 200);
	}

	function text($string) {
		$textcolor = imagecolorallocate($this->image, 0, 0, 0);

		$x = ($this->width / 2) - ($this->charWidth * strlen($string));
		$y = ($this->height / 2) - $this->charHeight;

		// write the string at the top left
		imagestring($this->image, $this->charWidth, $x, $y, $string, $textcolor);

	}

	function dots() {
	}

	function arcs() {

		$linecolor = imagecolorallocate($this->image, 0, 0, 0);

		$chunkSize = 5;
		$widthChunk = $this->width / $chunkSize;
		$heightChunk = $this->height / $chunkSize;

		for ($i=0;$i<=$chunkSize;$i++) {

			// Draw horizontal lines
			$coord = min($i * $heightChunk, $this->height-1);
			imageline($this->image, 0, $coord, $this->width, $coord, $linecolor);

			// Draw vertical lines
			$coord = min($i * $widthChunk, $this->width-1);
			imageline($this->image, $coord, 0, $coord, $this->height, $linecolor);
		}
	}

}

class Folder {

	function fontFilter($file) {
		return eregi(".ttf$", $file);
	}

	function toArray($path=".") {

		$list = array();
		if ($handle = opendir($path)) {

			while (false !== ($file = readdir($handle))) {
				$list[] = $file;
			}

			closedir($handle);
			return $list;
		}
	}

}

function letterImage($string) {
	$charWidth = 19;

	$imagelength = $length * $charWidth + 16;
	$imageheight = 35;
}

?>
