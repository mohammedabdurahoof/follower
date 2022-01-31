<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

/**
 *
 * @author Ritobroto
 * @returns List of Full Image, Each Full Image merged as base64
 */
trait FullImage {

    public function mergeImage($top_image, $bottom_image, $save_file = false) {
        $topPath = str_replace('\\', '/', storage_path('app\\'.$top_image));
        $bottomPath = str_replace('\\', '/', storage_path('app\\'.$bottom_image));
        
        list($img1_width, $img1_height) = getimagesize($topPath);
        list($img2_width, $img2_height) = getimagesize($bottomPath);

        $merged_width = $img1_width > $img2_width ? $img1_width : $img2_width; //get highest width as result image width
        $merged_height = $img1_height + $img2_height;
        
//        dd($img2_height." x ".$img2_width);
        
        $merged_image = imagecreatetruecolor($merged_width, $merged_height);

        imagealphablending($merged_image, false);
        imagesavealpha($merged_image, true);

        $img1 = imagecreatefromstring(file_get_contents($topPath));
        $img2 = imagecreatefromstring(file_get_contents($bottomPath));

        imagecopy($merged_image, $img1, 0, 0, 0, 0, $img1_width, $img1_height);
        //place at right side of $img1
        imagecopy($merged_image, $img2, 0, $img1_height, 0, 0, $img2_width, $img2_height);

        if ($save_file) {
            return false;
//            $save_path = "images/Sample_Output.png";
//            imagepng($merged_image, $save_path);
        } else {
            header('Content-Type: image/png');
            imagepng($merged_image);
        }

        //release memory
        imagedestroy($merged_image);
    }

}
