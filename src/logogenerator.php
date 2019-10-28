<?php
/**
 * LogoGenerator is responsible for providing the logo generation
 * functionality of Freddit.
 */
class LogoGenerator {

  /**
   * generate()
   * Generates the website's logo for the given width and height.
   * 
   * @param  mixed $width The width of the logo to be generated.
   * @param  mixed $height The heigt of the logo to be generated.
   *
   * @return void Outputs the image directly to the browser.
   */
  public function generate($width, $height) {
    $image = imagecreate($width, $height);
    $background_color = imagecolorallocate($image, rand(175, 200), rand(1, 100), 0);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    imagestring($image, 3, $width/2 - $width * 0.08, $height/2 - $height * 0.12, "Freddit", $text_color);
    
    // Surrounding rectangle
    imagerectangle($image, $width/2 - $width * 0.2, $height/2 - $height * 0.2, $width/2 + $width * 0.2, $height/2 + $height * 0.2, $text_color);

    $png = imagepng($image);

    // Free allocated resources
    imagecolordeallocate($image, $background_color);
    imagecolordeallocate($image, $text_color);
    imagedestroy($image);

    return $png;
  }
}
?>