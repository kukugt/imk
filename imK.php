<?php
/* * * * * * * * * * * * * * * * * * * * * *
** imK - Image Creator
** Author: Jorge Chaclán - http://kukugt.com
** Twitter: @kukugt
*/
class imK {
	var $r = array();
	var $cImage;
	var $thumb;

	var $new = NULL;
	var $dir = 'thumbnail';
	var $name = NULL;
	var $nTime = NULL;

	function imK ($image=null, $mod=null, $reWidth=0, $reHeight=0, $outType=null) {
		ini_set('memory_limit', '32M');
		set_time_limit(3600);
		set_magic_quotes_runtime(0);
		ini_set('magic_quotes_sybase', 0);
		if ( function_exists('date_default_timezone_set') )
			date_default_timezone_set('UTC');

		$iSize = getimagesize( $image );

		$this->r['mod'     ]	= $mod;
		$this->r['reWidth' ]	= abs($reWidth);
		$this->r['reHeight']	= abs($reHeight);
		$this->r['crX'     ]	= abs($crX);
		$this->r['crY'     ]	= abs($crY);
		$this->r['crWidth' ]	= abs($crWidth);
		$this->r['crHeight']	= abs($crHeight);
		$this->r['width'   ]	= $iSize[0];
		$this->r['height'  ]	= $iSize[1];
		$this->r['imgExt'  ]	= $iSize[2];
		$this->r['outType'  ]	= ($outType==null)?$iSize[2]:$outType;

		
		$this->ResizeImage($this->r);

		$this->Create($image, $this->r['imgExt']);

		imagecopyresampled($this->thumb, $this->cImage, 0, 0, 0, 0, $this->r['reWidth' ], $this->r['reHeight'], $this->r['width' ], $this->r['height']);

		$this->ImgOut($this->thumb, $this->r['outType']);

		imagedestroy($this->cImage);
		imagedestroy($this->thumb);
	}

	private function Create($image, $ext){
		switch($ext){
			case 1: $tb = imagecreatefromgif($image); break;
			case 2: $tb = imagecreatefromjpeg($image);break;
			case 3:	$tb = imagecreatefrompng($image); break;
			default:$tb = imagecreatefromstring(file_get_contents($image));	break;
		}
		$this->cImage = $tb;
	}

	private function ResizeImage (&$r){
		// Square
		if ($r['mod']=='q') {
			if ($r['width'] > $r['height']) {
				$r['reWidth' ] = ($r['reWidth']>0)?$r['reWidth']:$r['reHeight'];
				$r['reHeight'] = $r['height'] / ($r['width' ]/$r['reWidth']);
			} else {
				$r['reHeight'] = ($r['reWidth']>0)?$r['reWidth']:$r['reHeight'];
				$r['reWidth' ] = $r['width' ] / ($r['height']/ (($r['reWidth']>0)?$r['reWidth']:$r['reHeight']) );
			}
		}
		// Symmetric
		if ($r['mod'] == 's') {
			if ($r['reHeight'] > 0) {
				// $r['reHeight'] is the same.
				$r['reWidth' ]  = $r['width' ] / ($r['height']/$r['reHeight']);
			} elseif ($r['reWidth'] > 0) {
				$r['reHeight'] = $r['height' ] / ($r['width' ]/$r['reWidth' ]);
				// $r['reWidth']  is the same.
			}
		}
		// Asymmetric
		if ($r['mod'] == 'a') {
			$r['reWidth' ] = ($r['reWidth' ]>0)?$r['reWidth' ]:$r['width' ];
			$r['reHeight'] = ($r['reHeight']>0)?$r['reHeight']:$r['height'];
		}
		// Equal to the original 
		if ( $r['mod'] == null ) {
			$r['reWidth' ] = $r['width'];
			$r['reHeight'] = $r['height'];
		}

		$this->thumb = imagecreatetruecolor($r['reWidth' ], $r['reHeight']);
	}

	private function ImgOut(&$thumb, $type=0){
		switch($type){
			case 1:
				header("Content-type: image/gif");
				imagegif($thumb);
			break;
			case 2:
				header("Content-type: image/jpeg");
				imagejpeg($thumb);
			break;
			case 3: default:
				header("Content-type: image/png");
				imagepng($thumb);
			break;
		}//end Switch
	}

}





