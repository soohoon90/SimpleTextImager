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
	
	$title = "Error";
	$text = "Error";
	$tagline = "Error";

	if (isset($_GET["title"])) $title = $_GET["title"];
	if (isset($_GET["n"])){
		$n = $_GET["n"];
		$title .= " #";	
		$title .= sprintf("%03d",$n);	
	} 
	if (isset($_GET["text"])) $text = $_GET["text"];
	if (isset($_GET["tagline"])) $tagline = $_GET["tagline"];

 	if(get_magic_quotes_gpc()){
		$title=stripslashes($title);
		$text=stripslashes($text);
		$tagline=stripslashes($tagline);
   }

	setcookie('n', $n);
	setcookie('text', $text);
	
	$font = 'Chunkfive.otf';
	// size of title
	$title_size = 48;
	// max size of text
	$text_size = 48;
	// max size of tagline
	$tag_size = 18;

	$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';		
	
	// dimension of image
	$image_width = 500;
	$image_height = 375;
	// margin above and below title
	$margin_title = 20;
	// margin around the image
	$margin = 20;
	
	// adjust tagline size to fit given width
	$available_width = $image_width - ($margin * 2);
	do{
		$box = imagettfbbox($tag_size, 0, $font, $tagline);
		$testbox = imagettfbbox($tag_size, 0, $font, $test_chars);
		$tag_width = $box[2]-$box[0];
		$tag_height = $box[1]-$box[7];
		$tag_lift = ($testbox[1]-$testbox[7] - $tag_height);
		$tag_size -= 2;
	}while( $available_width < $tag_width );
	
	// adjust title size to fit given width
	$available_width = $image_width - ($margin * 2);
	do{
		$title_box = imagettfbbox($title_size, 0, $font, $title);
		$title_width = $title_box[2]-$title_box[0];
		$title_height = $title_box[1]-$title_box[7];
		$title_size -= 2;
	}while( $available_width < $title_width );
	
	// adjust test size to fit given height
	$available_height = $image_height - ($margin * 2) - $tag_height - $title_height - $margin_title;
	do{
		$lines = linify($text, $image_width-($margin * 2), $font, $text_size);
		$box = ImageTTFBBox($text_size,0,$font,$test_chars) ;
		$text_height = $box[1] - $box[7];
		$text_size -= 2;
	}while( $available_height < (count($lines) * $text_height) );
	
	// create image and fill it with background	
	$image = imagecreatetruecolor ($image_width, $image_height);
	$background_color = imagecolorallocate ($image,rand(100,200),rand(100,200),rand(100,200));
	$tag_color = imagecolorallocate ($image,25,25,25);
	$title_color = imagecolorallocate ($image,0,0,0);
	$text_color = imagecolorallocate ($image,255,255,255);
	imagefill($image,0,0,$background_color);

	// Insert Title
    imagettftext($image, $title_size+2, 0, $margin, $title_height+$margin_title, $title_color, $font, $title); 
	imagettftext($image, $tag_size+2, 0, $image_width-$tag_width, $image_height-$tag_lift, $tag_color, $font, $tagline); 

	// Insert lines
	$line_count = 1;
	$topmargin = $title_height + $margin_title + $margin;
	foreach ($lines as $line){ 
		$linebox = imagettfbbox($text_size+2, 0, $font, $line);
		$line_x = $margin;
 		//$line_x = $image_width - ($linebox[2]-$linebox[0]) - $margin;
	    $line_y = ($text_height * $line_count);
	    imagettftext($image, $text_size+2, 0, $line_x, $line_y+$topmargin, $text_color, $font, $line);
		$line_count += 1;
	}
	$file = $tagline;
	$file .= "_";
	$file .= $n;
	$file .= "_";
	$file .= md5($text);
	$file .= ".png";
	imagepng ($image, "generated/".$file);
	imagedestroy($image);
	echo "<img src='generated/$file'>";
?>