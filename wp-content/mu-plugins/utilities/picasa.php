<?php 

/**
 * Method displays the albums for this user
 * 
 */
function picasa_display_albums( $post, $metabox )
{
	require_once (ABSPATH . WPINC . '/class-feed.php');

	$url = 'http://picasaweb.google.com/data/feed/api/user/'.urlencode(get_option('picasa_username')).'/';
	$feed = new SimplePie();
	
	$feed->set_feed_url( $url );
	$feed->set_stupidly_fast(true);
	$feed->enable_cache(false);
	$feed->set_cache_duration(0);
	$feed->set_file_class('WP_SimplePie_File');
	
	$feed->init();
	$feed->handle_content_type();
	
	if ($feed->error()) {
		echo $feed->error();
		return;
	}
	
	$maxitems = $feed->get_item_quantity(); 
	$items = $feed->get_items(0, $maxitems);
	
	$urls = get_post_meta($post->ID, 'picasa_sync');
	
	$i=0;
	echo '<ul style="width:100%;">';
	foreach ((array)$items as $item)
	{
		$i = $i>0 ?0 :++$i;
		
		//get image
		$media_group = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'group');
		$media_content = $media_group[0]['child'][SIMPLEPIE_NAMESPACE_MEDIARSS]['content'];
		$icon = $media_content[0]['attribs']['']['url'];
		
		$isActive = false;
		$link = $item->get_link() ?$item->get_link() :$item->get_permalink();
		if (in_array($link, $urls))
			$isActive = true;
		?>
		<li data="<?php echo urlencode($link) ?>" class="picasa_list_item <?php echo $isActive? 'picasa_active' :'' ?>" style="width:40%;margin-left:2%;margin-right:<?php echo $i ?'10%' :'0'; ?>;float:left;border:10px solid #ddd;">
			<h3><?php echo $item->get_title() ?></h3>
			<img src="<?php echo $icon ?>" style="width:100%;" />
		</li>
		<?php 
	}
	?>
	<div style="width:100%;clear:both;"></div></ul>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.picasa_list_item').bind('click', function(e){
				if (jQuery(this).hasClass('picasa_active'))
				{
					jQuery(this).removeClass('picasa_active');
					jQuery.ajax({
						url: ajaxurl, 
						data: {
							action:'picasa_unsync',
							postid: <?php echo $post->ID ?>,
							url:jQuery(this).attr('data')
						},
						success:function(response){
							console.log(response);
						}
					});
				}
				else
				{
					jQuery(this).addClass('picasa_active');
					jQuery.ajax({
						url: ajaxurl,
						data: {
							action:'picasa_sync',
							postid: <?php echo $post->ID ?>,
							url:jQuery(this).attr('data')
						},
						success:function(response){
							console.log(response);
						}
					});
				}
			});
		});
	</script>
	<style type="text/css">ul li.picasa_active{border:10px solid #627db4!important;}</style>
	<?php 
}

/**
 * Simple recording of the albums
 * 
 */
add_action('wp_ajax_picasa_sync', 'picasa_sync');
function picasa_sync()
{
	update_post_meta($_REQUEST['postid'], 'picasa_sync', urldecode($_REQUEST['url']));
	die('picasa_sync');
}

add_action('wp_ajax_picasa_unsync', 'picasa_unsync');
function picasa_unsync()
{
	delete_post_meta($_REQUEST['postid'], 'picasa_sync', urldecode($_REQUEST['url']));
	die('picasa_unsync');
}

/**
 * Administrative Settings
 * 
 * Creating admin pages, is easy as that. There's a ton of work already completed for you, which will
 * allow you to offer seamless and stable administrative options pages to your users.
 */
$picasa = redrokk_admin_class::getInstance('picasa-admin', array(
	'page_title'	=> 'Picasa Synchronization',
	'menu_title'	=> 'Picasa Sync',
	'parent_menu'	=> 'media',
	'screen_icon'	=> WPMU_PLUGIN_URL.'/utilities/PicasaIcon.jpg',
));

/**
 * Administrative Metaboxes
 * 
 * Make quick use of creating new metaboxes by using the metabox class
 * 
 * @see https://gist.github.com/1880770
 */
