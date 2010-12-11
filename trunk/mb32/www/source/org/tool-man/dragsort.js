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
/* Copyright (c) 2005 Tim Taylor Consulting (see LICENSE.txt) */

ToolMan._dragsortFactory = {
	makeSortable : function(item) {
		var group = ToolMan.drag().createSimpleGroup(item)

		group.register('dragstart', this._onDragStart)
		group.register('dragmove', this._onDragMove)
		group.register('dragend', this._onDragEnd)

		return group
	},

	/** 
	 * Iterates over a list's items, making them sortable, applying
	 * optional functions to each item.
	 *
	 * example: makeListSortable(myList, myFunc1, myFunc2, ... , myFuncN)
	 */
	makeListSortable : function(list) {
		var helpers = ToolMan.helpers()
		var coordinates = ToolMan.coordinates()
		var items = list.getElementsByTagName("li")

		helpers.map(items, function(item) {
			var dragGroup = dragsort.makeSortable(item)
			dragGroup.setThreshold(4)
			var min, max
			dragGroup.addTransform(function(coordinate, dragEvent) {
				return coordinate.constrainTo(min, max)
			})
			dragGroup.register('dragstart', function() {
				var items = list.getElementsByTagName("li")
				min = max = coordinates.topLeftOffset(items[0])
				for (var i = 1, n = items.length; i < n; i++) {
					var offset = coordinates.topLeftOffset(items[i])
					min = min.min(offset)
					max = max.max(offset)
				}
			})
		})
		for (var i = 1, n = arguments.length; i < n; i++)
			helpers.map(items, arguments[i])
	},

	_onDragStart : function(dragEvent) {
	},

	_onDragMove : function(dragEvent) {
		var helpers = ToolMan.helpers()
		var coordinates = ToolMan.coordinates()

		var item = dragEvent.group.element
		var xmouse = dragEvent.transformedMouseOffset
		var moveTo = null

		var previous = helpers.previousItem(item, item.nodeName)
		while (previous != null) {
			var bottomRight = coordinates.bottomRightOffset(previous)
			if (xmouse.y <= bottomRight.y && xmouse.x <= bottomRight.x) {
				moveTo = previous
			}
			previous = helpers.previousItem(previous, item.nodeName)
		}
		if (moveTo != null) {
			helpers.moveBefore(item, moveTo)
			return
		}

		var next = helpers.nextItem(item, item.nodeName)
		while (next != null) {
			var topLeft = coordinates.topLeftOffset(next)
			if (topLeft.y <= xmouse.y && topLeft.x <= xmouse.x) {
				moveTo = next
			}
			next = helpers.nextItem(next, item.nodeName)
		}
		if (moveTo != null) {
			helpers.moveBefore(item, helpers.nextItem(moveTo, item.nodeName))
			return
		}
	},

	_onDragEnd : function(dragEvent) {
		ToolMan.coordinates().create(0, 0).reposition(dragEvent.group.element)
	}
}
