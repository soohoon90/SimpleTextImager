<?php
	
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
	
	isset($_GET["text"]) ? $text = $_GET["text"] : $text = "Error";
	isset($_GET["align"]) ? $align = $_GET["align"] : $align = "center";
	isset($_GET["margin"]) ? $margin = $_GET["margin"] : $margin = 50;
	isset($_GET["text_size"]) ? $text_size = $_GET["text_size"] : $text_size = 100;
	
 	if(get_magic_quotes_gpc()){
		$text=stripslashes($text);
   }
	
	$font = 'Chunkfive.otf';
	$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';		
	
	// dimension of image
	$image_width = 500;
	$image_height = 500;
	
	// adjust test size to fit given height
	$available_height = $image_height - ($margin * 2);
	do{
		$lines = linify($text, $image_width-($margin * 2), $font, $text_size);
		$box = ImageTTFBBox($text_size,0,$font,$test_chars) ;
		$text_height = $box[1] - $box[7];
		$text_size -= 2;
	}while( $available_height < (count($lines) * $text_height) );
	$text_size += 2;
	
	// create image and fill it with background	
	$image = imagecreatetruecolor ($image_width, $image_height);
	$background_color = imagecolorallocate ($image,rand(50,100),rand(50,100),rand(50,100));
	$tag_color = imagecolorallocate ($image,25,25,25);
	$title_color = imagecolorallocate ($image,0,0,0);
	$text_color = imagecolorallocate ($image,255,255,255);
	imagefill($image,0,0,$background_color);

	
	// Insert lines
	$line_count = 1;
	$topmargin = (($image_height - $margin*2) - ($text_height * count($lines)))/2 + $margin;
	foreach ($lines as $line){ 
		$linebox = imagettfbbox($text_size, 0, $font, $line);
		$line_x = $margin;
		if($align=="right") $line_x = $image_width - ($linebox[2]-$linebox[0]) - $margin;
		if($align=="center") $line_x = ($image_width - ($linebox[2]-$linebox[0]))/2;
	    $line_y = ($text_height * $line_count);
	    imagettftext($image, $text_size, 0, $line_x, $line_y+$topmargin, $text_color, $font, $line);
		$line_count += 1;
	}
	$file .= md5($text);
	$file .= ".png";
	imagepng ($image, "generated2/".$file);
	imagedestroy($image);
	echo "<img src='generated2/$file'>";
?>