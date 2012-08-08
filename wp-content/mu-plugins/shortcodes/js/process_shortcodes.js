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
	shf.url.value = shf.url.value == "" ? '' : shf.url.value;
	shf.width.value = shf.width.value == "" ? '480' : shf.width.value;
	shf.height.value = shf.height.value == "" ? '272' : shf.height.value;
	shf.align.value = shf.align.value == "" ? 'none' : shf.align.value;
	shf.autoplay.value = shf.autoplay.value == "" ? 'false' : shf.autoplay.value;
	
	if(shf.shortcode.value != null){
		switch (shf.shortcode.value) {
			
			case "vimeo":
				
				hs = '[vimeo url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
				
			break;
			
			case "youtube":
			
				hs = '[youtube url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
			
			case "megavideo":
			
				hs = '[megavideo url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
			
			case "flv":
			
				hs = '[flv_video url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
			
			case "mp4":
			
				hs = '[mp4_video url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
			
			case "mov":
			
				hs = '[mov_video url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
			
			case "3gp":
			
				hs = '[3gp_video url="'+shf.url.value+'" width="'+shf.width.value+'" height="'+shf.height.value+'" autoplay="'+shf.autoplay.value+'" align="'+shf.align.value+'"]';
			
			break;
		
		}

		ed.execCommand('mceInsertContent', false, hs);
		tinyMCEPopup.close();
	}
}

tinyMCEPopup.onInit.add(init);