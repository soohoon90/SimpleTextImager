<?php
	// fit to width and height
	function linify($string, $fitwidth, $font, $size){
		$words = explode(' ', $string);
		$lines = array($words[0]);
		$currentLine = 0; 
	    for($i = 1; $i < count($words); $i++) { 
			// see if next word fits
	        $lineSize = imagettfbbox($size, 0, $font, $lines[$currentLine] . ' ' . $words[$i]);
			$linewidth = $lineSize[2] - $lineSize[0];
	        if($linewidth < $fitwidth) { 
	            $lines[$currentLine] .= ' ' . $words[$i]; 
	        }else { 
	            $currentLine += 1;
	            $lines[$currentLine] = $words[$i]; 
	        } 
	    }
		return $lines;
	}
	
	header("Content-type: image/png");
	
	$title = "Untitled Tip #0";
	$string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec est nisl. In hac habitasse platea dictumst. Phasellus tempus lorem porttitor nunc laoreet faucibus pulvinar tellus auctor. ";
	
	$font_file = 'Chunkfive.otf';
	$font_size = 100;

	$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';		
	
	$width = 500;
	$height = 375;
	$margin = 20;
	
	do{
		$lines = linify($string, $width, $font_file, $font_size);
		$box = ImageTTFBBox($font_size,0,$font_file,$test_chars) ;
		$font_height = $box[1] - $box[7];
		$font_size -= 2;
	}while( $height < (count($lines)+1) * $font_height + ($margin * 2));
		
	$image = imagecreatetruecolor ($width, $height);
	$background_color = imagecolorallocate ($image,150,150,150);
	$title_color = imagecolorallocate ($image,0,0,0);
	$text_color = imagecolorallocate ($image,255,255,255);
	imagefill($image,0,0,$background_color);

	$line_box = imagettfbbox($font_size, 0, $font_file, $title); 
	$line_height = $line_box[1]-$line_box[7];
    imagettftext($image, $font_size, 0, $margin, $font_height, $title_color, $font_file, $title); 

	$line_count = 2;
	foreach ($lines as $line){ 
	    $line_box = imagettfbbox($font_size, 0, $font_file, $line); 
	    $line_y = ($font_height * $line_count);
	    imagettftext($image, $font_size, 0, $margin, $line_y, $text_color, $font_file, $line); 

	    // Increment Y so the next line is below the previous line 
	    $line_count ++; 
	}

	imagepng ($image);
	imagedestroy($image);



?>