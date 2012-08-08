tinyMCEPopup.requireLangPack();

var ed;

function init(){

	ed = tinyMCEPopup.editor;

	tinyMCEPopup.resizeToInnerSize();
	shf = document.forms[0];
}


function insertShortcode(){
	var fe, shf = document.forms[0], hs;

	tinyMCEPopup.restoreSelection();
	
	shf.shortcode.value = shf.shortcode.value == "" ? '' : shf.shortcode.value;
	shf.url.value = shf.url.value == "" ? '#' : shf.url.value;
	shf.text.value = shf.text.value == "" ? 'Text Button' : shf.text.value;
	shf.bg_color.value = shf.bg_color.value == "" ? '009ab6' : shf.bg_color.value;
	shf.font_color.value = shf.font_color.value == "" ? 'FFFFFF' : shf.font_color.value;
	
	if(shf.shortcode.value != null){
		switch (shf.shortcode.value) {
			
			case "small":
				
				hs = '[button size="small" href="'+shf.url.value+'" bgcolor="#'+shf.bg_color.value+'" fontcolor="#'+shf.font_color.value+'" text="'+shf.text.value+'"]';
				
			break;
			
			case "medium":
				
				hs = '[button size="medium" href="'+shf.url.value+'" bgcolor="#'+shf.bg_color.value+'" fontcolor="#'+shf.font_color.value+'" text="'+shf.text.value+'"]';
				
			break;
			
			case "big":
				
				hs = '[button size="big" href="'+shf.url.value+'" bgcolor="#'+shf.bg_color.value+'" fontcolor="#'+shf.font_color.value+'" text="'+shf.text.value+'"]';
				
			break;

		
		}

		ed.execCommand('mceInsertContent', false, hs);
		tinyMCEPopup.close();
	}
}


function insertLightboxShortcode(){
	var fe, shf = document.forms[0], hs;

	tinyMCEPopup.restoreSelection();
	
	if(shf.shortcode.value != null)
	{
		hs = '[sho_lightbox type="'+shf.shortcode.value+'" url="'+shf.url.value+'"';

		if ( iwidth = shf.iwidth.value )
			hs += ' iwidth="'+ iwidth +'"';
		
		if ( iheight = shf.iheight.value )
			hs += ' iheight="'+ iheight +'"';

		if ( width = shf.width.value )
			hs += ' width="'+ width +'"';

		if ( height = shf.height.value )
			hs += ' height="'+ height +'"';

		if ( link = shf.link.value )
			hs += ' link="'+ link +'"';
		
		hs += ']';
		ed.execCommand('mceInsertContent', false, hs);
		tinyMCEPopup.close();
	}
}

tinyMCEPopup.onInit.add(init);