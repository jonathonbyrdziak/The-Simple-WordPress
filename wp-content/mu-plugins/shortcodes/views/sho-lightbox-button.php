<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Add Button</title>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=jquery-1.6.min"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=colorpicker"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=eye"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=utils"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=tiny_mce_popup"></script>
	<script type="text/javascript" src="<?php bloginfo('url'); ?>/?sho_script=process_bt_shortcodes"></script>
	<link rel="stylesheet" href="<?php bloginfo('url'); ?>/wp-content/plugins/shortcodes/css/color_picker/css/colorpicker.css" type="text/css" media="screen" />
</head>
<style>
.label{
	display: inline-block;
	margin: 0px 10px 0px 0px;
	width: 150px;
}
fieldset{padding:20px;border:1px solid #e5e5e5;}
legend, label{letter-spacing:1px;text-transform:uppercase;font-size:11px;}
input.color_scheme_input, input#url, input#text, input[type=password], input[type=file], select{padding:5px;width:50%;}
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
input.regular, input.color_scheme_input, input#url, input#text, input[type=password], input[type=file], textarea, select{
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
		<span class="label">Button Style:</span>
			&nbsp;
			<select id="shortcode" name="shortcode">
				

				<option value="image">Image</option>
				<option value="iframe">Iframe</option>

			</select>
	</p>
	
	<p>
		<span class="label">URL: </span>
			&nbsp;
			<input type="text" name="url" id="url" value="" />
	</p>
	
	<p>
		<span class="label">Image Thumb Width: </span>
			&nbsp;
			<input type="text" name="iwidth" id="iwidth" class="regular" value="" />
	</p>
	
	<p>
		<span class="label">Image Thumb Height: </span>
			&nbsp;
			<input type="text" name="iheight" id="iheight" class="regular" value="" />
	</p>
	
	<p>
		<span class="label">Popup Width: </span>
			&nbsp;
			<input type="text" name=width" id="width" class="regular" value="" />
	</p>
	
	<p>
		<span class="label">Popup Height: </span>
			&nbsp;
			<input type="text" name="height" id="height" class="regular" value="" />
	</p>
	
	<p>
		<span class="label">Link Text: </span>
			&nbsp;
			<input type="text" name="link" id="link" class="regular" value="" />
	</p>

<br/>
<hr>
<p style="text-align:center;">
	<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />

	<input type="submit" id="insert" name="insert" value="Insert" onclick="insertLightboxShortcode();"/>
</p>

</form>
</body>
</html>