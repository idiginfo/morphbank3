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
	include_once("Auth.inc");
	include_once("Db_Sql.inc");
	
	include_once("labels.inc");
	include_once("media_files.inc");
	include_once("Utils.inc");
	
	$auth = new Auth("","", 1);
	
	$pn_media_id = $auth->getParameter("media_id", pInteger);
	if (!$pn_media_id) { die("No media_id"); }
	$t_media = new media_files($pn_media_id);
	if (!$t_media->getPrimaryKey()) { die("Invalid media_id"); }
	
	$ps_action = $auth->getParameter("action", pString);
	$pn_link_id = $auth->getParameter("link_id", pString);
	if (is_numeric($pn_link_id)) {
		$pn_link_id = intval($pn_link_id);
		if (!$pn_link_id) {
			unset($pn_link_id);
		}
	} else {
		unset($pn_link_id);
	}
	$ps_user_id = $auth->getParameter("user_id", pString);
	switch($ps_action) {
		# --------------------------------------------------------------------------------------------
		case 'save':
			if (((!$ps_user_id) || ($ps_user_id != $t_media->get("user_id"))) && (!$auth->user->hasRole("admin"))) {
				print "&error=".urlencode("Labelling access denied");
				break;
			}
			
			
			$pn_label_id = $auth->getParameter("label_id", pInteger);
			//error_log("label_id is " + $pn_label_id, 1, "seth@whirl-i-gig.com");
			$pn_area_x = $auth->getParameter("area_x", pFloat);
			$pn_area_y = $auth->getParameter("area_y", pFloat);
			$pn_area_w = $auth->getParameter("area_w", pFloat);
			$pn_area_h = $auth->getParameter("area_h", pFloat);
			$pn_text_x = $auth->getParameter("text_x", pFloat);
			$pn_text_y = $auth->getParameter("text_y", pFloat);
			$ps_comment = $auth->getParameter("comment", pString);
			$ps_color = $auth->getParameter("color", pString);
			
			$t_label = new labels($pn_label_id);
			$t_label->setMode(ACCESS_WRITE);
			$t_label->set("label_text", $ps_comment);
			$t_label->set("area_pos_x", $pn_area_x);
			$t_label->set("area_pos_y", $pn_area_y);
			$t_label->set("area_width", $pn_area_w);
			$t_label->set("area_height", $pn_area_h);
			$t_label->set("text_pos_x", $pn_text_x);
			$t_label->set("text_pos_y", $pn_text_y);
			$t_label->set("color", $ps_color);
			$t_label->set("user_id", $ps_user_id);
			if ($t_label->getPrimaryKey()) {
				if (($ps_user_id == $t_label->get("user_id")) || ($auth->user->hasRole("admin"))) {
					$t_label->update();
				} else {
					print "&error=Not%20author";
				}
			} else {
				$t_label->set("media_id", $pn_media_id);
				if ($pn_link_id) {
					$t_label->set("link_id", $pn_link_id);
				}
				$t_label->insert();
			}
			
			print "&label_id=".$t_label->getPrimaryKey();
			break;
		# --------------------------------------------------------------------------------------------
		case 'delete':
			if (((!$ps_user_id) || ($ps_user_id != $t_media->get("user_id"))) && (!$auth->user->hasRole("admin"))) {
				print "&error=".urlencode("Labelling access denied");
				break;
			}
			$pn_label_id = $auth->getParameter("label_id", pInteger);
			$t_label = new labels($pn_label_id);
			if (($ps_user_id == $t_label->get("user_id")) || ($auth->user->hasRole("admin"))) {
				$t_label->setMode(ACCESS_WRITE);
				$t_label->delete();
				
				if ($t_label->numErrors()) {
					foreach($t_label->getErrors() as $vs_e) {
						print "&error=".urlencode($vs_e);
					}
				} else {
					print "&success=1";
				}
			} else {
				print "&error=".urlencode("No such label");
			}
			break;
		# --------------------------------------------------------------------------------------------
		default:
			$vs_sql = "
				SELECT *
				FROM media_labels l
				INNER JOIN weblib_users AS wu ON wu.user_id = l.user_id
				WHERE
					l.media_id = ".intval($pn_media_id)." AND
					l.link_id ".((intval($pn_link_id) > 0) ? "= ".intval($pn_link_id) : "IS NULL");
					
			$q_labels = new Db_Sql($vs_sql);
			
			print "<labels>\n";
			
			while($q_labels->next_record()) {
				print "<label id='".$q_labels->get("label_id")."' title='".$q_labels->get("title", array("ESCAPE_FOR_XML" => true))."' x='".$q_labels->get("x")."' y='".$q_labels->get("y")."' width='".$q_labels->get("w")."' height='".$q_labels->get("h")."' createdOn='".$q_labels->get("created_on")."'>\n";
				
				print "<content><![CDATA[";
				print $q_labels->get("content");
				print "]]></content>\n";
				
				print "<author id='".addslashes($q_labels->get("user_id"))."'>\n";
				print "<firstname>".$q_labels->get("fname")."</firstname>\n";
				print "<lastname>".$q_labels->get("lname")."</lastname>\n";
				print "</author>\n";
				print "</label>\n";
			}
			
			print "</labels>";
			break;
		# --------------------------------------------------------------------------------------------
	}
?>
