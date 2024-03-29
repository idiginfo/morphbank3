// ----------------------------------------------
// Image Viewer class
// ----------------------------------------------
// Implements zoom and pan viewer in 
// a MovieClip for JPEG and TilePic format
// files.
// ----------------------------------------------
// Constructor
class bischen.ImageViewer {	
	public var _debug:Boolean = false;
	
	// MovieClip containing entire viewer
	private var mcViewerContainer:MovieClip;
	
	// MovieClip containing current view of image
	private var mcImageViewer:MovieClip;
	
	// MovieClip containing low-resolution underlying image
	private var mcBackground:MovieClip;
	
	// MovieClip containing very low-resolution (always single tile) underlying image 
	private var mcBackgroundBackground:MovieClip;
	
	private var mcImageLayer:MovieClip;
	
	// MovieClip labels overlaying image; contained by mcViewerContainer
	private var mcLabelLayer:MovieClip;
	private var nLabelTypecode:Number; // type of label to use with this viewer instance (0=rectangular area labels; 1=stick labels)
	private var bLabelTitleReadOnly:Boolean = false;
	private var bLabelDefaultTitle:String = "";
	
	// Image geometry (width, height, tile info, etc.)
	private var aGeometry:Array;
	private var pWidth:Number;
	private var pHeight:Number;
	private var pScales:Number;
	private var pRatio:Number;
	private var aImageDimensions:Array;
	private var pTileWidth:Number;
	private var pTileHeight:Number;
	private var pViewerURL:String;
	private var pImageURL:String;
	
	private var nCurScale:Number;
	
	private var aTilesLoading:Array;
	private var aTilesToLoadList:Array;
	private var aTilesToLoadDict:Array;
	private var aPriorityTilesToLoadList:Array;
	
	private var aTileCache:Array;
	
	private var oLabelList:bischen.LabelList;
	private var bEditLabels:Boolean;
	private var bUseLabels:Boolean;
	private var bLabelsLoaded:Boolean;
	private var pList:String;
	private var oParameterValues:Object;
	private var pLabelProcessorURL:String;
	
	private var bAntialiasingWhileMoving:Boolean = true;		// if false, quality is set to "low" when panning of zooming to speed up redraws
	
	private var bEditScale:Boolean;
	private var bUseScale:Boolean;
	
	private var nMagnification:Number;
	private var nDefaultMagnificationIncrement:Number;
	private var nMagnificationIncrement:Number;
	private var nBackgroundScale:Number;
	
	// MovieClip loader for monitoring tile load progress
	private var mclTileLoader:MovieClipLoader;
	private var lisTileLoader:Object; 				// MCL listener
	private var nTilesLoading:Number = 0;
	
	// Viewer toolbar Movie Clip
	private var mcToolbar;
	
	
	private var nViewerWidth:Number;
	private var nViewerHeight:Number;
	private var mcViewerMask:MovieClip;
	
	// tile loader "daemon" function
	private var fTileLoader:Function; 
	
	// Minimum and maximum zoom levels; 1=100%
	private var nMinMagnification:Number = 0.01;
	private var nMaxMagnification:Number = 6;
	
	private var nReferenceImageScale:Number;
	private var mcReferenceImagePalette:MovieClip;
	
	private var mcLabelPalette:MovieClip;
	
	private var bPalettesVisible:Boolean = true;
	
	private var bUseKeyboard:Boolean = true;
	
