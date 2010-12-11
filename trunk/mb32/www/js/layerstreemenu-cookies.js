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
// PHP Layers Menu 3.0.2 (C) 2001-2004 Marco Pratesi - http://www.marcopratesi.it/

function setLMCookie(name, value) {
	document.cookie = name + "=" + value + ";path=/";
}

function getLMCookie(name) {
	foobar = document.cookie.split(name + "=");
	if (foobar.length < 2) {
		return null;
	}
	tempString = foobar[1];
	if (tempString.indexOf(";") == -1) {
		return tempString;
	}
	yafoobar = tempString.split(";");
	return yafoobar[0];
}

function parseExpandString() {
	expandString = getLMCookie("expand");
	expand = new Array();
	if (expandString) {
		expanded = expandString.split("|");
		for (i=0; i<expanded.length-1; i++) {
			expand[expanded[i]] = 1;
		}
	}
}

function parseCollapseString() {
	collapseString = getLMCookie("collapse");
	collapse = new Array();
	if (collapseString) {
		collapsed = collapseString.split("|");
		for (i=0; i<collapsed.length-1; i++) {
			collapse[collapsed[i]] = 1;
		}
	}
}

parseExpandString();
parseCollapseString();

function saveExpandString() {
	expandString = "";
	for (i=0; i<expand.length; i++) {
		if (expand[i] == 1) {
			expandString += i + "|";
		}
	}
	setLMCookie("expand", expandString);
}

function saveCollapseString() {
	collapseString = "";
	for (i=0; i<collapse.length; i++) {
		if (collapse[i] == 1) {
			collapseString += i + "|";
		}
	}
	setLMCookie("collapse", collapseString);
}

