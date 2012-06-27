<?php 


/*
$userName = 'jonathonbyrd';
$url = 'http://picasaweb.google.com/data/feed/api/user/' .urlencode($userName) . '/';
$xml = wp_remote_fopen($url);
echo '<pre>';print_r($xml);die();
*/


/**
 * Administrative Settings
 * 
 * Creating admin pages, is easy as that. There's a ton of work already completed for you, which will
 * allow you to offer seamless and stable administrative options pages to your users.
 */
$picasa = redrokk_admin_class::getInstance('picasa-admin', array(
	'page_title'	=> 'Picasa Synchronization',
	'menu_title'	=> 'Picasa',
	'parent_menu'	=> 'tools', // if you choose not to add this, it will create a new menu for you
	// read the class properties for additional options
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

//echo get_option('picasa_username');die();

