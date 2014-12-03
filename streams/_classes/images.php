<?php
function fitinItemImage($filename, $type, $options)
{
	ini_set('memory_limit', '150M');

	$source = loadResizeItemImage($filename);
	if (!$source)
		return;

	$width = imagesx($source);
	$height = imagesy($source);

	$factor = $height < 253 ? 253 / $height : 1;
	$newheight = floor($height * $factor);
	$newwidth = floor($width * $factor);
	$newwidthR = $newwidth > 506 ? $newwidth : 506;
	$offset = ($newwidthR - $newwidth) / 2;

	$thumb = imagecreatetruecolor($newwidthR, $newheight);

	$white = imagecolorallocate($thumb, 255, 255, 255);
	imagefill($thumb, 1, 1, $white);
	imagecopyresampled($thumb, $source, $offset, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagedestroy($source);

	if (array_key_exists("addInterpol", $options)) {
		$interpol = imagecreatefrompng("/www/govalert/media/res/notice-" . $options["addInterpol"] . ".png");
		imagecopyresampled($thumb, $interpol, $newwidthR - 57, $newheight - 84, 0, 0, 57, 84, 57, 84);
		imagedestroy($interpol);
	}

	saveResizeItemImage($thumb, $filename, $type);
}

function resizeItemImage($filename, $type)
{
	ini_set('memory_limit', '150M');

	$source = loadResizeItemImage($filename);
	if (!$source)
		return;

	$width = imagesx($source);
	$height = imagesy($source);

	$factor = 0.7;
	if ($width > 1600)
		$factor = 1600 / $width;
	if ($height * $factor > 1600)
		$factor = 1600 / $height;

	$newwidth = floor($width * $factor);
	$newheight = floor($height * $factor);

	$thumb = imagecreatetruecolor($newwidth, $newheight);

	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagedestroy($source);
	saveResizeItemImage($thumb, $filename, $type);
}

function loadResizeItemImage($filename)
{
	$type = exif_imagetype($filename);
	if ($type == 2)
		return imagecreatefromjpeg($filename);
	else if ($type == 1)
		return imagecreatefromgif($filename);
	else if ($type == 3)
		return imagecreatefrompng($filename);
	else if ($type == 6)
		return imagecreatefrombmp($filename);
	return null;
}

function saveResizeItemImage($thumb, $filename, $type)
{
	if (strtolower($type) == ".jpg" || strtolower($type) == ".jpeg" || strtolower($type) == ".bmp")
		imagejpeg($thumb, $filename);
	else if (strtolower($type) == ".gif")
		imagegif($thumb, $filename);
	else if (strtolower($type) == ".png")
		imagepng($thumb, $filename);

	imagedestroy($thumb);
}

function imagecreatefrombmp($p_sFile)
{
	$file = fopen($p_sFile, "rb");
	$read = fread($file, 10);
	while (!feof($file) && ($read <> ""))
		$read .= fread($file, 1024);
	$temp = unpack("H*", $read);
	$hex = $temp[1];
	$header = substr($hex, 0, 108);
	if (substr($header, 0, 4) == "424d") {
		$header_parts = str_split($header, 2);
		$width = hexdec($header_parts[19] . $header_parts[18]);
		$height = hexdec($header_parts[23] . $header_parts[22]);
		unset($header_parts);
	}
	$x = 0;
	$y = 1;
	$image = imagecreatetruecolor($width, $height);
	$body = substr($hex, 108);
	$body_size = (strlen($body) / 2);
	$header_size = ($width * $height);
	$usePadding = ($body_size > ($header_size * 3) + 4);
	for ($i = 0; $i < $body_size; $i += 3) {
		if ($x >= $width) {
			if ($usePadding)
				$i += $width % 4;
			$x = 0;
			$y++;
			if ($y > $height)
				break;
		}
		$i_pos = $i * 2;
		$r = hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
		$g = hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
		$b = hexdec($body[$i_pos] . $body[$i_pos + 1]);
		$color = imagecolorallocate($image, $r, $g, $b);
		imagesetpixel($image, $x, $height - $y, $color);
		$x++;
	}
	unset($body);
	return $image;
}
