<?php
# ----------------------------------------------------------------------
#
# TilepicParser module.  Copyright 2004-2006 Whirl-i-Gig (http://www.whirl-i-gig.com)
#
# Disclaimer:  There are no doubt inefficiencies and bugs in this code; the
# documentation leaves much to be desired. If you'd like to improve these  
# libraries please consider helping us develop this software. 
#
# phpweblib is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.
#
# This source code are free and modifiable under the terms of 
# GNU Lesser General Public License. (http://www.gnu.org/copyleft/lesser.html)
#
# ----------------------------------------------------------------------
# class TilepicParser
# 
# Can return metadata and convert existing Tilepic files to any format supported by ImageMagick.
# Can encode formats listed in $mimetype2magick (well, any format supported by ImageMagick actually)
# to Tilepic format with tiles encoded at (you guessed it) any format in $mimetype2magick.
#
# NOTE: This module requires that either the MagickWand, imagick or GD graphics library be compiled into PHP. 
# If none is available, this module will not run! The default library is MagickWand. If you want to
# use GD you must explicitly specify this via the use() method
#
# Created on    18 February 2004
# Last update	23 August 2006
#
# Author	seth@whirl-i-gig.com
# ----------------------------------------------------------------------

define("LIBRARY_IMAGICK",1);
define("LIBRARY_GD", 2);
define("LIBRARY_MAGICKWAND",3);

class TilepicParser {
	var $error = "";
	var $properties = array();
	var $fh = "";
	
	#
	# Supported tile types
	#
	var $mimetype2magick = array(
			"image/gif" 	=> "GIF",
			"image/jpeg"	=> "JPEG",
			"image/png"		=> "PNG",
			"image/tiff"	=> "TIFF"
	);
	var $mimetype2ext = array(
			"image/gif" 	=> "gif",
			"image/jpeg"	=> "jpg",
			"image/png"		=> "png",
			"image/tiff"	=> "tiff"
	);
	
	var $debug = 0;
	
	var $backend = LIBRARY_MAGICKWAND;
	
