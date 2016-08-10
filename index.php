<?php
require("rgb-hsv.php"); // for RGB and HSL conversions


function deleteArray($array)
{
    // Now delete every item, but leave the array itself intact:
    foreach ($array as $i => $value) {
        unset($array[$i]);
    }
}

function getSimpleFractalImage($width, $height, $timesToDoFractal)
{

    // start with a single triangle
    $linesStartX = array(0 => 0, 1 => round($width/2));
    $linesStartY = array(0 => 0, 1 => $height);

    $linesEndX = array(0 => round($width/2), 1 => $width);
    $linesEndY = array(0 => $height, 1 => 0);

    $randRange = round($height/2);

    for($i = 0; $i < $timesToDoFractal; $i++)
    {

        $newLinesStartX = array();
        $newLinesStartY = array();

        $newLinesEndX = array();
        $newLinesEndY = array();

        $newLinesCount = 0;

        for($line = 0; $line < count($linesStartX); $line++)
        {
            // find the midpoint of a line and offset the Y coordinate
            $midpointX = round(($linesStartX[$line] + $linesEndX[$line]) / 2);
            $midpointY = round(($linesStartY[$line] + $linesEndY[$line]) / 2) + rand(-1.5 * $randRange,$randRange);

            if($midpointY < 0)
            {
                $midpointY = 0;
            }

            // add two new lines seperated by the midpoint generated
            $newLinesStartX[$newLinesCount] = $linesStartX[$line];
            $newLinesStartY[$newLinesCount] = $linesStartY[$line];

            $newLinesEndX[$newLinesCount] = $midpointX;
            $newLinesEndY[$newLinesCount] = $midpointY;

            $newLinesCount++;

            $newLinesStartX[$newLinesCount] = $midpointX;
            $newLinesStartY[$newLinesCount] = $midpointY;

            $newLinesEndX[$newLinesCount] = $linesEndX[$line];
            $newLinesEndY[$newLinesCount] = $linesEndY[$line];

            $newLinesCount++;
        }

        deleteArray($linesStartX);
        deleteArray($linesStartY);

        deleteArray($linesEndX);
        deleteArray($linesEndY);

        $linesStartX = $newLinesStartX;
        $linesStartY = $newLinesStartY;

        $linesEndX = $newLinesEndX;
        $linesEndY = $newLinesEndY;

        deleteArray($newLinesStartX);
        deleteArray($newLinesStartY);
        deleteArray($newLinesEndX);
        deleteArray($newLinesEndY);

        $randRange = round($randRange/(1 + (rand(100,999)/1000)));
    }

    // create image
    $image = imagecreatetruecolor($width, round($height * 1.3));

    // allocate colors
    $bg   = imagecolorallocate($image, 1, 2, 3);
    $blue = imagecolorallocate($image, 250, 251, 252);

    // fill the background
    imagefilledrectangle($image, 0, 0, $width, round($height * 1.3), $bg);

    for($line = 0; $line < count($linesStartX); $line++)
    {
        // set up array of points for polygon
        $values = array(
                    $linesStartX[$line],  $linesStartY[$line],  // Point 1 (x, y)
                    $linesEndX[$line],  $linesEndY[$line], // Point 2 (x, y)
                    $linesEndX[$line],  0,  // Point 3 (x, y)
                    $linesStartX[$line], 0  // Point 4 (x, y)
                    );
        // draw a polygon
        imagefilledpolygon($image, $values, 4, $blue);
    }

    return $image;
}

// variables that we will use to generate our fractal boxes
$width = isset($_GET["width"]) ? round($_GET["width"]/10) : 20;
$height = isset($_GET["height"]) ? round($_GET["height"]/13) : 20;
$shading = isset($_GET["shading"]) ? $_GET["shading"] : "both";
$colors = array(0 => 127 /* r */, 1 => 127 /* g */, 2 => 127 /* b */);
$fractals = isset($_GET["fractals"]) ? $_GET["fractals"] : 4; // not implimented
$from = isset($_GET["from"]) ? $_GET["from"] : "bottom";
$transparents = isset($_GET["transparents"]) ? $_GET["transparents"] : 0.0;
$stepping = isset($_GET["stepping"]) ? $_GET["stepping"] : 0.10;
$steps = isset($_GET["steps"]) ? $_GET["steps"] : 6;

