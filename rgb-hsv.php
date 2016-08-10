<?php
function RGB_TO_HSV ($R, $G, $B)  // RGB Values:Number 0-255
{                                            // HSV Results:Number 0-1
    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $V = $var_Max;

    if ($del_Max == 0)
    {
        $H = 0;
        $S = 0;
    }
    else
    {
        $S = $del_Max / $var_Max;

        $del_R = ( ( ( $max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

        if        ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

        if (H<0) $H++;
        if (H>1) $H--;
    }

    $HSL['H'] = $H;
    $HSL['S'] = $S;
    $HSL['V'] = $V;

    return $HSL;
}

function HSV_TO_RGB ($H, $S, $V)  // HSV Values:Number 0-1
{                                            // RGB Results:Number 0-255
     $RGB = array();

     if($S == 0)
     {
          $R = $G = $B = $V * 255;
     }
     else
     {
          $var_H = $H * 6;
          $var_i = floor( $var_H );
          $var_1 = $V * ( 1 - $S );
          $var_2 = $V * ( 1 - $S * ( $var_H - $var_i ) );
          $var_3 = $V * ( 1 - $S * (1 - ( $var_H - $var_i ) ) );

          if         ($var_i == 0) { $var_R = $V      ; $var_G = $var_3  ; $var_B = $var_1 ; }
          else if  ($var_i == 1) { $var_R = $var_2 ; $var_G = $V        ; $var_B = $var_1 ; }
          else if  ($var_i == 2) { $var_R = $var_1 ; $var_G = $V        ; $var_B = $var_3 ; }
          else if  ($var_i == 3) { $var_R = $var_1 ; $var_G = $var_2  ; $var_B = $V      ; }
          else if  ($var_i == 4) { $var_R = $var_3 ; $var_G = $var_1  ; $var_B = $V      ; }
          else                         { $var_R = $V      ; $var_G = $var_1  ; $var_B = $var_2 ; }

          $R = $var_R * 255;
          $G = $var_G * 255;
          $B = $var_B * 255;
     }

     $RGB['R'] = $R;
     $RGB['G'] = $G;
     $RGB['B'] = $B;

     return $RGB;
}

function makeLighterHSV($HSV, $step)
{
     $HSV["V"] += $step;

     if($HSV["V"] > 1.0)
     {
          $HSV["V"] = 1.0;
     }

     return $HSV;
}

function makeDarkerHSV($HSV, $step)
{
     $newHSV = array("H" => $HSV["H"], "S" => $HSV["S"], "V" => $HSV["V"]);

     $newHSV["V"] -= $step;

     if($newHSV["V"] < 0.0)
     {
          $newHSV["V"] = 0.0;
     }

     return $newHSV;
}

function makeLighterRGB($R, $G, $B, $step)
{
     $HSV = makeLighterHSV(RGB_TO_HSV($R, $G, $B), $step);

     return HSV_TO_RGB($HSV["H"], $HSV["S"], $HSV["V"]);

     //$scale = 1.1;

     //return array("R" => $R * $scale, "G" => $G * $scale, "B" => $B * $scale);
}

function makeDarkerRGB($R, $G, $B, $step)
{
     $HSV = makeDarkerHSV(RGB_TO_HSV($R, $G, $B), $step);

     return HSV_TO_RGB($HSV["H"], $HSV["S"], $HSV["V"]);

     //$scale = 0.9;

     //return array("R" => $R * $scale, "G" => $G * $scale, "B" => $B * $scale);
}
?>