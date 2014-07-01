<?php

namespace markroland;

class ImageManipulation
{

    protected $name;

    public function hello()
    {
        return "Hello world!";
    }

    /**
     * Resize a .gif, .png or .jpeg image
     * @param string $file_source Path to source file that will be resized
     * @param string $new_location Path to destination file for new image
     * @param integer $max_width Maximum width of desired image
     * @param integer $max_width Maximum width of desired image
     * @return boolean True if image is successfully created, false otherwise
     */
    public function resize($file_source, $new_location, $max_width, $max_height)
    {

        // Get information about the file
        $image_details = getimagesize($file_source);

        // Check for file type support
        if (!in_array($image_details['mime'], array('image/gif','image/jpeg','image/png'))) {
            throw new Exception('imagecreatetruecolor() failed in ImageManipulation->resize().');
            return false;
        }

        // Save image details to more descriptive variable names
        $file_type = $image_details['mime'];
        $source_width = $image_details[0];
        $source_height = $image_details[1];

        // Set maximum height and width to current size of image
        $dest_width = $source_width;
        $dest_height = $source_height;

        // Check width. Use it as the limiting factor if great than requested width.
        if ($source_width > $max_width) {
            $dest_width = $max_width;
            $dest_height = (int)($source_height/($source_width/$max_width)+.5);
        }

        // Check height. Use it as the limiting factor if great than requested width.
        if ($dest_height>$max_height) {
            $dest_height = $max_height;
            $dest_width = (int)($source_width/($source_height/$max_height)+.5);
        }

        // Create new image placeholder
        if (!$new_image = imagecreatetruecolor($dest_width, $dest_height)) {
            throw new Exception('imagecreatetruecolor() failed in ImageManipulation->resize().');
            return false;
        }

        // Resample image
        switch($file_type){
            case "image/gif":
                imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                $image = imagecreatefromgif($file_source);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $dest_width, $dest_height, $source_width, $source_height);
                $status = imagegif($new_image, $new_location);
                break;
            case "image/jpeg":
                $bgColor = imagecolorallocate($new_image, 255, 255, 255);
                imagefill($new_image, 0, 0, $bgColor);
                $image = imagecreatefromjpeg($file_source);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $dest_width, $dest_height, $source_width, $source_height);
                $status = imagejpeg($new_image, $new_location, 20);
                break;
            case "image/png":
                imageAlphaBlending($new_image, false);
                imageSaveAlpha($new_image, true);
                $image = imagecreatefrompng($file_source);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $dest_width, $dest_height, $source_width, $source_height);
                $status = imagepng($new_image, $new_location);
                break;

        }

        // Free Memory
        imagedestroy($new_image);
        imagedestroy($image);

        return true;
    }

    /**
     * Trim off empty image borders
     * @param string $file_source Path to source file that will be resized
     * http://www.php.net/manual/en/function.imagecopy.php
     */
    public function trim($file_source, $file_destination)
    {

        /*
        // Make another image to place the trimmed version in.
        $new_image = imagecreatetruecolor(247,247);
        imageAlphaBlending($new_image, false);
        imageSaveAlpha($new_image, true);

        // Copy it over to the new image.
        // imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, 247, 247, 256, 256);
        imagecopy($new_image, $source_image, 0, 0, 8, 8, 255, 255);

        // Save the image as a PNG
        // imageAlphaBlending($new_image, false);
        // imageSaveAlpha($new_image, true);
        $status = imagejpeg($new_image, $file_destination);

        $image = imagecreatefromjpeg($image_filepath);
        return;
        */

        // Load image from file
        $image = imagecreatefromjpeg($file_source);

        // Initialize padding (unused)
        $padding = 0;

        // Set background color to be trimmed
        $background = imagecolorallocate($image, 255, 255, 255);

        // Loop through edges, test for background, and set border
        $top = imageSY($image);
        $left = imageSX($image);
        $bottom = 0;
        $right = 0;
        for ($x = 0 ; $x < imagesx($image) ; $x++) {
            for ($y = 0 ; $y < imagesy($image) ; $y++) {
                if(imagecolorat($image, $x, $y) != $background) {
                  if($x < $left)
                    $left = $x;
                  if($x > $right)
                    $right = $x;
                  if($y > $bottom)
                    $bottom = $y;
                  if($y < $top)
                    $top = $y;
                }
            }
        }
        $right++;
        $bottom++;

        // Create new image
        $new_image = imagecreatetruecolor($right-$left+$padding*2, $bottom-$top+$padding*2);

        // Fill the background
        imagefill($new_image, 0, 0, $background);

        // Copy image
        imagecopy($new_image, $image, $padding, $padding, $left, $top, $right-$left, $bottom-$top);

        // Save image
        imagejpeg($new_image, $file_destination);

        // Remove images
        imagedestroy($image);
        imagedestroy($new_image);

    }

    /**
     * Convert a white background to a transparent background
     * @param string $file_source Path to source file
     * @param string $file_destination Path to destination file
     */
    public function convert_white_jpg_to_transparent_png($file_source, $file_destination)
    {

        // Load current image
        $image = imagecreatefromjpeg($file_source);

        // Use blending
        imagealphablending($image, true);

        // Identify a color to be changed to transparent - white in this case
        imagecolortransparent($image, imagecolorallocate($image, 255,255,255));

        // Save the image as a PNG
        imagepng($image, $file_destination);

    }

    /**
     * Convert a GIF image to a JPEG image
     * @param string $file_source Path to source file that will be resized
     * @param integer $quality Quality of JPEG compression
     * @param boolean|string False if failed. New file path if successful.
     */
    function convert_gif_to_jpeg($file_source, $quality = 90){

        $new_location = str_replace('.gif', '.jpeg', $file_source);

        // Get image info
        $image_details = getimagesize($file_source);

        // Create new image that is the same size as the original
        $new_image = imagecreatetruecolor($image_details[0], $image_details[1]);

        // Apply white background to new image
        $bgColor = imagecolorallocate($new_image,255,255,255);
        imagefill($new_image,0,0,$bgColor);

        // Merge existing image in to white background
        $image = imagecreatefromgif($file_source);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $image_details[0], $image_details[1], $image_details[0], $image_details[1]);

        // Save new image as jpg
        if( imagejpeg($new_image, $new_location, $quality) ){
            // Free Memory
            imagedestroy($new_image);
            return $new_location;
        }

        return false;
    }


}
