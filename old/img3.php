<?php

	require('textImageMaker.php');

	isset($_GET["text"]) ? $text = $_GET["text"] : $text = "Error";
	isset($_GET["align"]) ? $align = $_GET["align"] : $align = "center";
	isset($_GET["margin"]) ? $margin = $_GET["margin"] : $margin = 50;
	isset($_GET["text_size"]) ? $text_size = $_GET["text_size"] : $text_size = 100;
	
	$txtimg = new textImageMaker();
	//$txtimg->textOnlyMode = true;
	get_magic_quotes_gpc() ? $txtimg->text = stripslashes($text) : $txtimg->text = $text;
	$txtimg->titleText = "test tips # 001";
	$txtimg->tagText = "texttips.tumblr.com";
	$txtimg->draw();
	$img = $txtimg->output();
	echo "<img src='$img'>";

?>



