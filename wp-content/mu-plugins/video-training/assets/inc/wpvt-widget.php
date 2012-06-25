<?php

/***************************************************************
* Array of videos
* To make mangaging the videos easier
***************************************************************/

$training_videos = array( 
	
	array( 'id' => 'video1', 'title' => __('The Dashboard','wpvt'), 'url' => 'http://blip.tv/play/h697gsmjSAI.html', 'description' => __('An introduction to the WordPress administration panel and dashboard.','wpvt')),
	array( 'id' => 'video2', 'title' => __('Creating a new Post','wpvt'), 'url' => 'http://blip.tv/play/h697gsmkUgI.html', 'description' => __('This video will walk you through the use of the WordPress post/blog function.','wpvt')),
	array( 'id' => 'video3', 'title' => __('Editing a Post','wpvt'), 'url' => 'http://blip.tv/play/h697gsrsbAI.html', 'description' => __('How to edit a post once it has been created.','wpvt')),
	array( 'id' => 'video4', 'title' => __('Categories &amp; Tags','wpvt'), 'url' => 'http://blip.tv/play/h697gsrseQI.html', 'description' => __('Using categories and tags to organise your blog posts.','wpvt')),
	array( 'id' => 'video5', 'title' => __('Creating &amp; Editing Pages','wpvt'), 'url' => 'http://blip.tv/play/h697gszlUgI.html', 'description' => __('A introduction to WordPress pages.','wpvt')),
	array( 'id' => 'video6', 'title' => __('Add Photos &amp; Images','wpvt'), 'url' => 'http://blip.tv/play/h697gszlVwI.html', 'description' => __('Spice up your posts and pages with photos and images.','wpvt')),
	array( 'id' => 'video7', 'title' => __('How to Embed Video','wpvt'), 'url' => 'http://blip.tv/play/h697gs7OeAI.html', 'description' => __('Adding video to your site from popular sites such as YouTube.','wpvt')),
	array( 'id' => 'video8', 'title' => __('Using the Media Library','wpvt'), 'url' => 'http://blip.tv/play/h697gs7OQwI.html', 'description' => __('Keeping your media library clean and general file management.','wpvt')),	
	array( 'id' => 'video9', 'title' => __('Managing Comments','wpvt'), 'url' => 'http://blip.tv/play/h697gs_YMwI.html', 'description' => __('Learn to know your spam from your ham.','wpvt')),
	array( 'id' => 'video10', 'title' => __('How to Create a Link','wpvt'), 'url' => 'http://blip.tv/play/h697gs_YNgI.html', 'description' => __('Creating links using the rich text editor.','wpvt')),	
	array( 'id' => 'video11', 'title' => __('Changing the Theme','wpvt'), 'url' => 'http://blip.tv/play/h697gtDwRAI.html', 'description' => __('Tired of your site looking the same? Change your theme.','wpvt')),
	array( 'id' => 'video12', 'title' => __('Adding Widgets','wpvt'), 'url' => 'http://blip.tv/play/h697gtDwSgI.html', 'description' => __('Managing and adding widgets to your website.','wpvt')),
	array( 'id' => 'video13', 'title' => __('Building Custom Menus','wpvt'), 'url' => '', 'description' => __('Add and remove items from your websites navigation.','wpvt')),
	array( 'id' => 'video14', 'title' => __('Installing Plugins','wpvt'), 'url' => '', 'description' => __('Plugins allow you to easily add new functionality to your website','wpvt')),
	array( 'id' => 'video15', 'title' => __('Adding New Users','wpvt'), 'url' => '', 'description' => __('Managing users and permissions.','wpvt')),
	array( 'id' => 'video16', 'title' => __('Useful Tools','wpvt'), 'url' => '', 'description' => __('Helpful little extras within WordPress.','wpvt')),
	array( 'id' => 'video17', 'title' => __('Configuration Settings','wpvt'), 'url' => '', 'description' => __('Managing your website settings.','wpvt'))
	
);

/***************************************************************
* Functions wpvt_plugin_row_meta
* Add some handy shortcuts to the plugin admin screen
***************************************************************/

