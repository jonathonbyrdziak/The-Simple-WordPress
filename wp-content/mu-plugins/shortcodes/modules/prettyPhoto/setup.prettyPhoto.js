;;(function(window,jQuery) {
	//method to load the files
	function loadSource(src, doCallback){
		//adding the script tag to the head as suggested before
		var head = document.getElementsByTagName('head')[0];
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = src;
		
		// then bind the event to the callback function 
		// there are several events for cross browser compatibility
		if (typeof doCallback != 'undefined')
		{
			script.onreadystatechange = doCallback;
			script.onload = doCallback
		}
		
		// fire the loading
		head.appendChild(script);
	}
	
	var setupPrettyPhoto = function(){
		jQuery("*[rel^='shoLightbox']").prettyPhoto({
			social_tools:'',
			theme: 'light_square'
		});
		
		
	}
	
	jQuery || loadSource("https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js");
	
	//loadSource("/wp-content/themes/5TwentyStudios/plugins/shortcodes/modules/prettyPhoto/jquery.prettyPhoto.js", function(){
	loadSource("/?sho_script=../modules/prettyPhoto/jquery.prettyPhoto", function(){
		if (jQuery.isReady) setupPrettyPhoto();
		else jQuery(document).ready(setupPrettyPhoto);
	});
	
})(window,jQuery);