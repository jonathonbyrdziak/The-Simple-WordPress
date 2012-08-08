
	function pop_upload(id, title, type){
		if( title == "" )
			title = "Upload Your Image";
			
		jQuery("body").append("<div id='up-th' style='display:none;'></div><div id='up-body' style='display:none;'><h3 align='center'>"+title+"</h3><form id='th_up' method='post' enctype='multipart/form-data'><center><input type='file' name='" + id + "' size='30' /><br /><input class='button-primary' type='submit' name='submit' value='upload' /><input type='hidden' name='logo_target' value='" + id + "' /><input type='hidden' name='action' value='wp_handle_upload' /><input type='hidden' name='anolox_action' value='"+type+"' /></center></form><a class='close-pup' href='javascript:_finish();'>CLOSE (X)</a></div>");
		var mL = parseFloat(jQuery(window).width());
		var mT = parseFloat(jQuery(window).height());
		
			var tp = parseFloat(Math.floor((mT-170)/2));
			var lt = parseFloat(Math.floor((mL-300)/2));
			
		jQuery('#up-body').css("left", lt + "px");
		jQuery('#up-th, #up-body').fadeIn(300, function()
		{
		
			jQuery('#up-body').animate({top : tp + "px"}, 500);
		
		});
	};

	function _finish(){
		jQuery('#up-body').remove();
		jQuery('#up-th').fadeOut(200, function(){jQuery(this).remove();});
	};
	
(function(z){
	
	function ap(){
		var field = z('.joo_file:last');
		var bt = z('#adding');
		
		bt.click(function(){
			var toIn = field.clone();
			
			toIn.insertBefore(this).find('input').val("");
			z('#_sd_number').val( z('.joo_file').length );
			
			return false;
		});
	}
	
	function box_edit(){
		var be = z('.box_edit');
		var bc = z('.box_edit_cancel');
		
		be.each(function(){
			z(this).click(function(){
				z('.box_cc').show();
				z(this).parents('.box_cc').hide();
				z('.box_ee').hide();
				z('#' + z(this).attr('rel') ).show();
				return false;
			})
		});
		
		bc.each(function(){
			z(this).click(function(){
				z('.box_cc').show();
				z('.box_ee').hide();
				return false;
			})
		});
	}
	
	function hs_edit(){
		var hse = z('.hs_edit');
		var hsc = z('.hs_edit_cancel');
		
		hse.each(function(){
			z(this).click(function(){
				z('.hs_cc').show();
				z(this).parents('.hs_cc').hide();
				z('.hs_ee').hide();
				z('#' + z(this).attr('rel') ).show();
				return false;
			})
		});
		
		hsc.each(function(){
			z(this).click(function(){
				z('.hs_cc').show();
				z('.hs_ee').hide();
				return false;
			})
		});
	}
	
	function ps_edit(){
		var pse = z('.ps_edit');
		var psc = z('.ps_edit_cancel');
		
		pse.each(function(){
			z(this).click(function(){
				z('.ps_cc').show();
				z(this).parents('.ps_cc').hide();
				z('.ps_ee').hide();
				z('#' + z(this).attr('rel') ).show();
				return false;
			})
		});
		
		psc.each(function(){
			z(this).click(function(){
				z('.ps_cc').show();
				z('.ps_ee').hide();
				return false;
			})
		});
	}


z(document).ready(function(){
	ap();
	box_edit();
	hs_edit();
	ps_edit();
});
})(jQuery);
