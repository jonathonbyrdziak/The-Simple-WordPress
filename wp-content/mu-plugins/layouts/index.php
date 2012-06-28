<?php 
/**
 * 
 */

// Constants
define('LAYOUT_PLUGIN_DIR', dirname(__file__).DIRECTORY_SEPARATOR);
define('LAYOUT_PLUGIN_URL', str_replace(ABSPATH, site_url('/'), LAYOUT_PLUGIN_DIR));

// Resources
require_once LAYOUT_PLUGIN_DIR.'metabox.class.php';

// Declaring a new metabox
redrokk_metabox_class::getInstance("layout_details", array(
		'title'			=> "Rokkin Layouts",
		'priority'		=> 'high',
		'context'		=> 'side',
		'callback'		=> 'layout_details_metabox',
	)
);

/**
 * Method handles the layouts from the admin area.
 * 
 * @param unknown_type $post
 * @param unknown_type $metabox
 */
function layout_details_metabox( $post, $metabox )
{
	wp_register_script('redlayouts', LAYOUT_PLUGIN_URL.'layouts.js', array(), time(), true);
	wp_enqueue_script('redlayouts');
	
	wp_deregister_script('jquery.ui');
	wp_register_script('jquery.ui', LAYOUT_PLUGIN_URL.'jquery-ui-1.8.21.custom.min.js', array('jquery'), '1.8.82');
	wp_enqueue_script('jquery.ui');
	
	?>
	<style type="text/css">
	/* Custom Scrollbar Styles */
	.rokkin_scroll::-webkit-scrollbar{width:9px;height:9px;}
	.rokkin_scroll::-webkit-scrollbar-button:start:decrement,#doc ::-webkit-scrollbar-button:end:increment{display:block;height:0;background-color:transparent;}
	.rokkin_scroll::-webkit-scrollbar-track-piece{background-color:#FAFAFA;-webkit-border-radius:0;-webkit-border-bottom-right-radius:8px;-webkit-border-bottom-left-radius:8px;}
	.rokkin_scroll::-webkit-scrollbar-thumb:vertical{height:50px;background-color:#999;-webkit-border-radius:8px;}
	.rokkin_scroll::-webkit-scrollbar-thumb:horizontal{width:50px;background-color:#999;-webkit-border-radius:8px;}
	
	.rokkin_scroll {display:none;width:258px;height:300px;overflow-y:scroll;margin-top:6px;}
	.layouts_design_item {width: 220px;text-align: center;font-size: 20px!important;height: 20px;margin:6px 0;}
	.layouts_content_item {width: 220px;margin:6px 0;}

	#titlediv {padding-bottom:8px}
	</style>
	
	<a id="layouts_design_mode" class="button rokkin_button" style="float:left;" href="#" data="#designmode">Design Mode</a>
	<a id="layouts_content_mode" class="button rokkin_button" style="float:right;" href="#" data="#contentmode">Content Mode</a>
	<div class="clear"></div>
	
	<div id="designmode" class="rokkin_scroll rokkin200">
		<?php do_action('layouts_design_mode', $post, $metabox); ?>
		<div class="clear"></div>
	</div>
	
	<div id="contentmode" class="rokkin_scroll rokkin100">
		<?php do_action('layouts_content_mode', $post, $metabox); ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<script type="text/javascript">
	</script>
	<?php 
}

add_action('layouts_content_mode','layouts_content_mode');
function layouts_content_mode( $post, $metabox )
{
	?>
	<div class="layouts_content_item button">1</div>
	<div class="layouts_content_item button">2</div>
	<div class="layouts_content_item button">3</div>
	<div class="layouts_content_item button">4</div>
	<div class="layouts_content_item button">5</div>
	<div class="layouts_content_item button">6</div>
	<div class="layouts_content_item button">7</div>
	<div class="layouts_content_item button">8</div>
	<?php 
}