add_filter('plugin_row_meta', 'wpvt_plugin_row_meta', 10, 2);

function wpvt_plugin_row_meta($links, $file) {
	
	if ($file == WPVT_BASE) {

		$links[] = '<a href="'.get_admin_url().'index.php?edit=wpvt_widget#wpvt_widget">' . __('Settings','wpvt') . '</a>';
		$links[] = '<a href="http://scott.ee/">' . __('Support','wpvt') . '</a>';
		$links[] = '<a href="http://twitter.com/scottsweb">' . __('Twitter','wpvt') . '</a>';
		
	}

	return $links;
}

/***************************************************************
* Function wpvt_dashboard_widget_get_settings
* Register defualt settings and return array of settings
***************************************************************/

function wpvt_dashboard_widget_get_settings() {

	$defaults = array( 'video1' => 1, 'video2' => 1, 'video3' => 1, 'video4' => 1, 'video5' => 1, 'video6' => 1, 'video7' => 1, 'video8' => 1, 'video9' => 1, 'video10' => 1, 'video11' => 1, 'video12' => 1, 'video13' => 1, 'video14' => 1, 'video15' => 1, 'video16' => 1, 'video17' => 1);
	if ( ( !$options = get_option( 'wpvt_widget' ) ) || !is_array($options) )
		$options = array();
		return array_merge( $defaults, $options );
	
}

/***************************************************************
* Functions wpvt_add_dashboard_widget & wpvt_dashboard_widget & wpvt_dashboard_widget_settings
* Register and setup the widget
***************************************************************/

add_action('wp_dashboard_setup', 'wpvt_add_dashboard_widget' );

function wpvt_add_dashboard_widget() {
	wp_add_dashboard_widget('wpvt_widget', __('Video Tutorials','wpvt'), 'wpvt_dashboard_widget', 'wpvt_dashboard_widget_settings');
}

function wpvt_dashboard_widget() {

	global $training_videos;
	$options = wpvt_dashboard_widget_get_settings();
	
?>
	
	<style type="text/css">
		
		#TB_window
			{
			width: 622px !important;
			height: 410px !important;
			margin-top: 12% !important;
			}
			
		#TB_iframeContent
			{
			width: 622px !important;
			height: 380px !important;
			}
		
		#training-list-videos li span
			{
			display: block;
			color: #999999;
			}
		
	</style>


	<p><?php _e('The <a href="http://wp.tutsplus.com/tutorials/wp101-video-training-part-1-the-dashboard/" title="WPtuts+ WP101 tutorial series">WPtuts+ WP101 tutorial series</a> will guide you through everything you need to know about WordPress. If your new to WordPress we recommend taking the time to watch these training videos.','wpvt'); ?></p>
	<ol id="training-list-videos">
		<?php foreach ($training_videos as $video) { ?>
			<?php if ($video['url'] && isset($options[$video['id']])) { ?>
			<li><a href="<?php echo $video['url']; ?>?TB_iframe=true" title="<?php echo $video['title']; ?>" class="thickbox"><?php echo $video['title']; ?></a><span><?php echo $video['description']; ?></span></li>
			<?php } ?>
		<?php } ?>
	</ol>

<?php	
}

function wpvt_dashboard_widget_settings() {

	global $training_videos;
	$options = wpvt_dashboard_widget_get_settings();
	
	if ('post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'wpvt_widget' == $_POST['widget_id'] ) {
		foreach ($training_videos as $key )
			$options[$key['id']] = $_POST[$key['id']];
			update_option( 'wpvt_widget', $options );
	}
	
?>
	<p><?php _e('Choose the tutorials that are suitable for your website:', 'wpvt'); ?></p>

	<?php foreach ($training_videos as $video) { ?>
		<?php if ($video['url']) { ?>
		<label for="<?php echo $video['id']; ?>">
			<input id="<?php echo $video['id']; ?>" name="<?php echo $video['id']; ?>" type="checkbox" value="1" <?php if ( 1 == $options[$video['id']] ) echo 'checked="checked"'; ?> />
			<?php echo $video['title']; ?>
		</label>
	
		<br/>
		<?php } ?>
	<?php } ?>

<?php } ?>