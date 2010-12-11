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
/* Copyright (c) 2005 Tim Taylor Consulting (see LICENSE.txt)

based on http://www.quirksmode.org/js/cookies.html
*/

ToolMan._cookieOven = {

	set : function(name, value, expirationInDays) {
		if (expirationInDays) {
			var date = new Date()
			date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000))
			var expires = "; expires=" + date.toGMTString()
		} else {
			var expires = ""
		}
		document.cookie = name + "=" + value + expires + "; path=/"
	},

	get : function(name) {
		var namePattern = name + "="
		var cookies = document.cookie.split(';')
		for(var i = 0, n = cookies.length; i < n; i++) {
			var c = cookies[i]
			while (c.charAt(0) == ' ') c = c.substring(1, c.length)
			if (c.indexOf(namePattern) == 0)
				return c.substring(namePattern.length, c.length)
		}
		return null
	},

	eraseCookie : function(name) {
		createCookie(name, "", -1)
	}
}
