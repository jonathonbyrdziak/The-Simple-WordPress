<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.jonathonbyrd.com
 * @Package Wordpress
 * @copyright Proprietary Software, Copyright Byrd Incorporated. All Rights Reserved
 * @Since 1.0.0
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Constructor Function.
 * 
 */
function sho_initialize()
{
	//STYLES!
	wp_register_style('sho-css', site_url('/wp-content/mu-plugins/shortcodes/css/shortcodes.css'), array(), SHO_VERSION, 'all');
	wp_enqueue_style('sho-css');
	
	//ACTION!
	//add_action('plugins_loaded', 'twc_current_user_can', 1);
	add_action('init', 'sho_wp');
	add_action('init', 'sho_add_shortcode_button');
	add_filter('the_content', 'shortcode_clear_this', 1);
	
	//FILTERS!
	add_filter('tiny_mce_before_init', 'sho_add_editor_styles');
	
	//SHORTCODES!
	add_shortcode('message_block', 'sho_openingmess');
	add_shortcode('youtube', 'sho_youtube_shortcode');
	add_shortcode('vimeo', 'sho_vimeo_shortcode');
	add_shortcode('megavideo', 'sho_megavideo_shortcode');
	add_shortcode('flv_video', 'sho_fp_shortcode');
	add_shortcode('mp4_video', 'sho_fp_shortcode');
	add_shortcode('3gp_video', 'sho_qt_shortcode');
	add_shortcode('mov_video', 'sho_qt_shortcode');
	add_shortcode('contactform', 'sho_inter_contactform');
	add_shortcode('toggle', 'sho_toggle_sc');
	add_shortcode('tabgroup', 'sho_tab_group');
	add_shortcode('tab', 'sho_etdc_tab');
	add_shortcode('button', 'sho_button_sc');
	add_shortcode('sho_lightbox', 'sho_lightbox');
}

function shortcode_clear_this( $content )
{
	$content = str_replace(array('<!--clear-->','<!-- clear -->'), '<div class="clearfix clear"></div>', $content);
	return $content;
}

function sho_wp()
{
	//add_editor_style( get_bloginfo('url').'/wp-content/mu-plugins/shortcodes/css/shortcodes.css' );
	//add_editor_style( get_bloginfo('url').'/wp-content/mu-plugins/shortcodes/css/editor-style.css' );
	add_editor_style( '../../mu-plugins/shortcodes/css/shortcodes.css' );
	add_editor_style( '../../mu-plugins/shortcodes/css/editor-style.css' );
	
}

/**
 * Function does the actual bind of WYSIWYG button events
 * 
 */
function sho_add_shortcode_button()
{
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
	 
	if ( get_user_option('rich_editing') == 'true')
	{
		add_filter('mce_external_plugins', 'sho_add_shortcode_tinymce_plugin');
		add_filter('mce_buttons_3', 'sho_register_shortcode_button');
	}
}

/**
 * 
 * add_filter('mce_external_plugins', 'add_shortcode_tinymce_plugin');
 * 
 * @param unknown_type $plugin_array
 */
function sho_add_shortcode_tinymce_plugin($plugin_array)
{
	$plugin_array['shoshortcode'] = site_url('/wp-content/mu-plugins/shortcodes/js/s_column.js');
	$plugin_array['shoactiveonselect'] = site_url('/wp-content/mu-plugins/shortcodes/js/plugin_activeonselect.js');
	return $plugin_array;
}

/**
 * 
 * add_filter('mce_buttons_3', 'sho_register_shortcode_button');
 * 
 * @param $buttons
 */
function sho_register_shortcode_button( $buttons )
{
	array_push($buttons, "styleselect", "|", "2_columns", "3_columns", "4_columns", "|", 
		"1-2-3_columns", "213_columns", "112_columns", "121_columns", "211_columns", "13_columns", "31_columns",
		"|", "divider", "dropcap", "quot_left", "quot_right", "|", "tabs", "toggle", "sho_video", "sho_button", "sho_lightbox");
	return $buttons;
}

/**
 * 
 * add_filter('tiny_mce_before_init', 'sho_add_editor_styles');
 * 
 * @param $init_array
 */