	function ImageViewer(psName:String, pmcParentClip:MovieClip, pnLevel:Number, nViewerWidth:Number, nViewerHeight:Number) {
		//
		// Parent clip contains the image viewer
		//
		this.nViewerWidth = nViewerWidth;
		this.nViewerHeight = nViewerHeight;
		
		var mcParentClip:MovieClip;
		if (pmcParentClip == undefined) {
			mcParentClip = _root;
		} else {
			mcParentClip = pmcParentClip;
		}
		
		//
		// Level in which to place image viewer
		// Default is -16834, which means it will be behind all authored content
		//
		if (pnLevel == undefined) {
			pnLevel = -16834;
		}
		//
		// Image viewer movie clip - the image and labels live in here
		//
		this.mcViewerContainer = mcParentClip.createEmptyMovieClip(psName, pnLevel);
		this.mcViewerContainer.oViewer = this;
		this.mcImageViewer = this.mcViewerContainer.createEmptyMovieClip(psName, 100);
		
		this.mcImageLayer = this.mcImageViewer.createEmptyMovieClip("mcImageLayer", 9000);
		this.mcLabelLayer = this.mcImageViewer.createEmptyMovieClip("mcLabelLayer", 10000);
		
		this.mcViewerMask = this.mcViewerContainer.createEmptyMovieClip(psName + "_mask", pnLevel + 10);
		this.mcViewerMask.moveTo(0,0);
		this.mcViewerMask.beginFill(0xFF0000);
		this.mcViewerMask.lineTo(0,this.nViewerHeight);
		this.mcViewerMask.lineTo(this.nViewerWidth,this.nViewerHeight);
		this.mcViewerMask.lineTo(this.nViewerWidth,0);
		this.mcViewerMask.lineTo(0,0);
		this.mcViewerMask.endFill();

		this.mcImageViewer.setMask(this.mcViewerMask);
		
		this.mcImageViewer.oViewer = this;
		this.mcImageLayer.oViewer = this;
		
		this.mcBackground = this.mcImageLayer.createEmptyMovieClip(psName+"_background", -65000);
		this.mcBackgroundBackground = this.mcImageLayer.createEmptyMovieClip(psName+"_backgroundbackground", -65010);
		
		
		// Tile loader
		this.mclTileLoader = new MovieClipLoader();
		this.lisTileLoader = new Object();
		this.lisTileLoader.oImageViewer = this;
		this.lisTileLoader.onLoadInit = function(mcTarget) {
			this.oImageViewer.nTilesLoading--;
		}
		this.lisTileLoader.onLoadError = function(mcTarget, sErrorCode) {
			trace("Error loading tile into " + mcTarget + "; error: " + sErrorCode);
		}
		this.lisTileLoader.onLoadComplete = function(mcTarget) {
			//trace("Loaded tile into " + mcTarget);
		}
		this.mclTileLoader.addListener(lisTileLoader);
		
		// Set up error display
		this.mcImageViewer.createTextField("tError", 11, 0, Math.ceil(this.nViewerHeight/2.0), this.nViewerWidth, 200);
		var tfErr = new TextFormat();
		tfErr.font = "Arial";
		tfErr.size = 30;
		tfErr.color = 0xFFFFFF;
		tfErr.align = "center";
		this.mcImageViewer.tError.setNewTextFormat(tfErr);
		
		
		//
		// initial magnification increment is 3%
		//
		this.nDefaultMagnificationIncrement = 0.04;
		this.nMagnificationIncrement = this.nDefaultMagnificationIncrement;
		this.nCurScale = undefined;
		
		// 
		// contains information on each image scale
		//
		this.aGeometry = new Array();
		
		// arrays managing tile loading process
		this.aTilesToLoadList = new Array();
		this.aTilesToLoadDict = new Array();
		this.aTilesLoading = new Array();
		this.aPriorityTilesToLoadList = new Array();
		
		// Initialize tile cache
		this.aTileCache = new Array();
		
		// which image scale to use as low-resolution background while tiles are loading
		this.nBackgroundScale = 1;
		
		// do we need to support labels?
		this.bUseLabels = false; 	// default is no
		this.bEditLabels = false; 	// default is no
		this.bLabelsLoaded = false;	// will be true once labels are loaded 
		
		// do we need to support scales?
		this.bEditScale = false;
		this.bUseScale = false;
	
		// additional parameter list
		this.pList = "";
		this.oParameterValues = new Object;
		
		
		// create tool bar
		this.mcToolbar = this.mcViewerContainer.attachMovie("mcBischenToolbar", "mcToolbar", 48000); 
		
		this.mcToolbar.oViewer = this;
		this.mcToolbar._x = 10;
		this.mcToolbar._y = 10;
		this.mcToolbar.iZoomIn.gotoAndPlay(3);
		this.mcToolbar.nCurrentSelection = 0;
		this.setStatusMessage("");
		
		
		// start tile loading "daemon"
		this.mcImageLayer.nCounter = 0;
		
		this.fTileLoader = function() {
			this.nCounter++;
			if ((this.nCounter % 3) == 0) {	// only call updateTiles() every third go-round
				this.oViewer.updateTiles();
				this.nCounter = 0;
			}
		};
		
		this.mcImageLayer.onEnterFrame = this.fTileLoader;
		
		
		this.mcImageLayer.onPress = function() {
			// deselect label, if one is selected
			this.oViewer.oLabelList.clearLabelSelection();
			
			switch(this.oViewer.mcToolbar.nCurrentSelection) {
				case 0:		// Zoom in
					if (!this.oViewer.bAntialiasingWhileMoving) { _quality = "low"; }
					this.onEnterFrame = function() {
						var nIncrement:Number = this.oViewer.getMagnificationIncrement();
						if (nIncrement < 0.12) {
							nIncrement = Number(nIncrement) +  0.007;
						}
						var nMag:Number = this.oViewer.getMagnification() + (this.oViewer.getMagnification() * nIncrement);
						if (nMag > this.oViewer.nMaxMagnification) {
							nMag = this.oViewer.nMaxMagnification;
						}
						this.oViewer.setMagnification(nMag);
						this.oViewer.setMagnificationIncrement(Number(nIncrement));
						this.oViewer.updateViewer(this, _root._xmouse, _root._ymouse);
						
						
						this.oViewer.drawLabels();
					};	
					break;
				case 1:		// Zoom out
					if (!this.oViewer.bAntialiasingWhileMoving) { _quality = "low"; }
					this.onEnterFrame = function() {
						var nIncrement:Number = this.oViewer.getMagnificationIncrement();
						if (nIncrement < 0.12) {
							nIncrement = Number(nIncrement) +  0.007;
						}
						var nMag:Number = this.oViewer.getMagnification() - (this.oViewer.getMagnification() * nIncrement);
						if (nMag < this.nMinMagnification) {
							nMag = this.nMinMagnification;
						}
						this.oViewer.setMagnification(nMag);
						this.oViewer.setMagnificationIncrement(nIncrement);
						this.oViewer.updateViewer(this, _root._xmouse, _root._ymouse);
							
						this.oViewer.drawLabels();
					};
					break;
				case 2:		// Pan
					if (!this.oViewer.bAntialiasingWhileMoving) { _quality = "low"; }
					this.startDrag(false,0,0,this.oViewer.nViewerWidth - this._width, this.oViewer.nViewerHeight - this._height);
					//this.oViewer.mcImageViewer.startDrag(false,0,0,this.oViewer.nViewerWidth - this._width, this.oViewer.nViewerHeight - this._height);
					this.onEnterFrame = function() {
						this._parent.mcLabelLayer._x = this._x;
						this._parent.mcLabelLayer._y = this._y;
						
						this.oViewer.drawReferencePaletteHighlight()
						//if (!this.oViewer.drawReferencePaletteHighlight()) {
						//	this.onEnterFrame = null;
						//}
					}

					break;
				
			}
		}
		this.mcImageLayer.onRelease = this.mcImageLayer.onReleaseOutside = function() {
			if (!this.oViewer.bAntialiasingWhileMoving) { _quality = "high"; }
			this.stopDrag();
			this.onEnterFrame = this.oViewer.fTileLoader;
			
			switch(this.oViewer.mcToolbar.nCurrentSelection) {
				case 0:		// Zoom in
					break;
				case 1:		// Zoom out
					break;
				case 2:		// Pan
					this.oViewer.updateViewer(this, 0, 0);
					this.onEnterFrame = null;
					break;
				
			}
		}
		
		// Keyboard shortcuts
		var lisKeyboard:Object = new Object();
		lisKeyboard.oViewer = this;
		lisKeyboard.onKeyDown = function() {
			if (!this.oViewer.useKeyboard()) { return false; }
			// ----------------------------------------------------------------------------
			if (Key.isDown(76)) {
				// 'L'
				if (this.oViewer.oLabelList.labelsVisible()) {
					this.oViewer.oLabelList.hideLabels();
				} else {
					this.oViewer.oLabelList.showLabels();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(80)) {
				// 'P'
				this.oViewer.mcToolbar.iPan.onPress(1);
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(187)) {
				// '+'
				this.oViewer.mcToolbar.iZoomIn.onPress(1);
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(189)) {
				// '-'
				this.oViewer.mcToolbar.iZoomOut.onPress(1);
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(219)) { 
				// '[' (zoom out 1%)
				if ((this.oViewer.getMagnification() - 0.01) > this.oViewer.nMinMagnification) {
					this.oViewer.setMagnification(this.oViewer.getMagnification() - 0.01);
					this.oViewer.updateViewer(this.oViewer.mcImageLayer, this.oViewer.nViewerWidth/2, this.oViewer.nViewerHeight/2);
					this.oViewer.drawLabels();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(221)) { 
				// ']' (zoom in 1%)
				if ((this.oViewer.getMagnification() + 0.01) < this.oViewer.nMaxMagnification) {
					this.oViewer.setMagnification(this.oViewer.getMagnification() + 0.01);
					this.oViewer.updateViewer(this.oViewer.mcImageLayer, this.oViewer.nViewerWidth/2, this.oViewer.nViewerHeight/2);
					this.oViewer.drawLabels();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(Key.UP)) {
				var nInc:Number = 10 * this.oViewer.getMagnification();
				if (this.oViewer.mcImageLayer._y + nInc <= 0 ) {
					this.oViewer.mcImageLayer._y += nInc;
					this.oViewer.drawReferencePaletteHighlight();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(Key.DOWN)) {
				var nInc:Number = 10 * this.oViewer.getMagnification();
				if ((this.oViewer.mcImageLayer._y - nInc) > (this.oViewer.nViewerHeight - this.oViewer.mcImageLayer._height)) {
					this.oViewer.mcImageLayer._y -= nInc;
					this.oViewer.drawReferencePaletteHighlight();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(Key.LEFT)) {
				var nInc:Number = 10*this.oViewer.getMagnification();
				if ((this.oViewer.mcImageLayer._x - nInc) > (this.oViewer.nViewerWidth - this.oViewer.mcImageLayer._width)) {
					this.oViewer.mcImageLayer._x -= nInc;
					this.oViewer.drawReferencePaletteHighlight();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(Key.RIGHT)) {
				var nInc:Number = 10 * this.oViewer.getMagnification();
				if ((this.oViewer.mcImageLayer._x + nInc) <= 0) {
					this.oViewer.mcImageLayer._x += nInc;
					this.oViewer.drawReferencePaletteHighlight();
				}
			}// ----------------------------------------------------------------------------
			if (Key.isDown(78)) { // R
				if (this.oViewer.referencePaletteIsVisible()) {
					this.oViewer.hideReferencePalette();
				} else {
					this.oViewer.showReferencePalette();
				}
			}
			// ----------------------------------------------------------------------------
			if (Key.isDown(Key.TAB)) {
				var oSharedObject:SharedObject = SharedObject.getLocal("bischen");
				if (this.oViewer.bPalettesVisible) {
					this.oViewer.hideToolbar();
					
					if (oSharedObject.data["referenceImagePaletteVisible"]) { this.oViewer.hideReferencePalette(false); }
					if (oSharedObject.data["labelPaletteVisible"]) { this.oViewer.hideLabelPalette(false); }
					
					this.oViewer.bPalettesVisible = false;
					this.oViewer.oLabelList.hideLabels();
				} else {
					this.oViewer.showToolbar();
					
					if (oSharedObject.data["referenceImagePaletteVisible"]) { this.oViewer.showReferencePalette(false); }
					if (oSharedObject.data["labelPaletteVisible"]) { this.oViewer.showLabelPalette(false); }
					
					this.oViewer.bPalettesVisible = true;
					this.oViewer.oLabelList.showLabels();
				}
			}// ----------------------------------------------------------------------------
		}
		
		lisKeyboard.onKeyUp = function() {
			this.oViewer.updateViewer(this.oViewer.mcImageLayer, _root._xmouse, _root._ymouse);
		}
		Key.addListener(lisKeyboard);
	}
	// --------------------------------------------------------------------------------------------------
	function close() {
		this.mcViewerContainer.removeMovieClip();
	}
	// --------------------------------------------------------------------------------------------------
	//
	// Viewer methods
	//
	// ---------------------------------------------------------------
	function redraw() {
		this.loadViewerBackground();
		this.updateViewer(this.mcImageLayer);
		this.drawLabels();
		// Show reference image palette by default? 
		if (!this.mcReferenceImagePalette) {
			var oSharedObject:SharedObject = SharedObject.getLocal("bischen");
			
			if (oSharedObject.data["referenceImagePaletteVisible"]) {
				this.showReferencePalette(true);
			}
			if (oSharedObject.data["labelPaletteVisible"]) {
				this.showLabelPalette(true);
			}
		}
	}
	// ---------------------------------------------------------------
	function zoomToScale(nToScale:Number, nToX:Number, nToY:Number) {
		if (!this.bAntialiasingWhileMoving) { _quality = "low"; }
		
		this.mcImageLayer.onEnterFrame = function() {
			var nScale:Number = nToScale;
			
			var nMag:Number = this.oViewer.getMagnification();
			var nIncrement:Number = this.oViewer.getMagnificationIncrement();
			if (nIncrement < 0.12) {
				nIncrement = Number(nIncrement) +  0.007;
			}
			
			// Number of steps needed to get to target magnification
			var nSteps:Number = Math.ceil((Math.abs(nScale - nMag))/nIncrement);
			
			if (nMag < nScale) {
				nMag = Number(nMag) + (Number(nIncrement) * Number(nMag));
				if (nMag > nScale) { nMag = nScale; nSteps = 1; }
			} else {
				nMag = Number(nMag) - (Number(nIncrement) * Number(nMag));
				if (nMag < nScale ) { nMag = nScale; nSteps = 1; }
			}
			
			if (Math.abs(nMag - nScale) < 0.01) {
				nMag = nScale;
				nSteps = 1;
			}
			
			this.oViewer.setMagnification(nMag);
			this.oViewer.setMagnificationIncrement(nIncrement);
			
			// Width of visible image in magnified stage coord space
			var nViewerMagW:Number = this.oViewer.pWidth*nMag;
			var nViewerMagH:Number = this.oViewer.pHeight*nMag;
			
			// Center of viewer (in stage coord space)
			var nMx:Number = this.oViewer.nViewerWidth/2;
			var nMy:Number = this.oViewer.nViewerHeight/2;
			
			// Coordinates of target center of image
			var nICx:Number = this._x + (this.oViewer.aGeometry[this.oViewer.nCurScale]["width"] * nToX * (this._xscale/100));
			var nICy:Number = this._y + (this.oViewer.aGeometry[this.oViewer.nCurScale]["height"] * nToY * (this._yscale/100));
			
			if (nSteps >= 1){
				if (nICx > nMx) {
					this._x = this._x - (nICx - nMx)/(nSteps);
				}
				if (nICx < nMx) {
					this._x = this._x +  (nMx - nICx)/(nSteps);
				}
				if (nICy > nMy) {
					this._y = this._y - (nICy - nMy)/(nSteps);
				}
				if (nICy < nMy) {
					this._y = this._y + (nMy - nICy)/(nSteps);
				}
			}
			
			
			this.oViewer.mcToolbar.tMagnification.text = String(Math.ceil(nMag*100)+"%");
			
			if (nMag == nScale) {
				if (!this.oViewer.bAntialiasingWhileMoving) { _quality = "high"; }
				this.oViewer.mcImageLayer.onEnterFrame = this.oViewer.fTileLoader;
			}
			this.oViewer.updateViewer(this, nMx, nMy);
			
			this.oViewer.drawLabels();
			this.oViewer.drawReferencePaletteHighlight();
		}
	}
	// ---------------------------------------------------------------
	function getVisibleAreaOrigin() {
		var nMag:Number = this.getMagnification();
		var nRx:Number = (-1 * this.mcImageLayer._x)/(this.pWidth * nMag);
		var nRy:Number = (-1 * this.mcImageLayer._y)/(this.pHeight * nMag);	
		
		return [nRx, nRy];
	}
	// ---------------------------------------------------------------
	function getVisibleAreaCenter() {
		var nMag:Number = this.getMagnification();
		var nRx:Number = ((this.nViewerWidth/2) - this.mcImageLayer._x)/(this.pWidth * nMag);
		var nRy:Number = ((this.nViewerHeight/2) - this.mcImageLayer._y)/(this.pHeight * nMag);	
		
		return [nRx, nRy];
	}
	// ---------------------------------------------------------------
	function fitToScreen() {
		var nScaleX:Number = this.nViewerWidth/this.pWidth;
		var nScaleY:Number = this.nViewerHeight/this.pHeight;
		var nScale:Number = (nScaleX > nScaleY) ?  nScaleY : nScaleX;
		
		this.zoomToScale(nScale, 0.5, 0.5); // zoom to center
	}
	// ---------------------------------------------------------------
	function fitToActualSize() {
		var nMag:Number = this.getMagnification();
		
		// coords of point of image that is in center of viewer
		var nCx:Number = ((this.nViewerWidth/2) - this.mcImageLayer._x)/(this.pWidth * nMag);
		var nCy:Number = ((this.nViewerHeight/2) - this.mcImageLayer._y)/(this.pHeight * nMag);	
		
		var oSelectedLabel:Object = this.oLabelList.getSelectedLabel();
		if (oSelectedLabel) {
			var aTmp:Array = oSelectedLabel.getLocation();
			var aSize:Array;
			if (oSelectedLabel.getSize) {
				aSize = oSelectedLabel.getSize();
			} else {
				aSize = [0,0];
			}
			var nScaleX:Number = this.nViewerWidth/this.pWidth;
			var nScaleY:Number = this.nViewerHeight/this.pHeight;
			trace(aSize);
			nCx = (aTmp[0]/100) + ((aSize[0]/200));
			nCy = (aTmp[1]/100) + ((aSize[1]/200));
		}
		
		if (nCx < .1) { nCx = 0.1; }
		if (nCx > .9) { nCx = 0.9; }
		if (nCy < .1) { nCy = 0.1; }
		if (nCy > .9) { nCy = 0.9; }
		
		this.zoomToScale(1, nCx, nCy);
	}
	// ---------------------------------------------------------------
	function getGeometry() {
		if (isNaN(this.pScales)) {
			return 0;
		}
		if (this.pScales<1) {
			return 0;
		}
		// generate scale (layer) list
		var aGeometry:Array = new Array();
		var nW:Number = this.pWidth*this.pRatio;
		var nH:Number = this.pHeight*this.pRatio;
		var i:Number;
		for (i=this.pScales; i>0; i--) {
			nW = Math.ceil(nW/this.pRatio);
			nH = Math.ceil(nH/this.pRatio);
			aGeometry[i] = new Array();
			aGeometry[i]["width"] = nW;
			aGeometry[i]["height"] = nH;
		}
		var nStartTile:Number = 1;
		var wTiles:Number;
		var hTiles:Number;
		var nTiles:Number;
		for (i=1; i<=this.pScales; i++) {
			wTiles = Math.ceil(aGeometry[i]["width"]/this.pTileWidth);
			hTiles = Math.ceil(aGeometry[i]["height"]/this.pTileHeight);
			nTiles = Math.ceil(wTiles*hTiles);
			aGeometry[i]["tiles"] = nTiles;
			aGeometry[i]["start"] = nStartTile;
			aGeometry[i]["tileWidth"] = wTiles;
			aGeometry[i]["tileHeight"] = hTiles;
			if (this._debug) {
				trace("Layer "+i);
				trace("Number of tiles: "+nTiles);
				trace("Start tile: "+nStartTile);
				trace("Tile dim: "+wTiles+" x "+hTiles);
				trace("Dim: "+aGeometry[i]["width"]+" x "+aGeometry[i]["height"]);
			}
			nStartTile += nTiles;
			
			if (nTiles < 30) {
				this.nBackgroundScale = i;			
			}
			if ((aGeometry[i]["width"] <= 300) && (aGeometry[i]["height"] <= 300)){
				this.nReferenceImageScale = i;
			}
		}
		return aGeometry;
	}
	// ---------------------------------------------------------------
	//
	// Draw image in the viewer
	//
	function drawImage(mcBuffer:MovieClip, pDisplayWidth:Number, pDisplayHeight:Number, pDrawAllTiles:Boolean) {
		// get info for scale
		var aScaleGeometry:Array = this.aGeometry[this.nCurScale];
		var nEffectiveTileWidth:Number = this.pTileWidth * (mcBuffer._xscale/100);
		var nEffectiveTileHeight:Number = this.pTileHeight * (mcBuffer._xscale/100);
		//
		// We want to draw only the tiles that are visible on the viewer. A quick calculation of:
		//
		// viewerWidth/EffectiveTileWidth (where EffectiveTileWidth is the tile width *after* scaling; eg. a 200x200 tile
		// at 150% has an effective size of 300x300) gives the number of tiles to be drawn if the 
		//
		
		var nOffsetX:Number;
		var nOffsetY:Number;
		if (mcBuffer._x<0) {
			nOffsetX = Math.floor(Math.abs(mcBuffer._x)/nEffectiveTileWidth);
		} else {
			nOffsetX = 0;
		}
		var nOffsetXFraction:Number = (Math.abs(mcBuffer._x)/nEffectiveTileWidth) - nOffsetX;
		
		if (mcBuffer._y<0) {
			nOffsetY = Math.floor(Math.abs(mcBuffer._y)/nEffectiveTileHeight);
		} else {
			nOffsetY = 0;
		} 
		var nOffsetYFraction:Number = (Math.abs(mcBuffer._y)/nEffectiveTileHeight) - nOffsetY;
		
		var nDrawTilesX:Number = Math.ceil(this.nViewerWidth/nEffectiveTileWidth);
		if (((nEffectiveTileWidth * nOffsetXFraction) + (nOffsetXFraction * nDrawTilesX)) < this.nViewerWidth) {
			nDrawTilesX++;
		}
		if (nDrawTilesX>aScaleGeometry["tileWidth"]) {
			nDrawTilesX = aScaleGeometry["tileWidth"];
		}
		
		var nDrawTilesY:Number = Math.ceil(this.nViewerHeight/nEffectiveTileHeight);
		if (((nEffectiveTileHeight * nOffsetYFraction) + (nOffsetYFraction * nDrawTilesY)) < this.nViewerHeight) {
			nDrawTilesY++;
		}
		if (nDrawTilesY>aScaleGeometry["tileHeight"]) {
			nDrawTilesY = aScaleGeometry["tileHeight"];
		}
		var nX:Number = 0;
		var nY:Number = 0;
		// create movie clip container for current scale
		// if one does not exist already
		if (!mcBuffer["tile_buffer_"+this.nCurScale]) {
			var m = mcBuffer.createEmptyMovieClip("tile_buffer_"+this.nCurScale, this.nCurScale+100);
			m._visible = true;
		}
		var mcTileBuffer:MovieClip = mcBuffer["tile_buffer_"+this.nCurScale];
		
		if (this._debug) {
			trace("------------------------------------------");
			trace("first tile of scale is " + aScaleGeometry["start"]);
			trace("offset is " + nOffsetX + " x " + nOffsetY);
			trace("Offset fractions are x=" + nOffsetXFraction + "; y=" + nOffsetYFraction);
			trace("Raw offsets are x=" + (Math.abs(mcBuffer._x/nEffectiveTileWidth)) + "; y="+ (Math.abs(mcBuffer._y/nEffectiveTileHeight)));
			trace("display dimensions are " + pDisplayWidth + " x " +pDisplayHeight);
			trace("tile dimensions are " + this.pTileWidth + " x " +this.pTileHeight);
			trace("effective tile dimensions are " + nEffectiveTileWidth + " x " +nEffectiveTileHeight);
			trace("draw tiles is " + nDrawTilesX + " x " + nDrawTilesY);
			trace("geo is " + aScaleGeometry["tileWidth"] + " x " + aScaleGeometry["tileHeight"]);
		}
		
		var nColCount:Number = 0;
		var nRowCount:Number = nOffsetY;
		var nColsDrawn:Number = 0;
		var nRowsDrawn:Number = 0;
		var nStartTile:Number = aScaleGeometry["start"]+(nOffsetY*aScaleGeometry["tileWidth"]);
		var nEndTile:Number = nStartTile+(nDrawTilesY*aScaleGeometry["tileWidth"]);
		if (nEndTile>(aScaleGeometry["start"]+aScaleGeometry["tiles"])) {
			nEndTile = aScaleGeometry["start"]+aScaleGeometry["tiles"];
		}
		
		if (this._debug) {
			trace("start tile is " + nStartTile + "; end tile is " + nEndTile);
		}
		
		nY = nOffsetY*this.pTileHeight;
		
		if (this._debug) {
			trace("------------------------------------------");
			trace("START="+nStartTile+"; END="+nEndTile);
		}
		
		var nTileNum:Number;
		for (nTileNum=nStartTile; nTileNum<nEndTile; nTileNum++) {
			if ((nColCount>=nOffsetX) && (nColCount<(nOffsetX+nDrawTilesX)) && (nColCount<aScaleGeometry["tileWidth"])) {
				nX = nColCount*this.pTileWidth;
				if (!this.isCached(mcBuffer, nTileNum-1)) {
					var aTileInfo = new Array();
					aTileInfo["tilenum"] = nTileNum;
					aTileInfo["mcBuffer"] = mcTileBuffer;
					aTileInfo["x"] = nX;
					aTileInfo["y"] = nY;
					if (pDrawAllTiles == true) {
						this.aPriorityTilesToLoadList.push(aTileInfo);
					} else {
						this.aTilesToLoadList.push(aTileInfo);
					}
				}
				nColsDrawn++;
				nColCount++;
			} else {
				if ((nColCount>=(nDrawTilesX+nOffsetX)) || (nColCount>aScaleGeometry["tileWidth"])) {
					nColCount = 0;
					nRowCount++;
					nRowsDrawn++;
					
					nTileNum = aScaleGeometry["start"]+(nRowCount*aScaleGeometry["tileWidth"])-1;
					nY = nRowCount*this.pTileHeight;
				} else {
					nColCount++;
				}
			}
		}
		if (this._debug) {
			trace("------------------------------------------");
		}
		return 1;
	}
	// ---------------------------------------------------------------
	function updateViewer(mcBuffer:MovieClip, pRegistrationX:Number, pRegistrationY:Number, pScale:Number) {
		aImageDimensions = this.getImageDimensions();
		if ((this.getImageURL() == '') || (aImageDimensions[0]<=0) || (aImageDimensions[1]<=0)) {
			this.mcImageViewer.tError._visible = true;
			this.mcImageViewer.tError.text = "No image specified";
			return 0;
		}
		
		if (this._debug) {
			trace("Cur mag is " + this.nMagnification);
		}
		var nDisplayWidth:Number = (this.pWidth*this.nMagnification);
		var nDisplayHeight:Number = (this.pHeight*this.nMagnification);
		
		if ((nDisplayWidth<1) || (nDisplayHeight<1)) {
			return 0;
		}
		// transform registration point (in global coordinates) to local coordinates
		var m:Object = new Object();
		m.x = pRegistrationX;
		m.y = pRegistrationY;
		mcBuffer.globalToLocal(m);
		
		// Get scale to display
		var nNewScale:Number;
		if ((pScale == undefined) || (pScale<1) || (pScale>this.pScales)) {
			// get "default" scale to show entire image on screen
			nNewScale = this.getOptimalScale(nDisplayWidth, nDisplayHeight);
		} else {
			nNewScale = pScale;
		}
		if ((nNewScale<1) || (nNewScale>(this.aGeometry.length-1))) {
			trace("Invalid scale: "+nNewScale);
			return 0;
		}
		
		// Is this a new scale?
		var bScaleChanged:Boolean = false;
		var nOldScale:Number;
		if (this.nCurScale !== nNewScale) {
			nOldScale = this.nCurScale;
			this.nCurScale = nNewScale;
			bScaleChanged = true;
			if (this._debug) {
				trace("SCALE CHANGED FROM "+nOldScale+" to "+nNewScale);
			}
			// cleanup stray tiles
			if (nOldScale != undefined) {
				mcBuffer["tile_buffer_"+nOldScale]._visible = false;
			}
			mcBuffer["tile_buffer_"+nNewScale]._visible = true;
			this.aTilesToLoadList = new Array();
		}
		
		// --- Note old scale values
		var nScaleX0:Number = mcBuffer._xscale;
		var nScaleY0:Number = mcBuffer._yscale;
		
		// --- Get initial scale factor
		var aScaleGeometry:Array = this.aGeometry[nNewScale];
		var nScaleX:Number = nDisplayWidth/aScaleGeometry["width"];
		var nScaleY:Number = nDisplayHeight/aScaleGeometry["height"];
		
		//
		// The following two blocks of code set the scale of the background and background-background's to
		// match the scale of the image viewer display.
		//
		
		// --- Set up background scale
		var aBackgroundScaleGeometry:Array = this.aGeometry[this.nBackgroundScale];
		var nBackgroundScaleX:Number = aScaleGeometry["width"]/aBackgroundScaleGeometry["width"];
		var nBackgroundScaleY:Number = aScaleGeometry["height"]/aBackgroundScaleGeometry["height"];
		this.mcBackground._xscale = Math.ceil(nBackgroundScaleX*100);
		this.mcBackground._yscale = Math.ceil(nBackgroundScaleY*100);
				
		// --- Set up background-background scale
		var aBackgroundBackgroundScaleGeometry:Array = this.aGeometry[1];
		var nBackgroundBackgroundScaleX:Number = aScaleGeometry["width"]/aBackgroundBackgroundScaleGeometry["width"];
		var nBackgroundBackgroundScaleY:Number = aScaleGeometry["height"]/aBackgroundBackgroundScaleGeometry["height"];
		this.mcBackgroundBackground._xscale = Math.ceil(nBackgroundBackgroundScaleX*100);
		this.mcBackgroundBackground._yscale = Math.ceil(nBackgroundBackgroundScaleY*100);
		
		// --- Set new scale factor
		mcBuffer._xscale = this.mcLabelLayer._xscale = (nScaleX*100);
		mcBuffer._yscale = this.mcLabelLayer._yscale = (nScaleY*100);
		
		var nDScaleX:Number;
		var nDScaleY:Number;
		
		if (bScaleChanged) {
			//
			var aOldScaleGeometry:Array = this.aGeometry[nOldScale];
			var nOldScaleX:Number = nDisplayWidth/aOldScaleGeometry["width"];
			var nOldScaleY:Number = nDisplayHeight/aOldScaleGeometry["height"];
			// Get change in scale
			nDScaleX = ((nOldScaleX*100)-nScaleX0)/100.0;
			nDScaleY = ((nOldScaleY*100)-nScaleY0)/100.0;
		} else {
			// Get change in scale
			nDScaleX = ((nScaleX*100)-nScaleX0)/100.0;
			nDScaleY = ((nScaleY*100)-nScaleY0)/100.0;
		}
		// Get offset of registration point due to scaling
		var nDX:Number = m.x*nDScaleX;
		var nDY:Number = m.y*nDScaleY;
		// Shift picture container to account for shift of registration point due to scaling
		mcBuffer._x -= nDX;
		mcBuffer._y -= nDY;
		if (pRegistrationX == undefined) {
			if ((aScaleGeometry["width"]*nScaleX)<this.nViewerWidth) {
				mcBuffer._x = Math.ceil(((this.nViewerWidth-(aScaleGeometry["width"]*nScaleX))/2.0));
			} else {
				mcBuffer._x = 0;
			}
		}
		if (pRegistrationY == undefined) {
			if ((aScaleGeometry["height"]*nScaleY)<this.nViewerHeight) {
				mcBuffer._y = Math.ceil(((this.nViewerHeight-(aScaleGeometry["height"]*nScaleY))/2.0));
			} else {
				mcBuffer._y = 0;
			}
		}
		// Don't allow image to be moved completely off screen
		if (mcBuffer._x>=(this.nViewerWidth)) {
			mcBuffer._x = Math.ceil(this.nViewerWidth-nDisplayWidth);
		}
		if (mcBuffer._x<=(-1*nDisplayWidth)) {
			mcBuffer._x = 0;
		}
		if (mcBuffer._y>=(this.nViewerHeight)) {
			mcBuffer._y = Math.ceil(this.nViewerHeight-nDisplayHeight);
		}
		if (mcBuffer._y<=(-1*nDisplayHeight)) {
			mcBuffer._y = 0;
		}
		
		this.mcLabelLayer._x = mcBuffer._x;
		this.mcLabelLayer._y = mcBuffer._y;
		
		this.mcToolbar.tMagnification.text = Math.ceil(this.getMagnification()*100)+"%";
		this.drawImage(mcBuffer, nDisplayWidth, nDisplayHeight);
		this.drawReferencePaletteHighlight();
	}
	// ---------------------------------------------------------------
	function loadViewerBackground() {
		aImageDimensions = this.getImageDimensions();
		if ((this.getImageURL() == '') || (aImageDimensions[0]<=0) || (aImageDimensions[1]<=0)) {
			this.mcImageViewer.tError._visible = true;
			this.mcImageViewer.tError.text = "No image specified";
			return 0;
		}
		var aFullGeometry:Array = this.aGeometry[this.aGeometry.length-1];
		this.nCurScale = 1;
		this.drawImage(this.mcBackgroundBackground, aFullGeometry["width"], aFullGeometry["height"], true);
		this.nCurScale = this.nBackgroundScale;
		this.drawImage(this.mcBackground, aFullGeometry["width"], aFullGeometry["height"], true);
		this.updateTiles();
	}
	// ---------------------------------------------------------------
	//
	// Get scale of image to use at a given magnification (ie. display width and height)
	//
	function getOptimalScale(nWidth:Number, nHeight:Number) {
		var i:Number;
		for (i=1; i<this.aGeometry.length; i++) {
			if ((this.aGeometry[i]["width"]>=nWidth) && (this.aGeometry[i]["height"]>=nHeight)) {
				return i;
			}
		}
		return this.aGeometry.length-1;
	}
	// ---------------------------------------------------------------
	//
	// Tool bar
	//
	function showToolbar() {
		this.mcToolbar._visible = true;
	}
	// ---------------------------------------------------------------
	function hideToolbar() {
		this.mcToolbar._visible = false;
	}
	// ---------------------------------------------------------------
	function toolbarIsVisible() {
		return this.mcToolbar._visible;
	}
	// ---------------------------------------------------------------
	//
	// Reference image palette
	//
	function showReferencePalette(bSaveChange:Boolean) { 
		if (bSaveChange == undefined) { bSaveChange = true; }
		
		var oSharedObject:SharedObject;
		if (bSaveChange) {
			oSharedObject = SharedObject.getLocal("bischen");
			oSharedObject.data["referenceImagePaletteVisible"] = true;
			oSharedObject.flush();
		}
		if (!this.mcReferenceImagePalette) {
			this.mcReferenceImagePalette = this.mcViewerContainer.attachMovie("mcBischenReferenceImagePalette", "mcBischenReferenceImagePalette", 48010); 
			this.mcReferenceImagePalette._x = 10;
			this.mcReferenceImagePalette._y = 170;
			
			var mcBackground:MovieClip = this.mcReferenceImagePalette.createEmptyMovieClip("mcBackground", -100);
			var mcReferenceImage:MovieClip = this.mcReferenceImagePalette.createEmptyMovieClip("mcReferenceImage", 10);
			var mcReferenceImageHighlight:MovieClip = this.mcReferenceImagePalette.createEmptyMovieClip("mcReferenceImageHighlight", 20);
			
			this.mcReferenceImagePalette.oViewer = this;
			mcBackground.onPress = function() {
				this._parent.startDrag(false,0,0,this._parent.oViewer.nViewerWidth - this._width, this._parent.oViewer.nViewerHeight - this._height);
			}
			mcBackground.onRelease = mcBackground.onReleaseOutside = function() {
				this._parent.stopDrag();
				
				var oSharedObject2:SharedObject = SharedObject.getLocal("bischen");
				
				oSharedObject2.data["referenceImagePaletteLocX"] = this._parent._x;
				oSharedObject2.data["referenceImagePaletteLocY"] = this._parent._y;
				oSharedObject2.flush();
			}
			var nCurScale:Number = this.nCurScale;
			this.nCurScale = this.nReferenceImageScale;
			this.drawImage(this.mcReferenceImagePalette.mcReferenceImage, this.aGeometry[this.nReferenceImageScale]["width"], this.aGeometry[this.nReferenceImageScale]["height"], true);
		
			this.nCurScale = nCurScale;
			
			
			this.mcReferenceImagePalette.mcReferenceImage._x = 5;
			this.mcReferenceImagePalette.mcReferenceImage._y = 20;
			this.mcReferenceImagePalette.mcReferenceImageHighlight._x = 5;
			this.mcReferenceImagePalette.mcReferenceImageHighlight._y = 20;
			
			this.mcReferenceImagePalette.mcReferenceImage.onPress = function() {
				this.onEnterFrame = function() {
					var oViewer:Object = this._parent._parent.oViewer;
					var aInfo:Array = oViewer.aGeometry[oViewer.nCurScale];
					var nMag:Number = oViewer.mcImageLayer._xscale / 100;
					
					var nVisiblePW:Number = (oViewer.nViewerWidth/(aInfo["width"] * nMag));
					if (nVisiblePW > 1) { nVisiblePW = 1; }
					var nVisiblePH:Number = (oViewer.nViewerHeight/(aInfo["height"] * nMag));
					if (nVisiblePH > 1) { nVisiblePH = 1; }
					
					var nRefMapWidth:Number = oViewer.aGeometry[oViewer.nReferenceImageScale]["width"];
					var nRefMapHeight:Number = oViewer.aGeometry[oViewer.nReferenceImageScale]["height"];
					
					var nVisibleW:Number = nVisiblePW * nRefMapWidth;
					var nVisibleH:Number = nVisiblePH * nRefMapHeight;
					
					var nMouseX:Number = this._xmouse - (nVisibleW/2);
					var nMouseY:Number = this._ymouse - (nVisibleH/2);
					
					oViewer.mcLabelLayer._x = oViewer.mcImageLayer._x = nRefMapWidth/2 - (((nMouseX/nRefMapWidth) * aInfo["width"]) * nMag);
					if (oViewer.mcImageLayer._x + oViewer.mcImageLayer._width < oViewer.nViewerWidth ) { 
						oViewer.mcLabelLayer._x = oViewer.mcImageLayer._x = oViewer.nViewerWidth - oViewer.mcImageLayer._width; 
					}
					if (oViewer.mcImageLayer._x > 0) {
						oViewer.mcLabelLayer._x = oViewer.mcImageLayer._x = 0;
					}
					
					oViewer.mcLabelLayer._y = oViewer.mcImageLayer._y = nRefMapHeight/2 - (((nMouseY/nRefMapHeight) * aInfo["height"]) * nMag);
					if (oViewer.mcImageLayer._y + oViewer.mcImageLayer._height < oViewer.nViewerHeight ) { 
						oViewer.mcLabelLayer._y = oViewer.mcImageLayer._y = oViewer.nViewerHeight - oViewer.mcImageLayer._height; 
					}
					
					if (oViewer.mcImageLayer._y > 0) {
						oViewer.mcLabelLayer._y = oViewer.mcImageLayer._y = 0;
					}
				
					if (!oViewer.drawReferencePaletteHighlight()) {
						this.onEnterFrame = null;
					}
				}
			}
			this.mcReferenceImagePalette.mcReferenceImage.onRelease = this.mcReferenceImagePalette.mcReferenceImage.onReleaseOutside = function() {
				this.onEnterFrame = undefined;
				
				var oViewer:Object = this._parent._parent.oViewer;
				oViewer.updateViewer(oViewer.mcImageLayer,0,0);
			}
			
			this.mcReferenceImagePalette.createTextField("tStatus", 60000, 5, 0, 75, 20);
			var tfStatus:TextFormat = new TextFormat();
			tfStatus.size = 10;
			tfStatus.font = "Verdana";
			tfStatus.bold = true;
			mcReferenceImagePalette.tStatus.selectable = false;
			
			this.mcReferenceImagePalette.tStatus.setNewTextFormat(tfStatus);
			this.mcReferenceImagePalette.tStatus.text = "Navigator";
			
			var nPaletteWidth:Number = this.aGeometry[this.nReferenceImageScale]["width"];
			if (nPaletteWidth < 100) { nPaletteWidth = 100; }		// palette cannot be less than 100 pixels (otherwise it looks stupid and the "navigator" text label runs outside the palette outline)
			var nPaletteHeight:Number = this.aGeometry[this.nReferenceImageScale]["height"];
			
			mcBackground.moveTo(0,0);
			mcBackground.beginFill(0xCCCCCC);
			mcBackground.lineStyle(1, 0x000000);
			mcBackground.lineTo(0,0);
			mcBackground.lineTo((nPaletteWidth + 10),0);
			mcBackground.lineTo((nPaletteWidth + 10),(nPaletteHeight + 25));
			mcBackground.lineTo(0,(nPaletteHeight + 25));
			mcBackground.lineTo(0,0);
			mcBackground.endFill();
			
			var mcCloseWindow:MovieClip = this.mcReferenceImagePalette.attachMovie("iBischenCloseWindow", "mcCloseWindow", 50);
			
			mcCloseWindow._x = nPaletteWidth;
			mcCloseWindow._y = 10;
			mcCloseWindow.oViewer = this;
			mcCloseWindow.onPress = function() {
				this.oViewer.hideReferencePalette();
			}
			
		}
		if (
			(oSharedObject.data["referenceImagePaletteLocX"] >= 0) && 
			(oSharedObject.data["referenceImagePaletteLocY"] >= 0) 
			&&
			(oSharedObject.data["referenceImagePaletteLocX"] <= (this.nViewerWidth - this.mcReferenceImagePalette._width)) && 
			(oSharedObject.data["referenceImagePaletteLocY"] <= (this.nViewerHeight - this.mcReferenceImagePalette._height))
		) {
			this.mcReferenceImagePalette._x = oSharedObject.data["referenceImagePaletteLocX"];
			this.mcReferenceImagePalette._y = oSharedObject.data["referenceImagePaletteLocY"];
		} else {
			this.mcReferenceImagePalette._x = this.nViewerWidth - this.mcReferenceImagePalette._width - 10;
			this.mcReferenceImagePalette._y = this.nViewerHeight - this.mcReferenceImagePalette._height - 10;
		}
		
		this.drawReferencePaletteHighlight();
		this.mcReferenceImagePalette._visible = true;
	}
	// ---------------------------------------------------------------
	function hideReferencePalette(bSaveChange:Boolean) {
		if (bSaveChange == undefined) { bSaveChange = true; }
		
		if (bSaveChange) {
			var oSharedObject:SharedObject = SharedObject.getLocal("bischen");
			oSharedObject.data["referenceImagePaletteVisible"] = false;
			oSharedObject.flush();
		}
		this.mcReferenceImagePalette._visible = false;
	}
	// ---------------------------------------------------------------
	public function referencePaletteIsVisible():Boolean {
		return this.mcReferenceImagePalette._visible;
	}
	// ---------------------------------------------------------------
	private function drawReferencePaletteHighlight() {
		var mcReferenceMap:MovieClip = this.mcReferenceImagePalette.mcReferenceImage;
		if (!mcReferenceMap) {
			return false;
		}
		var nMag:Number = this.mcImageLayer._xscale / 100;
		var nX:Number = this.mcImageLayer._x;
		var nY:Number = this.mcImageLayer._y;
		var aInfo:Array = this.aGeometry[this.nCurScale];
	
		var nVisiblePW:Number = (this.nViewerWidth/(aInfo["width"] * nMag));
		if (nVisiblePW > 1) { nVisiblePW = 1; }
		var nVisiblePH:Number = (this.nViewerHeight/(aInfo["height"] * nMag));
		if (nVisiblePH > 1) { nVisiblePH = 1; }
		
		var mcReferenceImageHighlight:MovieClip = this.mcReferenceImagePalette.mcReferenceImageHighlight;
		
		var nRefMapWidth:Number = this.aGeometry[this.nReferenceImageScale]["width"];
		var nRefMapHeight:Number = this.aGeometry[this.nReferenceImageScale]["height"];
		
		var nVisibleW:Number = nVisiblePW * nRefMapWidth;
		var nVisibleH:Number = nVisiblePH * nRefMapHeight;
		var nOffsetX:Number = ((nX * -1) / nMag) * (nRefMapWidth/aInfo["width"]);
		var nOffsetY:Number = ((nY * -1) / nMag) * (nRefMapHeight/aInfo["height"]);
		if (nOffsetX < 0) { nOffsetX = 0; }
		if (nOffsetY < 0) { nOffsetY = 0; }
		if (nOffsetX > nRefMapWidth) { nOffsetX = nRefMapWidth; }
		if (nOffsetY > nRefMapHeight) { nOffsetY = nRefMapHeight; }
		//nOffsetX += 5;
		//nOffsetY += 20;
		
		var nExtentX:Number = nOffsetX + nVisibleW;
		var nExtentY:Number = nOffsetY + nVisibleH;
		
		if (nExtentX > nRefMapWidth) { nExtentX = nRefMapWidth; }
		if (nExtentY > nRefMapHeight) { nExtentY = nRefMapHeight; }
		
		mcReferenceImageHighlight.clear();
		mcReferenceImageHighlight.lineStyle(1, 0xcc0000, 100);
		mcReferenceImageHighlight.moveTo(nOffsetX,nOffsetY);
		mcReferenceImageHighlight.lineTo(nExtentX, nOffsetY);
		mcReferenceImageHighlight.lineTo(nExtentX, nExtentY);
		mcReferenceImageHighlight.lineTo(nOffsetX,nExtentY);
		mcReferenceImageHighlight.lineTo(nOffsetX,nOffsetY);
		
		return true;
	}
	
	// ---------------------------------------------------------------
	//
	// Label palette
	//
	function showLabelPalette(bSaveChange:Boolean) { 
		if (bSaveChange == undefined) { bSaveChange = true; }
		
		var oSharedObject:SharedObject;
		if (bSaveChange) {
			oSharedObject = SharedObject.getLocal("bischen");
			oSharedObject.data["labelPaletteVisible"] = true;
			oSharedObject.flush();
		}
		if (!this.mcLabelPalette) {
			this.mcLabelPalette = this.mcViewerContainer.attachMovie("mcBischenLabelPalette", "mcBischenLabelPalette", 48050); 
			this.mcLabelPalette._x = 10;
			this.mcLabelPalette._y = 120;
			
			var mcBackground:MovieClip = this.mcLabelPalette.createEmptyMovieClip("mcBackground", -100);
			this.mcLabelPalette.oViewer = this;
			
			mcBackground.onPress = function() {
				this._parent.startDrag(false,0,0,this._parent.oViewer.nViewerWidth - this._width, this._parent.oViewer.nViewerHeight - this._height);
			}
			mcBackground.onRelease = mcBackground.onReleaseOutside = function() {
				this._parent.stopDrag();
				
				var oSharedObject2:SharedObject = SharedObject.getLocal("bischen");
				
				oSharedObject2.data["labelPaletteLocX"] = this._parent._x;
				oSharedObject2.data["labelPaletteLocY"] = this._parent._y;
				oSharedObject2.flush();
			}
			
			
			//this.mcLabelPalette.mcLabelPalette.onPress = function() {
			//	
			//}
			
			this.mcLabelPalette.createTextField("tStatus", 60000, 5, 0, 75, 20);
			var tfStatus:TextFormat = new TextFormat();
			tfStatus.size = 10;
			tfStatus.font = "Verdana";
			tfStatus.bold = true;
			mcLabelPalette.tStatus.selectable = false;
			
			this.mcLabelPalette.tStatus.setNewTextFormat(tfStatus);
			this.mcLabelPalette.tStatus.text = "Labels";
			
			mcBackground.moveTo(0,0);
			mcBackground.beginFill(0xCCCCCC);
			mcBackground.lineStyle(1, 0x000000);
			mcBackground.lineTo(0,0);
			mcBackground.lineTo(85,0);
			mcBackground.lineTo(85,40);
			mcBackground.lineTo(0,40);
			mcBackground.lineTo(0,0);
			mcBackground.endFill();
			
			var mcCloseWindow:MovieClip = this.mcLabelPalette.attachMovie("iBischenCloseWindow", "mcCloseWindow", 50);
			
			mcCloseWindow._x = 75;
			mcCloseWindow._y = 10;
			mcCloseWindow.oViewer = this;
			mcCloseWindow.onPress = function() {
				this.oViewer.hideLabelPalette();
			}
			
			// draw buttons
			
			var bLabelsVisible:Boolean = this.oLabelList.labelsVisible();
			
			var mcBischenLabelsOn:MovieClip = mcLabelPalette.attachMovie("iBischenLabelsOn", "mcBischenLabelsOn", 10);
			var mcBischenLabelsOff:MovieClip = mcLabelPalette.attachMovie("iBischenLabelsOff", "mcBischenLabelsOff", 15);
			
			mcBischenLabelsOn._x = mcBischenLabelsOff._x = 5;
			mcBischenLabelsOn._y = mcBischenLabelsOff._y = 20;
			mcBischenLabelsOn._xscale = mcBischenLabelsOn._yscale = 150;
			mcBischenLabelsOff._xscale = mcBischenLabelsOff._yscale = 150;
			
			
			mcBischenLabelsOn._visible = bLabelsVisible;
			mcBischenLabelsOff._visible = !bLabelsVisible;
			mcBischenLabelsOn.onRelease = function() {
				this._visible = false;
				this._parent.mcBischenLabelsOff._visible = true;
				this._parent.oViewer.oLabelList.hideLabels();
			}
			mcBischenLabelsOff.onRelease = function() {
				this._visible = false;
				this._parent.mcBischenLabelsOn._visible = true;
				this._parent.oViewer.oLabelList.showLabels();
			}
			
			if (this.editingLabels()) {
				var mcBischenLabelsLocked:MovieClip = mcLabelPalette.attachMovie("iBischenLabelsLocked", "mcBischenLabelsLocked", 20);
				var mcBischenLabelsUnlocked:MovieClip = mcLabelPalette.attachMovie("iBischenLabelsUnlocked", "mcBischenLabelsUnlocked", 25);
				
				mcBischenLabelsLocked._x = mcBischenLabelsUnlocked._x = 35;
				mcBischenLabelsLocked._y = mcBischenLabelsUnlocked._y = 20;
				mcBischenLabelsLocked._xscale = mcBischenLabelsLocked._yscale = 150;
				mcBischenLabelsUnlocked._xscale = mcBischenLabelsUnlocked._yscale = 150;
				
				
				mcBischenLabelsLocked._visible = false;
				mcBischenLabelsUnlocked._visible = true;
				mcBischenLabelsLocked.onRelease = function() {
					this._visible = false;
					this._parent.mcBischenLabelsUnlocked._visible = true;
					this._parent.oViewer.oLabelList.locked(false);
				}
				mcBischenLabelsUnlocked.onRelease = function() {
					this._visible = false;
					this._parent.mcBischenLabelsLocked._visible = true;
					this._parent.oViewer.oLabelList.locked(true);
				}
				
				var mcBischenAddLabel:MovieClip = mcLabelPalette.attachMovie("iBischenAddLabel", "mcBischenAddLabel", 30);
				mcBischenAddLabel._x = 58;
				mcBischenAddLabel._y = 20;
				mcBischenAddLabel._xscale = 150;
				mcBischenAddLabel._yscale = 150;
				
				mcBischenAddLabel.oViewer = this;
				mcBischenAddLabel.onRelease = function() {
					var oLabelList = this.oViewer.getLabelList();
					oLabelList.addLabel(this.oViewer.getLabelType());
					this.oViewer.drawLabels();
				}
			}
			
			if (
				(oSharedObject.data["labelPaletteLocX"] >= 0) && 
				(oSharedObject.data["labelPaletteLocY"] >= 0) 
				&&
				(oSharedObject.data["labelPaletteLocX"] <= (this.nViewerWidth - this.mcLabelPalette._width)) && 
				(oSharedObject.data["labelPaletteLocY"] <= (this.nViewerHeight - this.mcLabelPalette._height))
			) {
				this.mcLabelPalette._x = oSharedObject.data["labelPaletteLocX"];
				this.mcLabelPalette._y = oSharedObject.data["labelPaletteLocY"];
			} else {
				var nReferencePaletteOffset:Number = this.mcReferenceImagePalette._height;
				if (nReferencePaletteOffset == undefined) { nReferencePaletteOffset = 0; }
				if (nReferencePaletteOffset > 0) { nReferencePaletteOffset += 10; }
				this.mcLabelPalette._x = this.nViewerWidth - this.mcLabelPalette._width - 10;
				this.mcLabelPalette._y = this.nViewerHeight - this.mcLabelPalette._height - nReferencePaletteOffset - 10;
			}
		}
		this.mcLabelPalette._visible = true;
	}
	// ---------------------------------------------------------------
	function hideLabelPalette(bSaveChange:Boolean) {
		if (bSaveChange == undefined) { bSaveChange = true; }
		
		if (bSaveChange) {
			var oSharedObject:SharedObject = SharedObject.getLocal("bischen");
			oSharedObject.data["labelPaletteVisible"] = false;
			oSharedObject.flush();
		}
		this.mcLabelPalette._visible = false;
	}
	// ---------------------------------------------------------------
	public function labelPaletteIsVisible():Boolean {
		return this.mcLabelPalette._visible;
	}
	// --------------------------------------------------------------------------------------------------
	//
	// Label methods
	//
	function loadLabels() {
		if (!this.usingLabels()) {
			return false;
		}
		
		// user-defined parameters
		var vsParameterList:String = this.getParameterList();
		var vaParameterList:Array = vsParameterList.split(";");
		var i:Number;
		var vaParameterTmp:Array = new Array();
		for (i=0; i<vaParameterList.length; i++) {
			if (this.getParameterValue(vaParameterList[i]) == undefined) {
				vaParameterTmp.push(vaParameterList[i]+"=");
			} else {
				vaParameterTmp.push(vaParameterList[i]+"="+escape(this.getParameterValue(vaParameterList[i])));
			}
		}
		var vsParameterStr = vaParameterTmp.join("&");
		
		// master list of labels to display in image viewer
		this.oLabelList = new bischen.LabelList(this.mcLabelLayer, undefined, this);
		
		this.oLabelList.setLabelDefaultTitle(this.getLabelDefaultTitle());
		this.oLabelList.setLabelTitleReadOnly(this.labelTitleReadOnly());
		trace("set label read-only " + this.labelTitleReadOnly());
		
		this.oLabelList.load(this.getLabelProcessorURL()+"?action=media_labels&service=list&"+vsParameterStr, this);
		trace("Label URL is " + this.getLabelProcessorURL()+"?action=media_labels&service=list&"+vsParameterStr);
		this.bLabelsLoaded = true;
		
		return 1;
	}
	// ---------------------------------------------------------------
	//
	// Draw labels on image viewer
	//
	function drawLabels() {
		if (this.bUseLabels == true) {
			if (!this.bLabelsLoaded) {
				this.loadLabels();
				return false;
			}
			if (!this.oLabelList.labelsVisible()) { return true; }
			
			var sw:Number = this.aGeometry[this.nCurScale]["width"];
			var sh:Number = this.aGeometry[this.nCurScale]["height"];
			var lScale:Number = this.mcImageLayer._xscale;
			var i:Number;
			this.oLabelList.draw(lScale, sw, sh,this.mcImageLayer._x, this.mcImageLayer._y);
		}
	}
	// ---------------------------------------------------------------
	function setLabelType(nTypecode:Number) {
		this.nLabelTypecode = nTypecode;
	}
	// ---------------------------------------------------------------
	function getLabelType(nTypecode:Number) {
		return this.nLabelTypecode;
	}
	// --------------------------------------------------------------------------------------------------
	//
	// Accessor methods
	//
	public function setImageDimensions(pWidth, pHeight, pScales, pRatio, pTileWidth, pTileHeight) {
		this.pWidth = Math.ceil(pWidth);
		this.pHeight = Math.ceil(pHeight);
		this.pScales = Math.ceil(pScales);
		this.pRatio = Math.ceil(pRatio);
		this.pTileWidth = Math.ceil(pTileWidth);
		this.pTileHeight = Math.ceil(pTileHeight);
		this.aGeometry = this.getGeometry();
		if (((this.nMagnification == undefined) && (this.nCurScale == undefined)) || ((!this.nMagnification) && (!this.nCurScale))) {
			var nScaleX:Number = this.nViewerWidth/this.pWidth;
			var nScaleY:Number = this.nViewerHeight/this.pHeight;
			if (nScaleX<nScaleY) {
				this.nMagnification = nScaleX;
			} else {
				this.nMagnification = nScaleY;
			}
			
		}
		this.mcToolbar.tMagnification.text = Math.ceil(this.getMagnification()*100)+"%";
	}
	// ---------------------------------------------------------------
	function getImageDimensions():Array {
		return [this.pWidth, this.pHeight, this.pScales, this.pRatio, this.pTileWidth, this.pTileHeight];
	}
	// ---------------------------------------------------------------
	function setMagnification(pMagnification:Number) {
		if (
			(
				((this.pWidth*pMagnification)>(0.01*this.nViewerWidth)) 
				&& 
				((this.pHeight*pMagnification)>(0.01*this.nViewerHeight))
			)
		) {
			this.nMagnification = pMagnification;
		}
	}
	// ---------------------------------------------------------------
	function getMagnification() {
		return this.nMagnification;
	}
	// ---------------------------------------------------------------
	function setMagnificationIncrement(pIncrement:Number) {
		if ((pIncrement<0.15) && (pIncrement>0)) {
			this.nMagnificationIncrement = pIncrement;
		}
	}
	// ---------------------------------------------------------------
	function getMagnificationIncrement():Number {
		return this.nMagnificationIncrement;
	}
	// ---------------------------------------------------------------
	function getDefaultMagnificationIncrement():Number {
		return this.nDefaultMagnificationIncrement;
	}
	// ---------------------------------------------------------------
	function setImageURL(url:String) {
		this.pImageURL = url;
	}
	// ---------------------------------------------------------------
	function getImageURL() {
		return this.pImageURL;
	}
	// ---------------------------------------------------------------
	function setViewerURL(url:String) {
		this.pViewerURL = url;
	}
	// ---------------------------------------------------------------
	function getViewerURL():String {
		return this.pViewerURL;
	}
	// ---------------------------------------------------------------
	function setLabelProcessorURL(url:String) {
		this.pLabelProcessorURL = url;
	}
	// ---------------------------------------------------------------
	function getLabelProcessorURL():String {
		return this.pLabelProcessorURL;
	}
	// ---------------------------------------------------------------
	function setParameterList(pList:String) {
		this.pList = pList;
	}
	// ---------------------------------------------------------------
	function getParameterList() {
		return this.pList;
	}
	// ---------------------------------------------------------------
	function setParameterValue(sParam:String, sValue:String) {
		this.oParameterValues[sParam] = sValue;
	}
	// ---------------------------------------------------------------
	function getParameterValue(sParam:String) {
		return this.oParameterValues[sParam];
	}
	// ---------------------------------------------------------------
	function getParameterValues() {
		return this.oParameterValues;
	}
	// ---------------------------------------------------------------
	function setAntialiasingOnMove(bSetting:Boolean) {
		this.bAntialiasingWhileMoving = bSetting;
	}
	// ---------------------------------------------------------------
	function getAntialiasingOnMove() {
		return this.bAntialiasingWhileMoving;
	}
	// ---------------------------------------------------------------
	function setUseLabels(state:Boolean) {
		this.bUseLabels = state;
	}
	// ---------------------------------------------------------------
	function usingLabels():Boolean {
		return this.bUseLabels;
	}
	// ---------------------------------------------------------------
	function setEditLabels(state:Boolean) {
		this.bEditLabels = state;
	}
	// ---------------------------------------------------------------
	function editingLabels():Boolean {
		return this.bEditLabels;
	}
	// ---------------------------------------------------------------
	function setUseScale(state:Boolean) {
		this.bUseScale = state;
	}
	// ---------------------------------------------------------------
	function usingScale():Boolean {
		return this.bUseScale;
	}
	// ---------------------------------------------------------------
	function setEditScale(state:Boolean) {
		this.bEditScale = state;
	}
	// ---------------------------------------------------------------
	function editingScale():Boolean {
		return this.bEditScale;
	}
	// ---------------------------------------------------------------
	function getLabelList() {
		return this.oLabelList;
	}
	// ---------------------------------------------------------------
	function setLabelDefaultTitle(sTitle:String) {
		return this.bLabelDefaultTitle = sTitle;
	}
	// ---------------------------------------------------------------
	function getLabelDefaultTitle():String {
		return this.bLabelDefaultTitle;
	}
	// ---------------------------------------------------------------
	function setLabelTitleReadOnly(bSetting:Boolean) {
		this.bLabelTitleReadOnly = bSetting;
	}
	// ---------------------------------------------------------------
	function labelTitleReadOnly():Boolean {
		return this.bLabelTitleReadOnly;
	}
	// ---------------------------------------------------------------
	function useKeyboard(bUseKeyboard:Boolean) {
		if (bUseKeyboard != undefined) {
			this.bUseKeyboard = bUseKeyboard;
		}
		return this.bUseKeyboard;
	}
	// ---------------------------------------------------------------

	// --------------------------------------------------------------------------------------------------
	//
	// Tile cache functions
	//
	function setCache(sName:String, nTileNum:Number, mInstanceRef:MovieClip) {
		if (this.aTileCache[sName] == undefined) {
			this.aTileCache[sName] = new Array();
		}
		this.aTileCache[sName][nTileNum] = mInstanceRef;
		return 1;
	}
	// ---------------------------------------------------------------
	function isCached(sName:MovieClip, nTileNum:Number) {
		if (this.aTileCache[sName][nTileNum] !== undefined) {
			if (this.aTileCache[sName][nTileNum].getBytesLoaded() == this.aTileCache[sName][nTileNum].getBytesTotal()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// ---------------------------------------------------------------
	function getCache(sName:String, nTileNum:Number) {
		if (this.aTileCache[sName][nTileNum] !== undefined) {
			return this.aTileCache[sName][nTileNum];
		} else {
			return undefined;
		}
	}
	// ---------------------------------------------------------------
	function setStatusMessage(sMessage:String) {
		this.mcToolbar.tStatus.text = sMessage;
		return true;
	}
	// ---------------------------------------------------------------
	function updateTiles() {
		var bExit:Boolean = false;
		
		while ((this.nTilesLoading < 4) && (!bExit)) {
			if ((this.aTilesToLoadList.length>0) || (this.aPriorityTilesToLoadList.length>0)) {
				this.setStatusMessage("Loading"); // set "loading" display status
				var aTileInfo;
				if (this.aPriorityTilesToLoadList.length>0) {
					aTileInfo = this.aPriorityTilesToLoadList.shift();
				} else {
					aTileInfo = this.aTilesToLoadList.pop();
				}
				if (!(this.aTilesToLoadDict[aTileInfo["mcBuffer"]+"_"+aTileInfo["tilenum"]])) {
					var mcTile:MovieClip = aTileInfo["mcBuffer"].createEmptyMovieClip("tile_"+aTileInfo["tilenum"]+"_mc", aTileInfo["tilenum"]+100);
					this.setCache(aTileInfo["mcBuffer"], aTileInfo["tilenum"], mcTile);
					this.aTilesToLoadDict[aTileInfo["mcBuffer"]+"_"+aTileInfo["tilenum"]] = 1;
					mcTile._x = aTileInfo["x"];
					mcTile._y = aTileInfo["y"];
					
					var nTileNumMarkerIndex:Number;
					if ((!this.pViewerURL) && ((nTileNumMarkerIndex = this.pImageURL.indexOf("*")) != -1)) {
						var sTileUrl = this.pImageURL.substr(0,nTileNumMarkerIndex) + aTileInfo["tilenum"] + this.pImageURL.substr(nTileNumMarkerIndex + 1);
						
						this.mclTileLoader.loadClip(sTileUrl, mcTile);
					} else {
						//trace("Load tile: " + this.pViewerURL+"?p="+this.pImageURL+"&t="+aTileInfo["tilenum"]);
						this.mclTileLoader.loadClip(this.pViewerURL+"?p="+this.pImageURL+"&t="+aTileInfo["tilenum"], mcTile);
					}
					this.nTilesLoading++;
				}
			} else {
				bExit = true;
				this.setStatusMessage(""); // stop "loading" status display
			}
		}
	}
	// --------------------------------------------------------------------------------------------------
	//
	// Utility functions
	//
	function findInArray(needle, haystack:Array) {
		var i:Number;
		for (i=0; i<haystack.length; i++) {
			if (haystack[i] == needle) {
				return 1;
			}
		}
		return 0;
	}
	// --------------------------------------------------------------------------------------------------
}
