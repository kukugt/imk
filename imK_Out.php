<?php
//Example
require_once "imK.php";

$image		= $_REQUEST['img'];

$mod		= $_REQUEST['mod'];

$reWidth	= $_REQUEST['width'];

$reHeight	= $_REQUEST['height'];

$outExt		= $_REQUEST['ext'];

new imK($image, $mod, $reWidth, $reHeight, $outExt);