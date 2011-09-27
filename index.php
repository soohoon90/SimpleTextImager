<style>
	body{
		font-family: helvetica, arial, sans-serif;
		font-size: 14px;
	}
	input{
		padding: 0.5em;
	}
	#wrapper{
		width: 800px;
		margin: 50px auto;
		border: 1px solid black;
		padding: 2em;
	}
	#sidebar{
		float: left;
		width: 160px;
		background-color: #eee;
		border: 1px solid #333;
	}
	#header{
		margin-bottom: 2em;
		text-align: center;
	}
	.forms{
		margin-left: 200px;
		background-color: #eee;
		padding: 2em;
		margin-bottom: 2em;		
	}
	#footer{
		text-align: center;
	}
</style>
<body OnLoad="document.generate.text.focus();">
	<div id="wrapper">
		<div id="header">
			<h1>Tumblr Text Image Generator</h1>
			<h2>Easiest way to make text-image posts</h2>
		</div>
		<div id="content">
			<div class="forms">
				<h3>Image with Title, text and tagline</h3>
				<h4>500x375</h4>
				<form name="generate" action="img.php" method="get">
				<?php isset($_COOKIE['n']) ? $n = $_COOKIE['n']+1 : $n = ""; ?>
				Last number: <?php echo isset($_COOKIE['n']) ? $_COOKIE['n'] : ""; ?><br />
				Last text: <?php echo isset($_COOKIE['text']) ? $_COOKIE['text'] : ""; ?><br />
				<br />
				Title: <input type="text" size="50" name="title" value="UNTITLED TIPS"/>
				Number: <input type="text" size="5" name="n" value="<?php echo $n; ?>"/><br />
				Text: <input type="text" size="75" name="text" /><br />
				Tagline: <input type="text" size="71" name="tagline" value="untitledtips.tumblr.com"/><br />
				<input type="submit" />
				</form>
			</div>
			<div class="forms">
				<h3>Image with centered text</h3>
				<h4>500x500</h4>
				<form name="generate" action="img2.php" method="get">
				Text: <input type="text" size="100" name="text" /><br />
				Margin: <input type="text" size="10" name="margin" value="50" />
				Text Size: <input type="text" size="10" name="text_size" value="32" /><br />
				Alignment: 
				<input type="radio" name="align" value="left" /> Left 
				<input type="radio" name="align" value="center" checked="true"/> Centre 
				<input type="radio" name="align" value="right" /> Right<br/>
				<input type="submit" />
				</form>
			</div>
		</div>		
		<div id="footer">
			Copyright 2011 Hoon Cho.
		</div>
	</div>
</body>