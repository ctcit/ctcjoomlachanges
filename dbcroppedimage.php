<?php
require_once './configuration.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id']) ||
   !isset($_GET['width']) || !is_numeric($_GET['width'])  ||
   !isset($_GET['ratioLong']) || !is_numeric($_GET['ratioLong']) ||
   !isset($_GET['ratioShort']) || !is_numeric($_GET['ratioShort']))  {
    http_response_code(400);
    die("Invalid request");
}
$id = $_GET['id'];

$targetLong = $_GET['width'];
$targetRatioLong = $_GET['ratioLong'];
$targetRatioShort = $_GET['ratioShort'];

// Get the image
$config = new JConfig();

$db = new mysqli($config->host, $config->user, $config->password);
if (!$db) {
    die("Could not connect: " . $db->error);
}
$db->select_db("tripreports") or die($db->error);


$sql = "SELECT image, type FROM image WHERE id=$id";
$result = $db->query($sql) or die($db->error);
$row = $result->fetch_object() or die('Image not found');
if($row->type != "jpeg") {
    http_response_code(400);
    die("Requested image is not a jpeg");
}

$imgSrc = $row->image;
$db->close();

$targetRatio = $targetRatioLong / $targetRatioShort;

// Get image and dimensions
list($width, $height) = getimagesizefromstring($imgSrc);
$originalImage = imagecreatefromstring($imgSrc);

// To use the same algorithm whether the image is portrait or landscape,
// transform from x/y to long/short axes
$longAxisIsX = $width > $height;
$longAxisLength = 0;
$shortAxisLength = 0;
$shortAxisOffset = 0;
$longAxisOffset = 0;

if ($longAxisIsX) {
    $longAxisLength = $width;
    $shortAxisLength = $height;
} else {
    $longAxisLength = $height;
    $shortAxisLength = $width;
}

// We are going to resize and crop in one operation
// Need to calculate the offesets for the new image in the coordinates of the old image
$shortAxisOvershoot = $shortAxisLength - ($longAxisLength * $targetRatioShort / $targetRatioLong);
if ($shortAxisOvershoot > 0) {
    // Short axis is too long
    $shortAxisOffset = $shortAxisOvershoot / 2;
    $longAxisLengthCropped = $longAxisLength;
    $shortAxisLengthCropped = $shortAxisLength - $shortAxisOvershoot;
} else {
    // Long axis is too long
    $longAxisOvershoot = $longAxisLength - ($shortAxisLength * $targetRatioLong / $targetRatioShort);
    $longAxisOffset = $longAxisOvershoot / 2;
    $shortAxisLengthCropped = $shortAxisLength;
    $longAxisLengthCropped = $longAxisLength - $longAxisOffset;
}

// Calculate target dimensions
$targetShort = $targetLong * $targetRatioShort / $targetRatioLong;

// Transform back to x/y (width/height) coordinates
$xOffset = ($longAxisIsX) ? $longAxisOffset : $shortAxisOffset;
$yOffset = ($longAxisIsX) ? $shortAxisOffset : $longAxisOffset;
$targetWidth = ($longAxisIsX) ? $targetLong : $targetShort;
$targetHeight = ($longAxisIsX) ? $targetShort : $targetLong;
$widthCropped = ($longAxisIsX) ? $longAxisLengthCropped : $shortAxisLengthCropped;
$heightCropped = ($longAxisIsX) ? $shortAxisLengthCropped : $longAxisLengthCropped;

// Resample the original image
$croppedImage = imagecreatetruecolor($targetWidth, $targetHeight);
imagecopyresampled($croppedImage, $originalImage, 0, 0, $xOffset, $yOffset,
                $targetWidth, $targetHeight, $widthCropped, $heightCropped);

// Return output
header('Content-type: image/jpeg');
echo( imagejpeg($croppedImage) );