	# ------------------------------------------------------------------------------------
	function TilepicParser($filename="") {
		if ($filename) { $this->load($filename); }
	}
	# ------------------------------------------------------------------------------------
	function init () {
		$this->error = "";
		$this->properties = array();
		
		if ($this->fh) { fclose($this->fh); }
		$this->fh = "";
		
		return 1;
	}
	# ------------------------------------------------------------------------------------
	function useLibrary($vn_backend) {
		switch($vn_backend) {
			case LIBRARY_GD:
				$vn_backend = LIBRARY_GD;
				break;
			case LIBRARY_IMAGICK:
				$vn_backend = LIBRARY_IMAGICK;
				break;
			default:
				$vn_backend = LIBRARY_MAGICKWAND;
				break;
		}
	}
	# ------------------------------------------------------------------------------------
	#
	# Tilepic loader (must call this before accessing file
	#
	# ------------------------------------------------------------------------------------
	function isTilepic($filepath) {
		if ($fh = @fopen($filepath,'r')) {
			// --------
			// -- Read header
			// --------
			$header = fread ($fh, 4);
			fclose($fh);
			if (preg_match("/TPC\n/", $header)) {
				# the first four characters of a Tilepic file are TPN\n
				return "image/tilepic";
			} else {
				return "";
			}
		} else {
			# file format is not supported by this plug-in
			return "";
		}
	}
	# ------------------------------------------------------------------------------------
	#
	# Tilepic loader (must call this before accessing file)
	#
	# ------------------------------------------------------------------------------------
	function load ($filepath) {
		$this->init();
		
		if (is_array($filepath)) {
			$this->properties = $filepath;
			return 1;
		}
		
		//$t=& new Timer();
		if ($this->fh = $fh = @fopen($filepath,'r')) {
			# look for signature
			$sig = fread ($fh, 4);
			if (preg_match("/TPC\n/", $sig)) {
				$buf = fread($fh, 4);
				$x = unpack("Nheader_size", $buf);
				if ($x['header_size'] <= 8) { 
					$this->error = "Tilepic header length is invalid";
					return 0;
				}
				$this->properties['filepath'] = $filepath;
				
				$this->properties['header_size'] = $x['header_size'];
				
				$header = fread($fh, $x['header_size'] - 8);
				$header_values = unpack("Nwidth/Nheight/Ntile_width/Ntile_height/Ntiles/nlayers/nratio/Nattributes_size/Nattributes", $header);
				
				# --- Check header values
				if (($header_values["width"] < 1) || ($header_values["height"] < 1) || ($header_values["tile_width"] < 1) || ($header_values["tile_height"] < 1) ||
					($header_values["tiles"] < 1) || ($header_values["layers"] < 1)) {
					$this->error = "Tilepic header is invalid";
					return 0;
				}
				
				foreach (array_keys($header_values) as $k) {
					$this->properties[$k] = $header_values[$k];
				}
				
				# --- get tile offsets (start of each tile)
				$tile_offsets = array();
				for ($i=0; $i < $header_values['tiles']; $i++) {
					$x = unpack("Noffset", fread($fh, 4)); 
					$tile_offsets[] = $x['offset'];
				}
				$this->properties['tile_offsets'] = $tile_offsets;
				
				# --- get attribute data
				$buf = fread($fh, 4);
				$x = unpack("Nattribute_offset", $buf);
				$this->properties['attribute_offset'] = $attribute_offset = $x['attribute_offset'];
				if (fseek($fh, $attribute_offset, 0) == -1) {
					$this->error = "Seek error while fetch attributes";
					return 0;
				}
				
				$attribute_data = fread($fh, filesize($filepath) - $attribute_offset);
				
				$attribute_list = explode("\0", $attribute_data);
				$attributes = array();
				foreach ($attribute_list as $attr) {
					if ($attr = trim($attr)) {
						$x = explode("=", $attr);
						$attributes[$x[0]] = $x[1];
						
						if (preg_match("/^mimetype\$/i", $x[0])) {
							$this->properties["tile_mimetype"] = $x[1];
						}
					}
				}
				$this->properties["attributes"] = $attributes;
				
				//error_log("Tilepic load took " . $t->getTime()." seconds");
				
				return 1;
			} else {
				$this->error = "File is not Tilepic format";
				return 0;
			}
		} else {
			$this->error = "Couldn't open file $filepath";
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function getProperties() {
		return $this->properties;
	}
	# ------------------------------------------------------------------------------------
	#
	# Tilepic creation methods
	#
	# ------------------------------------------------------------------------------------
	function encode ($filepath, $output_path, $options) {
		#
		# Default values for options
		#
		if (($options["layer_ratio"] = (isset($options["layer_ratio"])) ? $options["layer_ratio"] : 2) <= 0) { $options["layer_ratio"] = 2; }
		
		if (($options["scale_factor"] = (isset($options["scale_factor"])) ? $options["scale_factor"] : 1) <= 0) { $options["scale_factor"] = 1; }
		
		if (($options["quality"] = (isset($options["quality"])) ? $options["quality"] : 75) < 1) { $options["quality"] = 75; }
		if (isset($options["layers"])) {
			if (($options["layers"] < 1) || ($options["layers"] > 100)) {
				$options["layers"] = 6;
			}
		}
		
		if (($options["antialiasing"] = (isset($options["antialiasing"])) ? $options["antialiasing"] : 1) < 0) { $options["antialiasing"] = 1; }
		
		
		if (isset($options["tile_size"])) {
			$options["tile_width"] = $options["tile_height"] = $options["tile_size"];
		}
		
		if (($options["tile_width"] < 10) || ($options["tile_height"] < 10)) {
			$options["tile_width"] = $options["tile_height"] = 256;
		}
		
		if (($options["layers"] < 1) || ($options["layers"] > 25)) {
			$options["layers"] = 0;
		}
		
		if (!$options["output_mimetype"]) {
			$options["output_mimetype"] = "image/jpeg";
		}
		
		switch($this->backend) {
			case LIBRARY_GD:
				return $this->encode_gd($filepath, $output_path, $options);
				break;
			case LIBRARY_IMAGICK:
				return $this->encode_imagick($filepath, $output_path, $options);
				break;
			default:
				return $this->encode_magickwand($filepath, $output_path, $options);
				break;
		}
	}
	# ------------------------------------------------------------------------------------
	function encode_imagick ($filepath, $output_path, $options) {
		
		if (!($magick = $this->mimetype2magick[$options["output_mimetype"]])) {
			$this->error = "Invalid output format";
			return 0;
		}
		
		#
		# Open image
		#
		$h = imagick_readimage($filepath);
        if (imagick_iserror($h)) {
			$reason = imagick_failedreason($h);
			$description = imagick_faileddescription($h);
			$this->error = "Couldn't open image $filepath: $reason; $description";
			return 0;
        }
        $image_width = imagick_getwidth($h);
        $image_height = imagick_getheight($h);
        if (($image_width < 10) || ($image_height < 10)) {
        	$this->error = "Image is too small to be output as Tilepic; minimum dimensions are 10x10 pixels";
			return 0;
        }
        
        if ($options["scale_factor"] != 1) {
        	$image_width *= $options["scale_factor"];
        	$image_height *= $options["scale_factor"];
			
			if (!imagick_resize( $h, $image_width, $image_height, IMAGICK_FILTER_CUBIC, $options["antialiasing"],"")) {
				$reason      = imagick_failedreason($h);
				$description = imagick_faileddescription($h);
				$this->error = "Couldn't scale image: $reason; $description";
				return 0;
			}
        }
        
		#
		# How many layers to make?
		#
		if (!$options["layers"]) {
			$sw = $image_width * $options["layer_ratio"];
			$sh = $image_height * $options["layer_ratio"];
			$options["layers"] = 1;
			while (($sw >= $options["tile_width"]) || ($sh >= $options["tile_height"])) {
				$sw = ceil($sw / $options["layer_ratio"]);
				$sh = ceil($sh / $options["layer_ratio"]);
				$options["layers"] ++;
			}
		}
		
		#
		# Cut image into tiles
		#
		$tiles = 0;
		$layer_list = array();
		$base_width = $image_width;
		$base_height = $image_height;
		
		if ($this->debug) { print "BASE $base_width x $base_height \n";}
		for($l=$options["layers"]; $l >= 1; $l--) {
			$x = $y = 0;
			$wx = $options["tile_width"];
			$wy = $options["tile_height"];
			
			if ($this->debug) { print "LAYER=$l\n"; };
			if ($l < $options["layers"]) {
				$image_width = ceil($image_width/$options["layer_ratio"]);
				$image_height = ceil($image_height/$options["layer_ratio"]);
				if ($this->debug) { print "RESIZE layer $l TO $image_width x $image_height \n";}
				if (!imagick_resize( $h, $image_width, $image_height, IMAGICK_FILTER_CUBIC, $options["antialiasing"],"")) {
					$reason      = imagick_failedreason($h);
					$description = imagick_faileddescription($h);
					$this->error = "Couldn't scale image: $reason; $description";
					return 0;
				}
			}
		
			$i = 0;
			
			$layer_list[] = array();
			while($y < $image_height) {
				$slice = imagick_clonehandle($h);
				if ( !imagick_crop($slice, $x, $y, $wx, $wy)) {
					$reason      = imagick_failedreason( $slice ) ;
					$description = imagick_faileddescription( $slice ) ;
					$this->error = "Couldn't create tile: $reason; $description";
					return 0;
				}
				
				
				imagick_setcompressiontype($slice, IMAGICK_COMPRESSION_JPEG);
				imagick_setcompressionquality($slice, $options["quality"]);
				
				if (!imagick_convert($slice, $magick)) {
					$reason      = imagick_failedreason( $slice ) ;
					$description = imagick_faileddescription( $slice ) ;
					$this->error = "Tile conversion failed: $reason; $description";
					return 0;
				}
				
				# --- remove color profile (saves lots of space)
				imagick_profile($slice, "*", IMAGICK_PROFILE_OWN);
				$layer_list[sizeof($layer_list)-1][] = imagick_image2blob($slice);
				imagick_destroyhandle($slice);
				$x += $options["tile_width"];
				
				if ($x >= $image_width) {
					$y += $options["tile_height"];
					$x = 0;
				}
				$i++;
				$tiles++;
			}
			if ($this->debug) { print "OUTPUT $tiles TILES FOR LAYER $l : $image_width x $image_height\n";}
		}
		imagick_destroyhandle($h);
		
		#
		# Write Tilepic format file
		#
		if ($this->debug) { print "WRITING FILE..."; }
		if ($fh = fopen($output_path, "w")) {
			# --- attribute list
			$attribute_list = "";
			$attributes = 0;
			
			if ((isset($options["attributes"])) && (is_array($options["attributes"]))) {
				$options["attributes"]["mimeType"] = $options["output_mimetype"];
			} else {
				$options["attributes"] = array("mimeType" => $options["output_mimetype"]);
			}
			foreach ($options["attributes"] as $k => $v) {
				$attribute_list .= "$k=$v\0";
				$attributes++;
			}
			
			if ($this->debug) { print "header OK;"; }
			# --- header
			if (!fwrite($fh, "TPC\n")) {
				$this->error = "Could not write Tilepic signature";
				return 0;
			}
			if (!fwrite($fh, pack("NNNNNNnnNN",40, $base_width, $base_height, $options["tile_width"], $options["tile_height"], $tiles, $options["layers"], $options["layer_ratio"], strlen($attribute_list),$attributes))) {
				$this->error = "Could not write Tilepic header";
				return 0;
			}
		
			# --- offset table
			$offset = 44 + ($tiles * 4);
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, pack("N",$offset))) {
						$this->error = "Could not write Tilepic offset table";
						return 0;
					}
					$offset += strlen($layer_list[$i][$j]);
				}   
			}
			if ($this->debug) { print "offset table OK;"; }
			
