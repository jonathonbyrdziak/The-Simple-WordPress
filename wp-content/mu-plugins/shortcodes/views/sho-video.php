<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Video Shortcode</title>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=tiny_mce_popup"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=process_shortcodes"></script>
</head>
<style>
.label{
	display: inline-block;
	margin: 0px 10px 0px 0px;
	width: 100px;
}
fieldset{padding:20px;border:1px solid #e5e5e5;}
legend, label{letter-spacing:1px;text-transform:uppercase;font-size:11px;}
input[type=text], input[type=password], input[type=file], select{padding:5px;width:50%;}
textarea{width:70%;height:140px;padding:5px;}
input, textarea, select {outline-style:none!important;}
input[type="submit"]::-moz-focus-inner{border : 0px!important;} 
input[type="submit"]:focus{outline:none;}
button::-moz-focus-inner,
input[type="reset"]::-moz-focus-inner,
input[type="button"]::-moz-focus-inner,
input[type="submit"]::-moz-focus-inner,
input[type="file"] > input[type="button"]::-moz-focus-inner {
	border: none;
}
input[type=text], input[type=password], input[type=file], textarea, select{
	background-color:#f9f9f9;
	border-color:#D9D9D9 #EAEAEA #FFFFFF;
	border-style:solid;
	border-width:1px;
	color:#666;
	font-size: 12px;
	font-family: "Lucida Sans Unicode","Lucida Grande", Arial, Verdana, sans-serif;}
</style>
<body>
<form onsubmit="return false;" action="#">
	
	<p>
		<span class="label"> Shortcode type:</span>
			<select id="shortcode" name="shortcode">
				

				<option value="vimeo">Vimeo</option>
				<option value="youtube">Youtube</option>
				<option value="flv">.FLV video</option>
				<option value="mp4">.MP4 Video</option>
				<option value="mov">.MOV Video</option>
				<option value="3gp">.3gp Video</option>

			</select>
	</p>
	
	<p>
		<span class="label">Video URL: </span>
			<input type="text" name="url" id="url" value="" />
	</p>
	
	<p>
		<span class="label"> Width: </span>
			<input type="text" name="width" id="width" value="" />
	</p>
		
	<p>
		<span class="label"> Height:</span>
			<input type="text" name="height" id="height" value="" />
	</p>
		
	<p>
		<span class="label"> Align:</span>
			<select id="align" name="align">
				

				<option value="left">Left</option>
				<option value="right">Right</option>
				<option value="center">Center</option>

			</select>
	</p>
		
	<p>
		<span class="label">Autoplay:</span>
			<select id="autoplay" name="autoplay">
				

				<option value="true">True</option>
				<option value="false">False</option>

			</select>
	</p>
<br/>
<hr>
<p style="text-align:center;">
	<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />

	<input type="submit" id="insert" name="insert" value="Insert" onclick="insertShortcode();"/>
</p>

</form>
</body>
</html>