// if the colors are a parameter then use them instead
if(isset($_GET["colors"]))
{
    // reset the array as we have new colros to put in!
    $colors = array();

    foreach($_GET["colors"] as $hexcolor)
    {
        // get the hex color for the Red/Green/Blue values of the color (string should look like "#01ab23")
        $hexRed = substr($hexcolor, 0, 2);
        $hexGreen = substr($hexcolor, 2, 2);
        $hexBlue = substr($hexcolor, 4, 2);

        $colors[count($colors)] = hexdec($hexRed);
        $colors[count($colors)] = hexdec($hexGreen);
        $colors[count($colors)] = hexdec($hexBlue);
    }
}

$image = null;

if($from == "left" || $from == "right")
{
    $image = getSimpleFractalImage($height, $width, $fractals);
}
else
{
    $image = getSimpleFractalImage($width, $height, $fractals);
}

if($from == "bottom")
{
    $image = imagerotate($image, 180, 0);
}
else if($from == "left")
{
    $image = imagerotate($image, 90, 0);
}
else if($from == "right")
{
    $image = imagerotate($image, 270, 0);
}

$width = imagesx($image);
$height = imagesy($image);



$finalimage = imagecreatetruecolor($width * 10, $height * 10);


$trans = imagecolorallocate($finalimage, 1, 2, 3);

$colorsToUse = array();

// throw in a transperant color, to break up the mountains
//$colorsToUse[count($colorsToUse)] = $trans;


// go through each color and add shades of that color to use
for($i = 0; $i < count($colors); $i += 3)
{
    $currentColor = array("R" => $colors[$i], "G" => $colors[$i + 1], "B" => $colors[$i + 2]);

    $numberOfShades = $steps;

    if($shading == "both") // if we want both lighter and darker shaded blocks
    {
        $numberOfShades = round($numberOfShades/2);
    }

    //$colorsToUse[count($colorsToUse)] = $trans;

    if($shading == "lighter" || $shading == "both")
    {
        $currentShade = array("R" => $currentColor["R"], "G" => $currentColor["G"], "B" => $currentColor["B"]);

        for($j = 0; $j < $numberOfShades; $j++)
        {
            $tempShade = makeLighterRGB($currentShade["R"], $currentShade["G"], $currentShade["B"], $stepping);

            if($tempShade["R"] != $currentShade["R"] || $tempShade["G"] != $currentShade["G"] || $tempShade["B"] != $currentShade["B"])
            {
                $currentShade = array("R" => $tempShade["R"], "G" => $tempShade["G"], "B" => $tempShade["B"]);

                $colorsToUse[count($colorsToUse)] = imagecolorallocate($finalimage, $tempShade["R"], $tempShade["G"], $tempShade["B"]);
            }
        }
    }

    if($shading == "darker" || $shading == "both")
    {
        $currentShade = array("R" => $currentColor["R"], "G" => $currentColor["G"], "B" => $currentColor["B"]);

        for($j = 0; $j < $numberOfShades; $j++)
        {
            $tempShade = makeDarkerRGB($currentShade["R"], $currentShade["G"], $currentShade["B"], $stepping);

            if($tempShade["R"] != $currentShade["R"] || $tempShade["G"] != $currentShade["G"] || $tempShade["B"] != $currentShade["B"])
            {
                $currentShade = array("R" => $tempShade["R"], "G" => $tempShade["G"], "B" => $tempShade["B"]);

                $colorsToUse[count($colorsToUse)] = imagecolorallocate($finalimage, $tempShade["R"], $tempShade["G"], $tempShade["B"]);
            }
        }
    }
}

imagefilledrectangle($finalimage, 0, 0, $width * 10, $height * 10, $trans);

for($x = 0; $x < $width; $x++)
{
    for($y = 0; $y < $height; $y++)
    {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // if the color does not equal the transparent color or we decide to make it transparent for effect...
        if($r != 1 && $b != 2 && $g != 3 && (rand(0,100)/100) >= $transparents)
        {
            imagefilledrectangle($finalimage, $x * 10, $y * 10, $x * 10 + 12, $y * 10 + 12, $trans);
            imagefilledrectangle($finalimage, $x * 10 + 1, $y * 10 + 1, $x * 10 + 8, $y * 10 + 8, $colorsToUse[rand(0,count($colorsToUse) - 1)]);
            imagefilledrectangle($finalimage, $x * 10 + 3, $y * 10 + 3, $x * 10 + 6, $y * 10 + 6, $trans);
        }
    }
}

imagecolortransparent($finalimage, $trans);

// flush image
header('Content-type: image/png');
imagepng($finalimage);
imagedestroy($finalimage);

?>