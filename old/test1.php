<?php
	// fit to width, increase height 
	header("Content-type: image/png");
	
	$title = "Untitled Tip #0";
	$string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nec est nisl. In hac habitasse platea dictumst. Phasellus tempus lorem porttitor nunc laoreet faucibus pulvinar tellus auctor. Phasellus rutrum, velit vel viverra accumsan, elit magna ornare diam, sit amet vestibulum mauris orci non sapien. Vivamus nec urna dui. Suspendisse potenti.";

	$words = explode(' ', $string);
	
	$font_file = 'Chunkfive.otf';
	$font_size = 24;

	$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';		
	$box = ImageTTFBBox($font_size,0,$font_file,$test_chars) ;
	$font_height = $box[1] - $box[7];
	
	$width = 480;
	$margin = 20;
	
	$title_color = '#3333333';
	$font_color = '#666666';
	$background_color = '#EEEEEE';
	
	$lines = array($words[0]);
	
	$currentLine = 0; 
    for($i = 1; $i < count($words); $i++) { 
		// see if next word fits
        $lineSize = imagettfbbox($font_size, 0, $font_file, $lines[$currentLine] . ' ' . $words[$i]);
        if($lineSize[2] - $lineSize[0] < $width-($margin*2)) { 
			// fits, concat
            $lines[$currentLine] .= ' ' . $words[$i]; 
        }else { 
			// doesn't fit, move to next line;
            $currentLine += 1;
            $lines[$currentLine] = $words[$i]; 
        } 
    }
	
	$height = (count($lines)+1) * $font_height + ($margin * 2);
	$image = imagecreatetruecolor ($width,$height);
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