<?php
	
	class textImageMaker{
		
		public $fontFolder = "fonts/";
		public $font = 'Chunkfive.otf';
		
		public $titleText;
		public $text;
		public $tagText;
		
		public $titleSize = 100;
		public $textSize = 100;
		public $tagSize = 100;
		
		public $titleAlign = 'center';
		public $textAlign = 'center';
		public $tagAlign = 'center';
		
		public $textOnlyMode = false;
		
		public $imageWidth = 500;
		public $imageHeight = 500;
		
		public $margin = 50;
		
		public $outputFolder  = 'generated/';
		
		private $image;
				
		function draw(){
			$this->image = imagecreatetruecolor ($this->imageWidth, $this->imageHeight);
			$backgroundColor = imagecolorallocate ($this->image, rand(100,150), rand(100,150), rand(100,150));
			$tagColor = imagecolorallocate ($this->image, 25, 25, 25);
			$titleColor = imagecolorallocate ($this->image, 25, 25, 25);
			$textColor = imagecolorallocate ($this->image, 255, 255, 255);
			imagefill($this->image, 0, 0, $backgroundColor);
			imagerectangle ( $this->image, $this->margin, $this->margin, $this->imageWidth-$this->margin, $this->imageHeight-$this->margin, imagecolorallocate ($this->image, 25, 25, 25) );
			
			if ($this->textOnlyMode){
				$top = $this->margin + $titleHeight;
				$availableWidth = $this->imageWidth - $this->margin*2;
				$availableHeight = $this->imageHeight - $this->margin*2;
				$this->drawTextBlock($this->font, &$this->textSize, $this->text, $textColor, $this->textAlign, $top, $availableWidth, $availableHeight);
			}else{
				//function drawText($font, &$size, $text, $color, $align, $verticalAlign, $margin)
				echo "title was ".$this->titleSize.", tag was ".$this->tagSize."<br>";
				$this->drawText($this->font, $this->titleSize, $this->titleText, $titleColor, $this->titleAlign, 'top', $this->margin);
				$this->drawText($this->font, $this->tagSize, $this->tagText, $tagColor, $this->tagAlign, 'bottom', $this->margin);
				echo "title was ".$this->titleSize.", tag was ".$this->tagSize."<br>";
				$titleHeight = $this->getTextHeight($this->font, $this->titleSize);
				$tagHeight = $this->getTextHeight($this->font, $this->tagSize);
				$top = $this->margin + $titleHeight;
				$availableWidth = $this->imageWidth - $this->margin*2;
				$availableHeight = $this->imageHeight - $this->margin*2 - $titleHeight;
				$this->drawTextBlock($this->font, $this->textSize, $this->text, $textColor, $this->textAlign, $top, $availableWidth, $availableHeight);
			}
			
		}
		
		function output(){
			$outputFilename = md5($this->text).'.png';
			imagepng ($this->image, $this->outputFolder.$outputFilename);
			imagedestroy($this->image);
			return $this->outputFolder.$outputFilename;
		}
		
		function fitTextSizeHorizontally($font, $size, $text, $availableWidth){
			
			// this is for one line text (fit title or tag on given line of space)
			
			do{
				$textWidth = $this->getTextWidth($font, $size, $text);
				$size -= 2;
			}while( $availableWidth < $textWidth );
			
			return $size + 2;
		}
		
		function fitTextSizeVertically($font, $size, $text, $availableWidth, $availableHeight){
			
			// this fn assumes the text is smaller than height and there are multiple lines
			// figure out number of lines given width constraint
			// adjust font size so that output height is smaller than given height constraint
			do{
				$lines = $this->linify($font, $size, $text, $availableWidth);
				$textHeight = $this->getTextHeight($font, $size);
				$size -= 2;
			}while( $availableHeight < (count($lines) * $textHeight) );
			$size += 2;
			$lines = $this->linify($font, $size, $text, $availableWidth);
			return $size;
		}
		
		function getTextWidth($font, $size, $text){
			$box = imagettfbbox($size, 0, $font, $text);
			return $box[2]-$box[0];
		}
		
		function getTextHeight($font, $size, $text=""){
			$test_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';
			$box = ImageTTFBBox($size, 0, $font, $test_chars);
			return $box[1] - $box[7];
		}
		
		function drawText($font, &$size, $text, $color, $align, $verticalAlign, $margin){

			// adjust the size so it fits
			$size = $this->fitTextSizeHorizontally($font, $size, $text, $this->imageWidth-$margin*2);
			
			// figure out width and height
			$textWidth = $this->getTextWidth($font, $size, $text);
			$textHeight = $this->getTextHeight($font, $size);
			
			// figure out x location
			if ($align == 'center')	$x = $this->imageWidth/2 - $textWidth/2;
			else if ($align == 'right')	$x = $this->imageWidth - $textWidth - $this->margin;
			else $x = $this->margin;
			
			// figure out y location
			if ($verticalAlign == 'bottom')	$y = $this->imageHeight - $textHeight*0.2;
			else if ( $verticalAlign == 'center') $y = $this->imageHeight/2 + $textHeight;
			else $y = $this->margin + $textHeight; 

			// draw text
			imagettftext($this->image, $size, 0, $x, $y, $color, $font, $text);
			imagerectangle ( $this->image, $x, $y-$textHeight, $x+$textWidth, $y, $color );
			
		}
		
		function drawTextBlock($font, &$size, $text, $color, $align, $top, $availableWidth, $availableHeight){
						
			// assume using full imageWidth and margin;
			$size = $this->fitTextSizeVertically($font, $size, $text, $availableWidth, $availableHeight);
			$lines = $this->linify($font, $size, $text, $availableWidth );
			
			$textHeight = $this->getTextHeight($font, $size);
			for ($i = 0; $i < count($lines); $i++){
				
				$textWidth = $this->getTextWidth($font, $size, $lines[$i]);
								
				if ($align=="right") $x = $this->imageWidth - $$this->margin - $textWidth;
				else if ($align=="center") $x = $this->imageWidth/2 - $textWidth/2;
				else $x = $this->margin;
				
			    $y = ($textHeight * ($i+1)) + $top;
			
			    imagettftext($this->image, $size, 0, $x+2, $y+2, imagecolorallocate ($this->image, 0, 0, 0), $font, $lines[$i]);
				imagettftext($this->image, $size, 0, $x, $y, $color, $font, $lines[$i]);
				imagerectangle ( $this->image , $x , $y-$textHeight , $x+$textWidth , $y, $color );
			}
		}
		
		
		function linify($font, $size, $text, $availableWidth){
			$words = explode(' ', $text);
			$lines = array($words[0]);
			$currentLine = 0;
		    for($i = 1; $i < count($words); $i++) { 
				// see if next word fits
		        $lineSize = imagettfbbox($size, 0, $font, $lines[$currentLine] . ' ' . $words[$i]);
				$linewidth = $lineSize[2] - $lineSize[0];
		        if($linewidth < $availableWidth) { 
		            $lines[$currentLine] .= ' ' . $words[$i]; 
		        }else {
		            $currentLine += 1;
		            $lines[$currentLine] = $words[$i]; 
		        } 
		    }
			return $lines;
		}
	}
	
?>