function sho_add_editor_styles( $init_array )
{
	$arr = array(
		"Arrow lists" => "list_arrow",
		"Delete lists" => "list_delete",
		"Document lists" => "list_document",
		"Gear lists" => "list_gear",
		"Heart lists" => "list_heart",
		"Help lists" => "list_help",
		"Label lists" => "list_label",
		"Pencil lists" => "list_pencil",
		"Plus lists" => "list_plus",
		"Tick lists" => "list_tick",
		"Trash lists" => "list_trash",
		"Warning lists" => "list_warning",
	);
	
	$arrq = array();
	foreach($arr as $k=>$v)
		$arrq[] = "$k=$v";
		
	$f = implode(";",$arrq);
	
	$init_array['theme_advanced_styles'] = $f;
	return $init_array;
}

/**
 * Should eventually upgrade this to include all of the 
 * following
 * http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_lightbox($atts, $content = null )
{
	$atts = shortcode_atts(array(
		'type'		=> 'image',
		'url'		=> '',
		'height'	=> '',
		'width'		=> '',
		'iheight'	=> 100,
		'iwidth'	=> 100,
		'link'		=> '',
		'content'	=> $content,
	), $atts);
	
	return sho_get_show_view('shortcode-lightbox', $atts);
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_button_sc($atts, $content = null )
{
	$atts = shortcode_atts(array(
		'size'		=> 'medium',
		'href'		=> '#',
		'bgcolor'	=> '#555555',
		'fontcolor'	=> '#FFFFFF',
		'text'	=> 'Button text',
		'content'	=> $content,
	), $atts);
	
	return sho_get_show_view('shortcode-button-sc', $atts);
}

/**
 * 
 * @param $atts
 * @param $content
 */
