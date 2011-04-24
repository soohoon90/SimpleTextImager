<?php

	// randomize colour

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
	
	$title = "Brotip #0";
	$text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec est nisl. In hac habitasse platea dictumst. Phasellus tempus lorem porttitor nunc laoreet faucibus pulvinar tellus auctor. ";
	
	$font = 'Chunkfive.otf';
	// size of title
	$title_size = 48;
	// max size of text
	$text_size = 48;

	$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';		
	
	// dimension of image
	$image_width = 500;
	$image_height = 375;
	// margin above and below title
	$margin_title = 20;
	// margin around the image
	$margin = 20;
	
	// adjust title size to fit given width
	$available_width = $image_width - ($margin * 2);
	do{
		$title_box = imagettfbbox($title_size, 0, $font, $title);
		$title_width = $title_box[2]-$title_box[0];
		$title_height = $title_box[1]-$title_box[7];
		$title_size -= 2;
	}while( $available_width < $title_width );
	
	// adjust test size to fit given height
	$available_height = $image_height - $title_height - ($margin_title * 2) - ($margin * 2);
	do{
		$lines = linify($text, $image_width, $font, $text_size);
		$box = ImageTTFBBox($text_size,0,$font,$test_chars) ;
		$text_height = $box[1] - $box[7];
		$text_size -= 2;
	}while( $available_height < (count($lines) * $text_height) );
	
	// create image and fill it with background	
	$image = imagecreatetruecolor ($image_width, $image_height);
	$background_color = imagecolorallocate ($image,rand(100,200),rand(100,200),rand(100,200));
	$title_color = imagecolorallocate ($image,0,0,0);
	$text_color = imagecolorallocate ($image,255,255,255);
	imagefill($image,0,0,$background_color);

	// Insert Title
    imagettftext($image, $title_size, 0, $margin, $title_height+$margin_title, $title_color, $font, $title); 

	// Insert lines
	$line_count = 1;
	$topmargin = $title_height + ($margin_title * 2) + $margin;
	foreach ($lines as $line){ 
	    $line_y = ($text_height * $line_count);
	    imagettftext($image, $text_size, 0, $margin, $line_y+$topmargin, $text_color, $font, $line);
		$line_count += 1;
	}

	imagepng ($image);
	imagedestroy($image);

?>