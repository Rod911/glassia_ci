<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function placeholder_img() {

		// Dimensions
		$getsize    = isset($_GET['size']) ? $_GET['size'] : '100x100';
		$dimensions = explode('x', $getsize);
		$dim_y = 150;
		$dim_x = min($dim_y * ($dimensions[0] / $dimensions[1]), 300);

		// Create image
		$image      = imagecreate($dim_x, $dim_y);

		// Colours
		$bg         = isset($_GET['bg']) ? $_GET['bg'] : 'ccc';
		$bg         = hex2rgb($bg);
		$setbg      = imagecolorallocate($image, $bg['r'], $bg['g'], $bg['b']);

		$fg         = isset($_GET['fg']) ? $_GET['fg'] : '555';
		$fg         = hex2rgb($fg);
		$setfg      = imagecolorallocate($image, $fg['r'], $fg['g'], $fg['b']);

		// Text
		$text       = isset($_GET['text']) ? strip_tags($_GET['text']) : $getsize;
		$text       = str_replace('+', ' ', $text);

		// Text positioning
		$fontsize   = 4;
		$fontwidth  = imagefontwidth($fontsize);    // width of a character
		$fontheight = imagefontheight($fontsize);   // height of a character
		$length     = strlen($text);                // number of characters
		$textwidth  = $length * $fontwidth;         // text width
		$xpos       = (imagesx($image) - $textwidth) / 2;
		$ypos       = (imagesy($image) - $fontheight) / 2;

		// Generate text
		imagestring($image, $fontsize, $xpos, $ypos, $text, $setfg);

		// Render image
		imagepng($image);
		imagedestroy($image);
	}
}

// Convert hex to rgb (modified from csstricks.com)
function hex2rgb($colour) {
	$colour = preg_replace("/[^abcdef0-9]/i", "", $colour);
	if (strlen($colour) == 6) {
		list($r, $g, $b) = str_split($colour, 2);
		return array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
	} elseif (strlen($colour) == 3) {
		list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
		return array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
	}
	return false;
}
