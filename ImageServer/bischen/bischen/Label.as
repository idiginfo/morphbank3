// ----------------------------------------------
// Label class
// ----------------------------------------------
// Implements labels for attachment to an
// ImageViewer object
// ----------------------------------------------
class bischen.Label {
	private var nLabelID:Number;
	private var nLabeIIndex:Number;
	private var mcLabel:MovieClip;
	private var bLabelHasChanged:Boolean = false;
	
	private var nX:Number;	// x location (percentage of image width)
	private var nY:Number;	// y location (percentage of image height)
	
	private var oLabelList:bischen.LabelList;
	
	private var bIsSelected:Boolean = false;
	// ---------------------------------------------------------------------------------------------
	function Label(oLabelList:bischen.LabelList, nLabelID:Number, mcParent:MovieClip, nDepth:Number, sTitle:String, sContent:String, bIsSelected:Boolean) {
		
	}
	// ---------------------------------------------------------------------------------------------
	function layout() {
		trace("MUST OVERRIDE layout() METHOD IN A bischen.Label subclass!");
	}
	// ---------------------------------------------------------------------------------------------
	function draw(nImageScale:Number, nSw:Number, nSh:Number, bIsSelected:Boolean) {
		trace("MUST OVERRIDE draw() METHOD IN A bischen.Label subclass!");
	} 
	// ---------------------------------------------------------------------------------------------
	// -- Accessors
	// ---------------------------------------------------------------------------------------------
	function isVisible(v:Boolean):Boolean {
		if (v != undefined) { this.mcLabel._visible = v;}
		return this.mcLabel._visible;
	}
	// ---------------------------------------------------------------------------------------------
	function id():Number {
		return this.nLabelID;
	}
	// ---------------------------------------------------------------------------------------------
	function getLabelMC():MovieClip {
		return this.mcLabel;
	}
	// ---------------------------------------------------------------------------------------------
	function setLabelIndex(nIndex:Number) {
		this.nLabeIIndex = nIndex;
	}
	// ---------------------------------------------------------------------------------------------
	function getLabelIndex():Number {
		return this.nLabeIIndex;
	}
	// ---------------------------------------------------------------------------------------------
	function setLabelHasChanged() {
		this.bLabelHasChanged = true;
	}
	// ---------------------------------------------------------------------------------------------
	function clearLabelHasChanged() {
		this.bLabelHasChanged =false;
	}
	// ---------------------------------------------------------------------------------------------
	function labelHasChanged() {
		return this.bLabelHasChanged;
	}
	// ---------------------------------------------------------------------------------------------
	// location is in resolution independent coordinates (percentage width and height of image)
	function setLocation(nX:Number, nY:Number) {
		this.nX = nX;
		this.nY = nY;
	}
	// ---------------------------------------------------------------------------------------------
	function getLocation():Array {
		return [new Number(this.nX), new Number(this.nY)];
	}
	// ---------------------------------------------------------------------------------------------
	function setStatusMessage(sMessage:String):Boolean {
		return this.oLabelList.oViewer.setStatusMessage(sMessage);
	}
	// ---------------------------------------------------------------------------------------------
	// -- Save label to database
	// ---------------------------------------------------------------------------------------------
	function save() {
		trace("MUST OVERRIDE save() METHOD IN A bischen.Label subclass!");
	}
	// ---------------------------------------------------------------------------------------------
}