			if (!fwrite($fh, pack("N", $offset))) {
				$this->error = "Could not finish writing Tilepic offset table";
				return 0;
			}
			
			# --- tiles
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, $layer_list[$i][$j])) {
						$this->error = "Could not write Tilepic tile data";
						return 0;
					}
				}   
			}
			if ($this->debug) { print "tiles OK;"; }
			unset($layer_list);
			# --- attributes
			if (!fwrite($fh, $attribute_list)) {
				$this->error = "Could not write Tilepic attributes";
				return 0;
			}
			if ($this->debug) { print "attributes OK\n"; }
			fclose($fh);
			
			return $options;
		} else {
			$this->error = "Couldn't open output file $output_path\n";
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function encode_magickwand ($filepath, $output_path, $options) {
		
		if (!($magick = $this->mimetype2magick[$options["output_mimetype"]])) {
			$this->error = "Invalid output format";
			return 0;
		}
		
		#
		# Open image
		#
		$h = NewMagickWand();
		MagickReadImage($h, $filepath);
        if (WandHasException($h)) {
			$reason = 		WandGetExceptionType($h);
			$description = 	WandGetExceptionString($h);
			$this->error = "Couldn't open image $filepath: $reason; $description";
			return 0;
        }
        $image_width = 	MagickGetImageWidth($h);
        $image_height = MagickGetImageHeight($h);
        if (($image_width < 10) || ($image_height < 10)) {
        	$this->error = "Image is too small to be output as Tilepic; minimum dimensions are 10x10 pixels";
			return 0;
        }
        
        if ($options["scale_factor"] != 1) {
        	$image_width *= $options["scale_factor"];
        	$image_height *= $options["scale_factor"];
			
			if (!MagickResizeImage( $h, $image_width, $image_height, MW_CubicFilter, $options["antialiasing"])) {
				$reason      = WandGetExceptionType( $this->handle ) ;
				$description = WandGetExceptionString( $this->handle ) ;
				$this->error = "Couldn't scale image: $reason; $description";
				return 0;
			}
        }
        
		#
		# How many layers to make?
		#
		if (!$options["layers"]) {
			$sw = $image_width * $options["layer_ratio"];
			$sh = $image_height * $options["layer_ratio"];
			$options["layers"] = 1;
			while (($sw >= $options["tile_width"]) || ($sh >= $options["tile_height"])) {
				$sw = ceil($sw / $options["layer_ratio"]);
				$sh = ceil($sh / $options["layer_ratio"]);
				$options["layers"] ++;
			}
		}
		
		#
		# Cut image into tiles
		#
		$tiles = 0;
		$layer_list = array();
		$base_width = $image_width;
		$base_height = $image_height;
		
		if ($this->debug) { print "BASE $base_width x $base_height \n";}
		for($l=$options["layers"]; $l >= 1; $l--) {
			$x = $y = 0;
			$wx = $options["tile_width"];
			$wy = $options["tile_height"];
			
			if ($this->debug) { print "LAYER=$l\n"; };
			if ($l < $options["layers"]) {
				$image_width = ceil($image_width/$options["layer_ratio"]);
				$image_height = ceil($image_height/$options["layer_ratio"]);
				if ($this->debug) { print "RESIZE layer $l TO $image_width x $image_height \n";}
				if (!MagickResizeImage( $h, $image_width, $image_height, MW_CubicFilter, $options["antialiasing"])) {
					$reason      = WandGetExceptionType( $this->handle ) ;
					$description = WandGetExceptionString( $this->handle ) ;
					$this->error = "Couldn't scale image: $reason; $description";
					return 0;
				}
			}
		
			$i = 0;
			
			$layer_list[] = array();
			while($y < $image_height) {
				$slice = CloneMagickWand($h);
				if ( !MagickCropImage($slice, $wx, $wy, $x, $y)) {
					$reason      = WandGetExceptionType( $slice ) ;
					$description = WandGetExceptionDescription( $slice ) ;
					$this->error = "Couldn't create tile: $reason; $description";
					return 0;
				}
				
				
				MagickSetCompressionQuality($slice, $options["quality"]);
				
				if (!MagickSetImageFormat($slice, $magick)) {
					$reason      = WandGetExceptionType( $slice ) ;
					$description = WandGetExceptionDescription( $slice ) ;
					$this->error = "Tile conversion failed: $reason; $description";
					return 0;
				}
				
				# --- remove color profile (saves lots of space)
				MagickRemoveImageProfiles($slice);
				$layer_list[sizeof($layer_list)-1][] = MagickGetImageBlob($slice);
				DestroyMagickWand($slice);
				$x += $options["tile_width"];
				
				if ($x >= $image_width) {
					$y += $options["tile_height"];
					$x = 0;
				}
				$i++;
				$tiles++;
			}
			if ($this->debug) { print "OUTPUT $tiles TILES FOR LAYER $l : $image_width x $image_height\n";}
		}
		DestroyMagickWand($h);
		
		#
		# Write Tilepic format file
		#
		if ($this->debug) { print "WRITING FILE..."; }
		if ($fh = fopen($output_path, "w")) {
			# --- attribute list
			$attribute_list = "";
			$attributes = 0;
			
			if ((isset($options["attributes"])) && (is_array($options["attributes"]))) {
				$options["attributes"]["mimeType"] = $options["output_mimetype"];
			} else {
				$options["attributes"] = array("mimeType" => $options["output_mimetype"]);
			}
			foreach ($options["attributes"] as $k => $v) {
				$attribute_list .= "$k=$v\0";
				$attributes++;
			}
			
			if ($this->debug) { print "header OK;"; }
			# --- header
			if (!fwrite($fh, "TPC\n")) {
				$this->error = "Could not write Tilepic signature";
				return 0;
			}
			if (!fwrite($fh, pack("NNNNNNnnNN",40, $base_width, $base_height, $options["tile_width"], $options["tile_height"], $tiles, $options["layers"], $options["layer_ratio"], strlen($attribute_list),$attributes))) {
				$this->error = "Could not write Tilepic header";
				return 0;
			}
		
			# --- offset table
			$offset = 44 + ($tiles * 4);
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, pack("N",$offset))) {
						$this->error = "Could not write Tilepic offset table";
						return 0;
					}
					$offset += strlen($layer_list[$i][$j]);
				}   
			}
			if ($this->debug) { print "offset table OK;"; }
			
			if (!fwrite($fh, pack("N", $offset))) {
				$this->error = "Could not finish writing Tilepic offset table";
				return 0;
			}
			
			# --- tiles
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, $layer_list[$i][$j])) {
						$this->error = "Could not write Tilepic tile data";
						return 0;
					}
				}   
			}
			if ($this->debug) { print "tiles OK;"; }
			unset($layer_list);
			# --- attributes
			if (!fwrite($fh, $attribute_list)) {
				$this->error = "Could not write Tilepic attributes";
				return 0;
			}
			if ($this->debug) { print "attributes OK\n"; }
			fclose($fh);
			
			return $options;
		} else {
			$this->error = "Couldn't open output file $output_path\n";
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function encode_gd ($filepath, $output_path, $options) {
		
		if (!($magick = $this->mimetype2magick[$options["output_mimetype"]])) {
			$this->error = "Invalid output format";
			return 0;
		}
		
		#
		# Open image
		#
		if($va_info = getimagesize($filepath)) {
			switch($va_info[2]) {
				case IMAGETYPE_GIF:
					$r_image = imagecreatefromgif($filepath);
					$vs_mimetype = "image/gif";
					$vs_typename = "GIF";
					break;
				case IMAGETYPE_JPEG:
					$r_image = imagecreatefromjpeg($filepath);
					$vs_mimetype = "image/jpeg";
					$vs_typename = "JPEG";
					break;
				case IMAGETYPE_PNG:
					$r_image = imagecreatefrompng($filepath);
					$vs_mimetype = "image/png";
					$vs_typename = "PNG";
					break;
			}
			if (!$r_image) {
				$this->error = "Couldn't open image $filepath: open for $vs_typename failed";
				return 0;
			}
		} else {
			$this->error = "Couldn't open image $filepath: unsupported file type";
			return 0;
		}
        $image_width = $va_info[0];
        $image_height = $va_info[1];
        if (($image_width < 10) || ($image_height < 10)) {
        	$this->error = "Image is too small to be output as Tilepic; minimum dimensions are 10x10 pixels";
			return 0;
        }
        
        if ($options["scale_factor"] != 1) {
        	$image_width *= $options["scale_factor"];
        	$image_height *= $options["scale_factor"];
			
			$r_new_image = imagecreatetruecolor($image_width, $image_height);
			$r_color = ImageColorAllocate( $r_new_image, 255, 255, 255 );
			imagefilledrectangle($r_new_image, 0,0,$image_width-1, $image_height-1, $r_color);
			if (imagecopyresampled($r_new_image, $r_image, 0, 0, 0, 0, $image_width, $image_height, $va_info[0], $va_info[1])) {
				$this->error = "Couldn't scale image for new layer";
				return 0;
			}
			imagedestroy($r_image);
			$r_image = $r_new_image;
        }
        
		#
		# How many layers to make?
		#
		if (!$options["layers"]) {
			$sw = $image_width * $options["layer_ratio"];
			$sh = $image_height * $options["layer_ratio"];
			$options["layers"] = 1;
			while (($sw >= $options["tile_width"]) || ($sh >= $options["tile_height"])) {
				$sw = ceil($sw / $options["layer_ratio"]);
				$sh = ceil($sh / $options["layer_ratio"]);
				$options["layers"] ++;
			}
		}
		
		#
		# Cut image into tiles
		#
		$tiles = 0;
		$layer_list = array();
		$base_width = $image_width;
		$base_height = $image_height;
		
		for($l=$options["layers"]; $l >= 1; $l--) {
			$x = $y = 0;
			$wx = $options["tile_width"];
			$wy = $options["tile_height"];
			
			if ($l < $options["layers"]) {
				$old_image_width = $image_width;
				$old_image_height = $image_height;
				$image_width = ceil($image_width/$options["layer_ratio"]);
				$image_height = ceil($image_height/$options["layer_ratio"]);
				
				$r_new_image = imagecreatetruecolor($image_width, $image_height);
				$r_color = ImageColorAllocate( $r_new_image, 255, 255, 255 );
				imagefilledrectangle($r_new_image, 0,0,$image_width-1, $image_height-1, $r_color);
				if (!imagecopyresampled($r_new_image, $r_image, 0, 0, 0, 0, $image_width, $image_height, $old_image_width, $old_image_height)) {
					$this->error = "Couldn't scale image for layer $l";
					return 0;
				}
				imagedestroy($r_image);
				$r_image = $r_new_image;
			}
		
			$i = 0;
			//$slices = array();
			$layer_list[] = array();
			while($y < $image_height) {
				$r_slice = imagecreatetruecolor($wx, $wy);
				$r_color = ImageColorAllocate( $r_slice, 255, 255, 255 );
				imagefilledrectangle($r_slice, 0,0,$wx-1, $wy-1, $r_color);
				if(!imagecopy($r_slice, $r_image,0,0,$x,$y,$wx, $wy)) {
					$this->error = "Couldn't create tile in level $l";
					return 0;
				}
				
				ob_start();
				switch($options["output_mimetype"]) {
					case 'image/gif':
						imagegif($r_slice);
						break;
					case 'image/jpeg':
						if ($options["quality"] > 0) {
							imagejpeg($r_slice, '', $options["quality"]);
						} else {
							imagejpeg($r_slice);
						}
						break;
					case 'image/png':
						imagepng($r_slice);
						break;
					default:
						die("Invalid output format ".$options["output_mimetype"]);
				}
				$vs_image = ob_get_clean();
				
				$layer_list[sizeof($layer_list)-1][] = $vs_image;
				imagedestroy($r_slice);
				$x += $options["tile_width"];
				
				if ($x >= $image_width) {
					$y += $options["tile_height"];
					$x = 0;
				}
				$i++;
				$tiles++;
			}
		}
		imagedestroy($r_image);
		
		#
		# Write Tilepic format file
		#
		if ($fh = fopen($output_path, "w")) {
			# --- attribute list
			$attribute_list = "";
			$attributes = 0;
			
			if ((isset($options["attributes"])) && (is_array($options["attributes"]))) {
				$options["attributes"]["mimeType"] = $options["output_mimetype"];
			} else {
				$options["attributes"] = array("mimeType" => $options["output_mimetype"]);
			}
			foreach ($options["attributes"] as $k => $v) {
				$attribute_list .= "$k=$v\0";
				$attributes++;
			}
			
			# --- header
			if (!fwrite($fh, "TPC\n")) {
				$this->error = "Could not write Tilepic signature";
				return 0;
			}
			if (!fwrite($fh, pack("NNNNNNnnNN",40, $base_width, $base_height, $options["tile_width"], $options["tile_height"], $tiles, $options["layers"], $options["layer_ratio"], strlen($attribute_list),$attributes))) {
				$this->error = "Could not write Tilepic header";
				return 0;
			}
		
			# --- offset table
			$offset = 44 + ($tiles * 4);
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, pack("N",$offset))) {
						$this->error = "Could not write Tilepic offset table";
						return 0;
					}
					$offset += strlen($layer_list[$i][$j]);
				}   
			}
			
			if (!fwrite($fh, pack("N", $offset))) {
				$this->error = "Could not finish writing Tilepic offset table";
				return 0;
			}
			
			# --- tiles
			for($i=sizeof($layer_list)-1; $i >= 0; $i--) {
				for($j=0; $j<sizeof($layer_list[$i]);$j++) {
					if (!fwrite($fh, $layer_list[$i][$j])) {
						$this->error = "Could not write Tilepic tile data";
						return 0;
					}
				}   
			}
			unset($layer_list);
			# --- attributes
			if (!fwrite($fh, $attribute_list)) {
				$this->error = "Could not write Tilepic attributes";
				return 0;
			}
			fclose($fh);
			
			return $options;
		} else {
			$this->error = "Couldn't open output file $output_path\n";
			return 0;
		}
	}
	
	# ------------------------------------------------------------------------------------
	#
	# Tilepic file access methods
	#
	# ------------------------------------------------------------------------------------
	function getTile($tile_number) {
		# --- Tile numbers start at 1, *NOT* 0 in parameter!
		$tile_number--; # internally, tiles are numbered from zero, so adjust here
		
		if (!$this->properties["filepath"]) {
			$this->error = "No file loaded";
			return 0;
		}
		if (($this->fh) || ($this->fh = fopen($this->properties["filepath"], "r"))) {
			if ($offset = $this->properties["tile_offsets"][$tile_number]) {
				if (!($next_offset = $this->properties["tile_offsets"][$tile_number + 1])) {
					if (!($next_offset = $this->properties["attribute_offset"])) {
						$this->error = "Couldn't find end of tile [".$this->properties["attribute_offset"]."]";
						return 0;
					}
				}
				
				if (fseek($this->fh, $offset, 0) == -1) {
					$this->error = "Could not seek to requested tile";
					return 0;
				}
				
				return fread($this->fh, $next_offset - $offset);
			} else {
				$this->error = "Invalid tile number '$tile_number'";
				return 0;
			}
		} else {
			$this->error = "Couldn't open file ".$this->properties["filepath"];
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function getTileQuickly($ps_filepath, $pn_tile_number) {
		# --- Tile numbers start at 1, *NOT* 0 in parameter!
		if ($fh = @fopen($ps_filepath,'r')) {
			# look for signature
			$sig = fread ($fh, 4);
			if (preg_match("/TPC\n/", $sig)) {
				$buf = fread($fh, 4);
				$x = unpack("Nheader_size", $buf);
				if ($x['header_size'] <= 8) { 
					$this->error = "Tilepic header length is invalid";
					return 0;
				}
				
				# --- get tile offsets (start of each tile)
				if (!fseek($fh, ($x['header_size']) + (($pn_tile_number - 1) * 4))) {
					$x = unpack("Noffset", fread($fh, 4)); 
					$y = unpack("Noffset", fread($fh, 4)); 
					$vn_len = $y["offset"] - $x["offset"];
					
					if (!fseek($fh, $x["offset"])) {
						echo fread($fh, $vn_len);
						return true;
					} else {
						$this->error = "File seek error while getting tile";
						return 0;
					}
				} else {
					$this->error = "File seek error while getting tile offset";
					return 0;
				}
			} else {
				$this->error = "File is not Tilepic format";
				return 0;
			}
		} else {
			$this->error = "Couldn't open file $ps_filepath";
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function writeTiles($ps_dirpath, $ps_filestem="") {
		# --- get tile offsets (start of each tile)
		if (($this->fh) || ($this->fh = fopen($this->properties["filepath"], "r"))) {
			$vs_ext = $this->mimetype2ext[$this->properties["tile_mimetype"]];
			
			foreach($this->properties["tile_offsets"] as $vn_tile_num => $vn_offset) {
				if (fseek($this->fh, $vn_offset, 0) == -1) {
					$this->error = "Could not seek to requested tile";
					return 0;
				}
				if (!($vn_next_offset = $this->properties["tile_offsets"][$vn_tile_num + 1])) {
					if (!($vn_next_offset = $this->properties["attribute_offset"])) {
						$this->error = "Couldn't find end of tile [".$this->properties["attribute_offset"]."]";
						return 0;
					}
				}
				if ($r_fh = fopen($ps_dirpath."/".$ps_filestem.($vn_tile_num+1).".".$vs_ext,"w+")) {
					fwrite($r_fh, fread($this->fh, $vn_next_offset - $vn_offset));
					fclose($r_fh);
				} else {
					$this->error = "Couldn't write tile to ".$ps_dirpath;
					return 0;
				}
			}
		} else {
			$this->error = "Couldn't open file ".$this->properties["filepath"];
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function getLayer($layer_number, $output_mimetype = "image/jpeg") {
		if (!($magick = $this->mimetype2magick[$output_mimetype])) {
			$this->error = "Invalid output format";
			return 0;
		}
		if (($layer_number > 0) && ($layer_number <= $this->properties['layers'])) {
			#
			# --- assemble tiles
			#
			switch($this->backend) {
				case LIBRARY_GD:
					$h = $this->getLayer_gd($layer_number, $output_mimetype);
					break;
				case LIBRARY_IMAGICK:
					$h = $this->getLayer_imagick($layer_number, $output_mimetype);
					break;
				default:
					$h = $this->getLayer_magickwand($layer_number, $output_mimetype);
					break;
			}
			return $h;
		} else {
			# --- layer does not exist
			$this->error = "Layer $layer_number does not exist";
			return 0;
		}
	}
	# ------------------------------------------------------------------------------------
	function getLayer_imagick($layer_number, $output_mimetype) {
		$layer_tiles = $this->getFileGeometry();
		if (!($tile_count = $layer_tiles[$layer_number]['tiles'])) {
			$this->error = "Invalid file";
			return 0;
		}
		$tile_start = 1;
		
		for ($l=1; $l<$layer_number; $l++) {
			$tile_start += $layer_tiles[$l]['tiles'];
		}
		
		$h = imagick_getcanvas("#ffffff", $layer_tiles[$layer_number]["width"], $layer_tiles[$layer_number]["height"]);
		if (imagick_iserror($h)) {
			$reason = imagick_failedreason( $h ) ;
			$description = imagick_faileddescription( $h ) ;
			$this->error = "Couldn't create new image: $reason; $description";
			return 0;
		}

		$tile_number = $tile_start;
		
		$tile_width = $this->properties['tile_width'];
		$tile_height = $this->properties['tile_height'];
		for($y=0; $y<$layer_tiles[$layer_number]['vertical_tiles']; $y++) {
			$cy = ($y*$tile_height);
			for($x=0; $x<$layer_tiles[$layer_number]['horizontal_tiles']; $x++) {
				$cx = ($x*$tile_width);
				$tile = $this->getTile($tile_number);
				if ($tile) { 
					$t = imagick_blob2image($tile);
					imagick_composite($h,IMAGICK_COMPOSITE_OP_OVER,$t,$cx,$cy);
					if (imagick_iserror($h)) {
						$reason = imagick_failedreason( $h ) ;
						$description = imagick_faileddescription( $h ) ;
						$this->error = "Couldn't add tile: $reason; $description";
						return 0;
					}
					imagick_destroyhandle($t);
				}
				$tile_number++;
			}
		}
		imagick_convert( $h, $magick ) ;
		if (imagick_iserror($h)) {
			$reason = imagick_failedreason( $h ) ;
			$description = imagick_faileddescription( $h ) ;
			$this->error = "Couldn't convert image to $output_mimetype ($magick): $reason; $description";
			return 0;
		}
		return $h;
	}
	
	# ------------------------------------------------------------------------------------
	function getLayer_magickwand($layer_number, $output_mimetype) {
		$layer_tiles = $this->getFileGeometry();
		if (!($tile_count = $layer_tiles[$layer_number]['tiles'])) {
			$this->error = "Invalid file";
			return 0;
		}
		$tile_start = 1;
		
		for ($l=1; $l<$layer_number; $l++) {
			$tile_start += $layer_tiles[$l]['tiles'];
		}
		
		$h = NewMagickWand();
		MagickNewImage($h, $layer_tiles[$layer_number]["width"], $layer_tiles[$layer_number]["height"], "#ffffff");
		if (WandHasException($h)) {
			$reason = 		WandGetExceptionType( $h ) ;
			$description = 	WandGetExceptionString( $h ) ;
			$this->error = "Couldn't create new image: $reason; $description";
			return 0;
		}

		$tile_number = $tile_start;
		
		$tile_width = $this->properties['tile_width'];
		$tile_height = $this->properties['tile_height'];
		for($y=0; $y<$layer_tiles[$layer_number]['vertical_tiles']; $y++) {
			$cy = ($y*$tile_height);
			for($x=0; $x<$layer_tiles[$layer_number]['horizontal_tiles']; $x++) {
				$cx = ($x*$tile_width);
				$tile = $this->getTile($tile_number);
				if ($tile) { 
					$t = NewMagickWand();
					MagickReadImageBlob($t, $tile);
					MagickCompositeImage($h, $t, MW_OverCompositeOp,$cx,$cy);
					if (WandHasException($h)) {
						$reason = 		WandGetExceptionType( $h ) ;
						$description = 	WandGetExceptionDescription( $h ) ;
						$this->error = "Couldn't add tile: $reason; $description";
						return 0;
					}
					DestroyMagickWand($t);
				}
				$tile_number++;
			}
		}
		MagickSetImageFormat( $h, $magick ) ;
		if (WandHasException($h)) {
			$reason = 		WandGetExceptionType( $h ) ;
			$description = 	WandGetExceptionString( $h ) ;
			$this->error = "Couldn't convert image to $output_mimetype ($magick): $reason; $description";
			return 0;
		}
		return $h;
	}
	# ------------------------------------------------------------------------------------
	function getLayer_gd($layer_number, $output_mimetype) {
		$layer_tiles = $this->getFileGeometry();
		if (!($tile_count = $layer_tiles[$layer_number]['tiles'])) {
			$this->error = "Invalid file";
			return 0;
		}
		$tile_start = 1;
		
		for ($l=1; $l<$layer_number; $l++) {
			$tile_start += $layer_tiles[$l]['tiles'];
		}
		
		$r_new_image = imagecreatetruecolor($layer_tiles[$layer_number]["width"], $layer_tiles[$layer_number]["height"]);
		if (!$r_new_image) {
			$this->error = "Couldn't create new image";
			return 0;
		}

		$tile_number = $tile_start;
		
		$tile_width = $this->properties['tile_width'];
		$tile_height = $this->properties['tile_height'];
		for($y=0; $y<$layer_tiles[$layer_number]['vertical_tiles']; $y++) {
			$cy = ($y*$tile_height);
			for($x=0; $x<$layer_tiles[$layer_number]['horizontal_tiles']; $x++) {
				$cx = ($x*$tile_width);
				$tile = $this->getTile($tile_number);
				if ($tile) { 
					$t = imagecreatefromstring($tile);
					if (!$t) {
						$this->error = "Invalid tile format";
						return 0;
					}
					imagecopy($r_new_image, $t, $cx, $cy, 0, 0, $tile_width, $tile_height);
					imagedestroy($t);
				}
				$tile_number++;
			}
		}
		return $r_new_image;
	}
	# ------------------------------------------------------------------------------------
	function getFileGeometry() {
		# --- Layer numbers start at 1  *NOT* 0!
		$layer_tiles = array();
		
		$width_tiles = $height_tiles = $start_tile = 0;
		
		for ($l=1; $l<=$this->properties['layers']; $l++) {
			$scale_factor = pow($this->properties['ratio'], ($this->properties['layers'] - $l));
			if (!$scale_factor) { $scale_factor = 1; }
			
			$effective_tile_width = $this->properties['tile_width'] * $scale_factor;
			$effective_tile_height = $this->properties['tile_height'] * $scale_factor;
		
			$width_tiles = $this->properties['width']/$effective_tile_width;
			$height_tiles = $this->properties['height']/$effective_tile_height;
			$tiles = ceil($width_tiles) * ceil($height_tiles);
			$layer_tiles[$l] = array(
									"layer"=>$l, "tiles" => $tiles, "effective_tile_width" => $effective_tile_width, "effective_tile_height" => $effective_tile_height, 
									"scale_factor" => $scale_factor, "horizontal_tiles" => ceil($width_tiles), "vertical_tiles" => ceil($height_tiles), 
									"width" => intval($width_tiles * $this->properties['tile_width']), "height" => intval($height_tiles * $this->properties['tile_height']), 
									"start_tile" => $start_tile);
		
			$start_tile += $tiles;
		}
		
		return $layer_tiles;
	}
	# ------------------------------------------------------------------------------------
	function getTileLayout() {
		# not implemented yet
	}
	# ------------------------------------------------------------------------------------
	function getProperty($value) {
		return $this->properties[$value];
	}
	# ------------------------------------------------------------------------------------
	function close() {
		return $this->init();
	}
	# ------------------------------------------------------------------------------------
}
?>