redrokk_metabox_class::getInstance('picasa-username', array(
	'title'			=> 'Picasa Setup',
	'_object_types'	=> $picasa,
	'priority'		=> 'high',
	'_fields'		=> array(
		array(
			'name' 	=> 'Picasa Username',
			'id' 	=> 'picasa_username',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "<br/>Please enter your Picasa username. If you don't know it, then you can enter your Google Email",
		),
	)
));

//function picasa_albums
if (get_option('picasa_username', false)) {
	redrokk_metabox_class::getInstance('picasa-albums', array(
		'title'			=> 'Picasa Albums',
		'_object_types'	=> $picasa,
		'priority'		=> 'high',
		'callback'		=> 'picasa_display_albums'
	));
	$sync = redrokk_metabox_class::getInstance('picasa-sync', array(
		'title'			=> 'Synchronization',
		'_object_types'	=> $picasa,
		'context'		=> 'side',
		'priority'		=> 'default',
		'_fields'		=> array(
			array(
				'name' 	=> 'Sync Regularly',
				'id' 	=> 'picasa_sync_regularly',
				'type' 	=> 'checkbox',
				'options'	=> array(
					'true'	=> "Yes",
				),
			),
		)
	));
}

/**
 * Delcaring my new cron
 * 
 */
$args = array(
	'callback' => 'picasa_cron',
	'schedule' => 10, // minutes
);
if (get_option('picasa_sync_regularly', 'false') !== 'true') {
	
	// Saves once
	add_action('metabox-save-'.$sync->_id, 'picasa_cron');
	
	// kills all future cron events for this slug
	$args['stop'] = 'true'; 
}
redrokk_cron_class::getInstance('picasa-cron', $args);

function picasa_cron()
{
	$post = redrokk_admin_class::getInstance('picasa-admin')->getPost();
	$urls = get_post_meta($post->ID, 'picasa_sync');
	
	require_once (ABSPATH . WPINC . '/class-feed.php');
	
	foreach ((array)$urls as $url) {
		$feed = new SimplePie();
		
		$feed->set_feed_url( $url );
		$feed->set_stupidly_fast(true);
		$feed->enable_cache(false);
		$feed->set_cache_duration(0);
		$feed->set_file_class('WP_SimplePie_File');
		
		$feed->init();
		$feed->handle_content_type();
		
		if ($feed->error()) {
			echo $feed->error();
			continue;
		}
		
		$maxitems = $feed->get_item_quantity(); 
		$items = $feed->get_items(0, $maxitems);
		
		foreach ((array)$items as $item) {
			
			//get image
			$media_group = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'group');
			$media_content = $media_group[0]['child'][SIMPLEPIE_NAMESPACE_MEDIARSS]['content'];
			
			$url  = $media_content[0]['attribs']['']['url'];
			$link = $item->get_link() ?$item->get_link() :$item->get_permalink();
			$title = $item->get_title();
			
			if (picasa_does_file_exist( $url )) continue;
			
			$file_array = picasa_save_file( $url );
			if (is_wp_error($file_array))
				continue;
			
			$post_data = array();
			$post_data['post_title'] = $title;
			
			$id = media_handle_sideload($file_array, $post->ID, $post_data);
			add_post_meta($id, 'imageurl', $url);
		}
		
	}
}

/**
 * Method determines if the file already exists in the system
 * 
 * @param string $url
 * @return bool
 */
function picasa_does_file_exist( $url )
{
	$posts = get_posts(array ( 
		'post_type' 	=> 'attachment', 
		'meta_value' 	=> $url, 
	));
	
	return ($posts) ?true :false;
}

/**
 * 
 * @param string $url
 */
function picasa_save_file( $url )
{
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	if (!preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches))
		return false;
		
	$tmp = download_url( $url );
	
	if ( is_wp_error( $tmp ) ) 
		return $tmp;
	
	
	$file_array = array();
	$file_array['name'] = basename($matches[0]);
	$file_array['tmp_name'] = $tmp;
	
	return $file_array;
}

