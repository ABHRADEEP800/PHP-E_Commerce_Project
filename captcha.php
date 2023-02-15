<?php
session_start();

// header for image
header("Content-type: image/png");

// random text
$text = substr(str_shuffle("23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 5);

// store text in session
$_SESSION["captcha"] = $text;

// font
$font = "./monofont.ttf";

// image size
$width = 200;
$height = 50;

// create image
$image = imagecreatetruecolor($width, $height);

// background color
$color = imagecolorallocate($image, 176, 176, 176);

// text color
imagefilledrectangle($image, 0, 0, $width, $height, $color);

// random dots
$text_color = imagecolorallocate($image, 0, 0, 0);

// random lines
imagettftext($image, 25, 2, 60, 35, $text_color, $font, $text);

// output image
imagepng($image);

// clear memory
imagedestroy($image);
?>