function sho_etdc_tab( $atts, $content )
{
	extract(shortcode_atts(array(
		'title' => 'Tab %d'
	), $atts));

	ob_start();
		$x = $GLOBALS['tab_count'];
		$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' =>  $content );

		$GLOBALS['tab_count']++;
	ob_get_clean();
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_tab_group( $atts, $content )
{	
	ob_start();
	
		$GLOBALS['tab_count'] = 0;
		do_shortcode( $content );
						
		if( is_array( $GLOBALS['tabs'] ) ){
			foreach( $GLOBALS['tabs'] as $a => $tab ){
			
				$tabs[] = '<li><a class="" href="#" rel="'.$a.'"><span>'.$tab['title'].'</span></a></li>';
				$panes[] = '<div class="pane">'.do_shortcode($tab['content']).'</div>';
			
			}
			
			echo '<div class="breaks_tab shortcode">' . "\n".'<ul class="tabs">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="panes">'.implode( "\n", $panes ).'</div>'."\n". '</div>' . "\n";
		
		}
	
	$tabs = ob_get_clean();	
		
	return $tabs;
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_toggle_sc($atts, $content = null )
{
	extract(shortcode_atts(array(
		'title'		=> 'Title of toggle',
	), $atts));
	
	ob_start();
		
		$return = '<div class="toggle_container shortcode">' . "\n";
		$return .= '<h3 class="toggle_title">'.$title.'<span class="toggle_indicator"></span></h3>' . "\n";
		$return .= '<div class="toggle_body">' . "\n";
			$return .= do_shortcode($content);
		$return .= '<div class="clear"></div>' . "\n";
		$return .= '</div>' . "\n";
		$return .= '</div>' . "\n";
		
		echo $return;
		
	$toggle = ob_get_clean();
	
	return $toggle;
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_inter_contactform( $atts, $content = null )
{
	return get_show_view('sho-contact-form');
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_qt_shortcode($atts, $content = null )
{
	extract(shortcode_atts(array(
		'url'		=> '',
		'width'		=> '600',
		'height'	=> '367',
		'align'		=> 'left',
		'autoplay'	=> 'false',
	), $atts));
	
	$class= "";
	if( $align == 'left' )$class=" alignleft";
	if( $align == 'center' )$class=" aligncenter";
	if( $align == 'right' )$class=" alignright";
	
	ob_start();
		
		if(empty($url) ) echo __('Shortcode ERROR! You MUST enter the video URL', 'sho');
		
		$before = '<div class="motion'.$class.'">';
		$after = '</div>' . "\n" . '<div class="clear"></div>' . "\n";
		
		echo $before.sho_print_object($url, 'quicktime', $width, $height, $autoplay).$after;
		
	$flowp = ob_get_clean();
	return $flowp;
}

/**
 * 
 * @param $atts
 * @param $content
 */
function sho_fp_shortcode($atts, $content = null )
{
	extract(shortcode_atts(array(
		'url'		=> '',
		'width'		=> '600',
		'height'	=> '367',
		'align'		=> 'left',
		'autoplay'	=> 'false',
	), $atts));
	
	$class= "";
	if( $align == 'left' )$class=" alignleft";
	if( $align == 'center' )$class=" aligncenter";
	if( $align == 'right' )$class=" alignright";
	
	ob_start();
		
		if(empty($url) ) echo __('Shortcode ERROR! You MUST enter the video URL', 'sho');
		
		$before = '<div class="motion'.$class.'">';
		$after = '</div>' . "\n" . '<div class="clear"></div>' . "\n";
		
		echo $before.sho_print_object($url, 'flowplayer', $width, $height, $autoplay).$after;
		
	$sleftho = ob_get_clean();
	return $sleftho;
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_megavideo_shortcode($atts, $content = null )
{
	extract(shortcode_atts(array(
		'url'		=> '',
		'width'		=> '600',
		'align'		=> 'left',
		'height'	=> '367',
	), $atts));
	
	$class= "";
	if( $align == 'left' )$class=" alignleft";
	if( $align == 'center' )$class=" aligncenter";
	if( $align == 'right' )$class=" alignright";
	
	ob_start();
		
		if(empty($url) ) echo __('Shortcode ERROR! You MUST enter the megavideo URL', 'sho');
	
		$before = '<div class="motion'.$class.'">';
		$after = '</div>' . "\n" . '<div class="clear"></div>' . "\n";
		
		echo $before.sho_print_object($url, 'megavideo', $width, $height, '').$after;
		
	$mega = ob_get_clean();
	return $mega;
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_vimeo_shortcode($atts, $content = null )
{
	extract(shortcode_atts(array(
		'url'		=> '',
		'width'		=> '600',
		'height'	=> '367',
		'align'		=> 'left',
		'autoplay'	=> 'false',
	), $atts));
	
	$class= "";
	if( $align == 'left' )$class=" alignleft";
	if( $align == 'center' )$class=" aligncenter";
	if( $align == 'right' )$class=" alignright";
	
	ob_start();
		
		if(empty($url) ) echo __('Shortcode ERROR! You MUST enter the vimeo URL', 'sho');
		
		$autoplay = ( $autoplay == "true" ) ? "1" : "0";
		
		$before = '<div class="motion'.$class.'">';
		$after = '</div>' . "\n" . '<div class="clear"></div>' . "\n";
		
		echo $before.sho_print_object($url, 'vimeo', $width, $height, $autoplay).$after;
		
	$vimeo = ob_get_clean();
	return $vimeo;
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 */
function sho_youtube_shortcode($atts, $content = null )
{
	extract(shortcode_atts(array(
		'url'		=> '',
		'width'		=> '600',
		'height'	=> '367',
		'align'		=> 'left',
		'autoplay'	=> 'false',
	), $atts));
	
	$class= "";
	if( $align == 'left' )$class=" alignleft";
	if( $align == 'center' )$class=" aligncenter";
	if( $align == 'right' )$class=" alignright";

	ob_start();

		if(empty($url) ) echo __('Shortcode ERROR! You MUST enter the youtube URL', 'sho');
		
		$autoplay = ( $autoplay == "true" ) ? "1" : "0";
		
		$before = '<span class="motion'.$class.'">';
		$after = '</span>';
		
		echo $before.sho_print_object($url, 'youtube', $width, $height, $autoplay).$after;
		
	$youtube = ob_get_clean();
	return $youtube;
	
}

/**
 * 
 * @param unknown_type $atts
 * @param unknown_type $content
 * @return string
 */
function sho_openingmess( $atts, $content = null ){
	ob_start();
		echo '<div class="message_block shortcode">';
		echo '<h1>' . $content . '</h1>';
		echo '</div>';
	$message = ob_get_clean();
	return $message;
}


/**
 * Function is responsible for setting the actual capability check
 * after the plugins are loaded and the cookie is also loaded.
 * 
 */
function sho_current_user_can()
{
	defined("SHO_CURRENT_USER_CAN") or define("SHO_CURRENT_USER_CAN", (current_user_can(SHO_ACCESS_CAPABILITY)) );
	defined("SHO_CURRENT_USER_CANNOT") or define("SHO_CURRENT_USER_CANNOT", (!SHO_CURRENT_USER_CAN) );
	
}

/**
 * This is function to display portfolio objects
 * 
 * @param $url --> the url of data (vimeo, flash, youtube, .mov)
 * @param $type = type of portfolio data (it can be youtube, vimeo, etc)
 * @param $width = width of object
 * @param $height = height of object
 * @return data string
 */
function sho_print_object($url, $type, $width, $height, $autoplay){
	$return = "";
	$flash 	= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}">
		<param name="wmode" value="transparent" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="movie" value="{path}" />
		<embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="transparent">
		</embed>
		</object>';
	
	$vimeo	= '<object width="{width}" height="{height}">
		<param name="wmode" value="transparent" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id={path}&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;fullscreen=1&amp;autoplay={autoplay}" />
		<embed src="http://vimeo.com/moogaloop.swf?clip_id={path}&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;fullscreen=1&amp;autoplay={autoplay}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="transparent">
		</embed></object>';
	
	$flow_p = '<object id="{id}" width="{width}" height="{height}" data="{flowpath}" type="application/x-shockwave-flash">
		<param name="wmode" value="opaque" />
		<param name="movie" value="{flowpath}" />
		<param name="bgcolor" value="0x000000" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptacces" value="always" />
		<param name="flashvars" value=\'config={"clip":{"url":"{path}","autoPlay":{autos},"autoBuffering":true, "scaling": "fit"}}\' />
		<embed type="application/x-shockwave-flash" width="{width}" height="{height}" wmode="opaque" bgcolor="0x000000" allowscriptacces="always" src="{flowpath}" flashvars=\'config={"clip":{"url":"{path}","autoPlay":{autos},"autoBuffering":true, "scaling": "fit"}}\'/>
		</object>';
	
	$quicktime 	= '<object classid="clsid:02bf25d5-8c17-4b23-bc80-d3488abddc6b" codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0" height="{height}" width="{width}">
		<param name="src" value="{path}"/>
		<param name="autoplay" value="{autoplay}"/>
		<param name="scale" value="tofit"/>
		<param name="type" value="video/quicktime"/>
		<embed src="{path}" scale="tofit" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">
		</embed>
		</object>';

	switch ( $type ) {
		case "youtube":
			
			if($autoplay == "true") $autoplay = "1";
			if($autoplay == "false") $autoplay = "0";
		
			$parsed_url = parse_url($url);
			if( isset( $parsed_url['query'] ) ){
				
				parse_str($parsed_url['query'], $parsed_query);
				
				if( isset( $parsed_query['v'] ) ) $mov = $parsed_query['v'];
			
			}
			
			$movie = 'http://www.youtube.com/v/' . $mov . '?fs=1&amp;hl=en_US&amp;autoplay={autoplay}';
			$movie = str_replace('{autoplay}', $autoplay, $movie);
			
			$return = str_replace('{path}', $movie, $flash);
			$return = str_replace('{width}', $width, $return);
			$return = str_replace('{height}', $height, $return);
		break;
		case "vimeo":
			$mov = str_replace('/www.', '/', $url);
			$mov = str_replace('http://vimeo.com/', '', $mov);
			$movie = $mov;
			
			$return = str_replace('{path}', $movie, $vimeo);
			$return = str_replace('{width}', $width, $return);
			$return = str_replace('{height}', $height, $return);
			$return = str_replace('{autoplay}', $autoplay, $return);
		break;
		case "quicktime":
			$movie = $url;
			
			$return = str_replace('{path}', $movie, $quicktime);
			$return = str_replace('{width}', $width, $return);
			$return = str_replace('{height}', $height, $return);
			$return = str_replace('{autoplay}', $autoplay, $return);
		break;
		case "flash":
			$movie = $url;
			
			$return = str_replace('{path}', $movie, $flash);
			$return = str_replace('{width}', $width, $return);
			$return = str_replace('{height}', $height, $return);
		break;
		case "flowplayer":
			$movie = $url;
			
			$return = str_replace('{path}', $movie, $flow_p);
			$return = str_replace('{autos}', $autoplay, $return);
			$return = str_replace('{id}', sho_takeid($movie), $return);
			$return = str_replace('{flowpath}', get_bloginfo('site').'/wp-content/mu-plugins/shortcodes/modules/flowplayer/flowplayer-3.2.5.swf', $return);
			$return = str_replace('{width}', $width, $return);
			$return = str_replace('{height}', $height, $return);
			
		break;
		case "image":
			$return = '<img src="'.$url.'" alt="" />';
		break;
	}
	
	return $return;
}

/**
 * 
 * @param unknown_type $url
 * @return string
 */
function sho_takeid($url){
	return "flow" . md5($url);
}