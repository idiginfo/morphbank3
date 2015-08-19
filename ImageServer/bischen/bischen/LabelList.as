// ----------------------------------------------
// LabelList class
// ----------------------------------------------
// Implements labels for attachment to an
// ImageViewer object
// ----------------------------------------------
class bischen.LabelList {
	// ---------------------------------------------------------------------------------------------
	private var aLabels:Array;		// array of bischen.Label objects
	private var oLabelXML:XML;		// XML source from which labels were loaded
	
	private var mcLabelLayer:MovieClip;
	private var mcCursorLayer:MovieClip;
	
	private var bLabelsVisible:Boolean = true;
	
	private var bLabelTitleReadOnly:Boolean = false;
	private var bLabelDefaultTitle:String = "";
	
	public var onLoadXML;
	
	public var oViewer;
	
	private var nSelectedLabelID:Number;
	
	private var aLabelID2Index:Array;
	
	private var bLocked:Boolean = false;
	
	private var mcMousePointerResize1D:MovieClip;
	private var mcMousePointerResize2D:MovieClip;
	
	// ---------------------------------------------------------------------------------------------
	function LabelList(mcLabelLayer:MovieClip, sUrl:String, oViewer:bischen.ImageViewer) {
		this.aLabels = new Array();
		this.mcLabelLayer = mcLabelLayer;
		this.mcCursorLayer = _root;
		
		if (sUrl) { this.load(sUrl); }
		
		this.oViewer = oViewer;
		
		this.aLabelID2Index = new Array();
		
		this.mcMousePointerResize1D = mcCursorLayer.attachMovie("iResize1D", "mcResize1DPointer", 60000);
		this.mcMousePointerResize1D._visible = false;
		this.mcMousePointerResize2D = mcCursorLayer.attachMovie("iResize2D", "mcResize2DPointer", 60010);
		this.mcMousePointerResize2D._visible = false;
		
		var lisKeyboard:Object = new Object();
		lisKeyboard.oLabelList = this;
		lisKeyboard.onKeyDown = function() {
			if (!this.oLabelList.oViewer.useKeyboard()) { return false; }
			// ----------------------------------------------------------------------------
			if (Key.isDown(68) || Key.isDown(8) || Key.isDown(46)) {	// delete currently selected label
				if (this.oLabelList.nSelectedLabelID) {
					this.oLabelList.removeLabel(this.oLabelList.nSelectedLabelID);
					this.oLabelList.draw();
				}
			}
			// ----------------------------------------------------------------------------
		}
		Key.addListener(lisKeyboard);
	}
	// ---------------------------------------------------------------------------------------------
	// 
	// ---------------------------------------------------------------------------------------------
	function draw(lScale, sw, sh,nX, nY) {
		// call draw on each label in list
		var i:Number;
		for(i=0; i < this.aLabels.length; i++) {
			this.aLabels[i].draw(lScale, sw, sh, ((this.aLabels[i].id() == this.nSelectedLabelID) ? true : false));
		}
	} 
	// ---------------------------------------------------------------------------------------------
	// 
	// ---------------------------------------------------------------------------------------------
	function showLabels() {
		var i:Number;
		for(i=0; i < this.aLabels.length; i++) {
			this.aLabels[i].isVisible(true);
		}
		this.bLabelsVisible = true;
	}
	// ---------------------------------------------------------------------------------------------
	// 
	// ---------------------------------------------------------------------------------------------
	function hideLabels() {
		var i:Number;
		for(i=0; i < this.aLabels.length; i++) {
			this.aLabels[i].isVisible(false);
		}
		this.bLabelsVisible = false;
	}
	// ---------------------------------------------------------------------------------------------
	// List manipulation
	// ---------------------------------------------------------------------------------------------
	function addLabel(nTypecode:Number) {
		if (this.locked()) { return false; }
		if (!this.oViewer.editingLabels()) { return false; }
		if (!nTypecode) { nTypecode = 0; }
		nTypecode = Math.ceil(nTypecode);
		
		
		this.setStatusMessage("Adding label...");
		
		var lvAdd:LoadVars = new LoadVars();
		var rvAdd:LoadVars = new LoadVars();
		
		// What part of the screen is visible?
		var aVisibleCenter = this.oViewer.getVisibleAreaCenter();
		
		var nNewX:Number = aVisibleCenter[0] * 100;
		var nNewY:Number = aVisibleCenter[1] * 100;
		
		var nMag:Number = this.oViewer.getMagnification();
	
		var nNewW:Number = 10/nMag;
		if (nNewW > 30) { nNewW = 30; }
		var nNewH:Number = 10/nMag;
		if (nNewH > 30) { nNewH = 30; }
		
		var sNewTitle:String = "Enter title for new label here";
		if (this.getLabelDefaultTitle()) {
			sNewTitle = this.getLabelDefaultTitle();
		}
		var sNewContent:String = "Enter label description here";
		
		lvAdd.action = "media_labels";
		lvAdd.service = "save";
		lvAdd.label_id = 0;
		
		lvAdd.x = nNewX;
		lvAdd.y = nNewY;
		
		lvAdd.w = nNewW;
		lvAdd.h = nNewH;
		lvAdd.title = sNewTitle;
		lvAdd.content = sNewContent;
		lvAdd.typecode = nTypecode;
		
		var oParameters:Object = this.oViewer.getParameterValues();
		var sParam:String;
		for(sParam in oParameters) {
			trace("Set " + sParam + " = " + oParameters[sParam]);
			lvAdd[sParam] = oParameters[sParam];
		}
		
		rvAdd.params = lvAdd;
		rvAdd.oLabelList = this;
		
		rvAdd.onLoad = function() {
			if (this.success == 1) {
				this.oLabelList.setStatusMessage("");
				
				var oLabel:Object;
				switch(nTypecode) {
					case 0:
						oLabel = new bischen.RectangularAreaLabel(this.oLabelList, this.label_id, this.oLabelList.mcLabelLayer, this.oLabelList.labelCount() + 1001, this.params.title , this.params.content);
						oLabel.setLocation(this.params.x, this.params.y);
						oLabel.setSize(this.params.w, this.params.h);
						break;
					case 1:
						oLabel = new bischen.StickLabel(this.oLabelList, this.label_id, this.oLabelList.mcLabelLayer, this.oLabelList.labelCount() + 1001, this.params.title , this.params.content);
						oLabel.setLocation(this.params.x, this.params.y);
						break;
					default:
						trace("Invalid typecode '" + nTypecode + "'");
						break;
				}
				
				
				this.oLabelList.aLabels.push(oLabel);
				this.oLabelList.aLabelID2Index["" + oLabel.id()] = this.oLabelList.aLabels.length - 1;
				this.oLabelList.oViewer.drawLabels();
			} else {
				trace("Error creating label: "+ this.error);
				trace(unescape(this));
			}
		}
		var sLabelProcessorUrl:String = this.oViewer.getLabelProcessorURL();
		lvAdd.sendAndLoad(sLabelProcessorUrl, rvAdd, "GET");
		
		return true;
	}
	// ---------------------------------------------------------------------------------------------
	function removeLabel(nLabelID:Number) {
		if (this.locked()) { return false; }
		if (!this.oViewer.editingLabels()) { return false; }
		var aNewLabelList:Array = new Array();
		var i:Number;
		
		this.setStatusMessage("Deleting label...");
		for(i=0; i < this.aLabels.length; i++) {
			if (this.aLabels[i].id() != nLabelID) {
				aNewLabelList.push(this.aLabels[i]);
			} else {
				var lvAdd:LoadVars = new LoadVars();
				var rvAdd:LoadVars = new LoadVars();
				
				lvAdd.action = "media_labels";
				lvAdd.service = "delete";
				lvAdd.label_id = this.aLabels[i].id();
				
				this.aLabels[i].getLabelMC().removeMovieClip();
				this.aLabels[i] = null;
				
				var oParameters:Object = this.oViewer.getParameterValues();
				var sParam:String;
				for(sParam in oParameters) {
					lvAdd[sParam] = oParameters[sParam];
				}
				
				rvAdd.params = lvAdd;
				rvAdd.oLabelList = this;
				
				rvAdd.onLoad = function() {
					if (this.success == 1) {
						this.oLabelList.setStatusMessage("");
					} else {
						trace("Error deleting label: "+ this.error);
						trace(unescape(this));
					}
				}
				var sLabelProcessorUrl:String = this.oViewer.getLabelProcessorURL();
				lvAdd.sendAndLoad(sLabelProcessorUrl, rvAdd, "GET");
			}
		}
		this.aLabels = aNewLabelList;
	}
	// ---------------------------------------------------------------------------------------------
	function getList() {
		return this.aLabels;
	}
	// ---------------------------------------------------------------------------------------------
	function clearList() {
		this.aLabels = new Array();
		return true;
	}
	// ---------------------------------------------------------------------------------------------
	function labelCount() {
		return this.aLabels.length;
	}
	// ---------------------------------------------------------------------------------------------
	function disableViewerKeys(bDisableViewerKeys:Boolean) {
		this.oViewer.useKeyboard(!bDisableViewerKeys);
	}
	// ---------------------------------------------------------------------------------------------
	// -- Load labels
	// ---------------------------------------------------------------------------------------------
	function load(sUrl:String, fCallback) {
		var oLabelXML:Object = new XML();
		oLabelXML.ignoreWhite = true;
		//
		// Use loaded XML
		//
		oLabelXML.oLabelList = this;
		
		this.setStatusMessage("Loading labels...");
		oLabelXML.onLoad = function(bSuccess:Boolean) {
			if (bSuccess) {
				var aNodes:Array = this.firstChild.childNodes;
				
				// loop through label list xml by label
				var i:Number;
				for (i=0; i<aNodes.length; i++) {
					var j:Number;
					
					var sTitle:String = aNodes[i].attributes.title;
					var nTypeCode:Number = Math.ceil(aNodes[i].attributes.typecode);
					var sContent:String = "";
					for (j=0; j< aNodes[i].childNodes.length; j++) {
						switch (aNodes[i].childNodes[j].nodeName) {
							case 'content':
								sContent = aNodes[i].childNodes[j].firstChild.nodeValue;
								break;
						}
					}
					
					var oLabel:Object;
					switch(nTypeCode) {
						case 0:
							oLabel = new bischen.RectangularAreaLabel(this.oLabelList, aNodes[i].attributes.id, this.oLabelList.mcLabelLayer, i + 1000, sTitle, sContent);
							oLabel.setLocation(aNodes[i].attributes.x, aNodes[i].attributes.y);
							oLabel.setSize(aNodes[i].attributes.width, aNodes[i].attributes.height);
							break;
						case 1:
							oLabel = new bischen.StickLabel(this.oLabelList, aNodes[i].attributes.id, this.oLabelList.mcLabelLayer, i + 1000, sTitle, sContent);
							oLabel.setLocation(aNodes[i].attributes.x, aNodes[i].attributes.y);
							break;
					}	
					
					oLabel.setLabelIndex(i);
					this.oLabelList.aLabels.push(oLabel);
					this.oLabelList.aLabelID2Index["" + oLabel.id()] = this.oLabelList.aLabels.length - 1;
				}
				
				//
				// Call load completion handler if defined
				//
				if (this.oLabelList.onLoadXML) {
					this.oLabelList.onLoadXML(this.oLabelList);
				}
				trace("Loaded label XML");
				this.oLabelList.setStatusMessage("");
				if (fCallback) {
					fCallback.drawLabels();
				}
			} else {
				trace("There was an error loading the label XML");
			}
		};
		oLabelXML.load(sUrl);
		
		return oLabelXML;
	}
	// ---------------------------------------------------------------------------------------------
	function showResizeMousePointer(bShow:Boolean, sCorner:String) {
		if (sCorner.length == 1) {
			this.mcMousePointerResize1D._visible = bShow;
			this.mcMousePointerResize2D._visible = false;
		} else {
			this.mcMousePointerResize1D._visible = false;
			this.mcMousePointerResize2D._visible = bShow;
		}
		
		if (bShow) {
			switch(sCorner) {
				case 'UL':
					this.mcMousePointerResize2D._rotation = -90;
					break;
				case 'LL':
					this.mcMousePointerResize2D._rotation = -180;
					break;
				case 'L':
					this.mcMousePointerResize1D._rotation = -90;
					break;
				case 'UR':
					this.mcMousePointerResize2D._rotation = 0;
					break;
				case 'LR':
					this.mcMousePointerResize2D._rotation = 90;
					break;
				case 'R':
					this.mcMousePointerResize1D._rotation = 90;
					break;
				case 'T':
					this.mcMousePointerResize1D._rotation = 0;
					break;
				case 'B':
					this.mcMousePointerResize1D._rotation = 180;
					break;
			}
			if (sCorner.length == 1) {
				this.mcMousePointerResize1D._x = this.mcMousePointerResize1D._parent._xmouse;
				this.mcMousePointerResize1D._y = this.mcMousePointerResize1D._parent._ymouse;
			} else {
				this.mcMousePointerResize2D._x = this.mcMousePointerResize2D._parent._xmouse;
				this.mcMousePointerResize2D._y = this.mcMousePointerResize2D._parent._ymouse;
			}
		}
	}
	// ---------------------------------------------------------------------------------------------
	// -- Accessors
	// ---------------------------------------------------------------------------------------------
	function labelsVisible() {
		return this.bLabelsVisible;
	}
	// ---------------------------------------------------------------------------------------------
	function selectLabel(nSelectedLabelID:Number) {
		if (!this.oViewer.editingLabels()) { return false; }
		this.nSelectedLabelID = nSelectedLabelID;
		var i:Number;
		
		for(i=0; i < this.aLabels.length; i++) {
			if (this.aLabels[i].getLabelMC().getDepth() != (1000 + i)) {
				this.aLabels[i].getLabelMC().swapDepths(1000 + i);
			}
		}
		
		for(i=0; i < this.aLabels.length; i++) {
			if (this.aLabels[i].id() == nSelectedLabelID) {
				this.aLabels[i].getLabelMC().swapDepths(60000);
				this.aLabels[i].draw(undefined, undefined, undefined, true);
			} else {
				this.aLabels[i].draw(undefined, undefined, undefined, false);
			}
		}
		
	}
	// ---------------------------------------------------------------------------------------------
	function getSelectedLabel() {
		var i:Number;
		if (!this.nSelectedLabelID) { return false;} 
		
		for(i=0; i < this.aLabels.length; i++) {
			if (this.aLabels[i].id() == this.nSelectedLabelID) {
				return this.aLabels[i];
			}
		}
		return false;
	}
	// ---------------------------------------------------------------------------------------------
	function clearLabelSelection() {
		this.nSelectedLabelID = null;
		var i:Number;
		for(i=0; i < this.aLabels.length; i++) {
			this.aLabels[i].draw(undefined, undefined, undefined, false);
		}
	}
	// ---------------------------------------------------------------------------------------------
	function locked(bLocked:Boolean) {
		if (bLocked != undefined) {
			this.bLocked = bLocked;
			if (bLocked) {
				this.clearLabelSelection();
			}
		}
		return this.bLocked;
	}
	// ---------------------------------------------------------------------------------------------
	function setStatusMessage(sMessage:String) {
		return this.oViewer.setStatusMessage(sMessage);
	}
	// ---------------------------------------------------------------------------------------------
	function setLabelDefaultTitle(sTitle:String) {
		return this.bLabelDefaultTitle = sTitle;
	}
	// ---------------------------------------------------------------------------------------------
	function getLabelDefaultTitle():String {
		return this.bLabelDefaultTitle;
	}
	// ---------------------------------------------------------------------------------------------
	function setLabelTitleReadOnly(bSetting:Boolean) {
		this.bLabelTitleReadOnly = bSetting;
	}
	// ---------------------------------------------------------------------------------------------
	function labelTitleReadOnly():Boolean {
		return this.bLabelTitleReadOnly;
	}
	// ---------------------------------------------------------------------------------------------
}
