<?php 

/**
 * Administrative Settings
 * 
 * Creating admin pages, is easy as that. There's a ton of work already completed for you, which will
 * allow you to offer seamless and stable administrative options pages to your users.
 */
$admin = redrokk_admin_class::getInstance('business_information', array(
	'page_title'	=> 'Business Information',
	'menu_title'	=> 'Business Info',
	'position'		=> 1, 
	'parent_menu'	=> 'settings',
	'screen_icon'	=> WPMU_PLUGIN_URL.'/utilities/business.png'
));

/**
 * Administrative Metaboxes
 * 
 * Make quick use of creating new metaboxes by using the metabox class
 *
 * @see https://gist.github.com/1880770
 */
redrokk_metabox_class::getInstance('website-info', array(
	'title'			=> 'Website',
	'_object_types'	=> $admin,
	'_fields'		=> array(
		array(
			'name' 	=> 'Logo',
			'id' 	=> 'logo',
			'type' 	=> 'file',
			'class'	=> 'regular-text',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Introductory Text',
			'id' 	=> 'description',
			'type' 	=> 'wpeditor',
			'class'	=> '',
			'desc'	=> "",
		),
		array(
			'name' => 'Link Contact To',
			'desc' => '',
			'id' => 'contact-page',
			'type' => 'select_pages',
		),
		array(
			'name' 	=> 'Call To Action',
			'id' 	=> 'cta',
			'type' 	=> 'text',
			'class'	=> '',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Point To',
			'id' 	=> 'point',
			'type' 	=> 'select',
			'class'	=> 'regular-text',
			'options'=> array(
				'left_align' 	=> 'Align Left',
				'centered' 		=> 'Center',
				'right_align' 	=> 'Align Right',		
			),
			'desc'	=> "",
		),
	)
)); 

redrokk_metabox_class::getInstance('contact-info', array(
	'title'			=> 'Contact Information',
	'_object_types'	=> $admin,
	'_fields'		=> array(
		array(
			'name' 	=> 'Phone Number',
			'id' 	=> 'number',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Toll Free',
			'id' 	=> 'tollfree',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Fax Number',
			'id' 	=> 'fax',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Address',
			'id' 	=> 'address',
			'type' 	=> 'textarea',
			'class'	=> '',
			'desc'	=> "",
		),
		array(
			'name' 	=> 'Contact Email',
			'id' 	=> 'contact-email',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "Enter an email if you don't want to use the admin email.",
		),
		array(
			'name' 	=> 'Business Hours',
			'id' 	=> 'business-hours',
			'type' 	=> 'wpeditor',
			'class'	=> 'medium',
			'desc'	=> "",
		),
	)
));

/**
 * 
 * @params array $column
 */
function business_mode_menu( $menu = array() )
{
	$menu[] = array (
		'check_callback' 	=> false,
		'capability' 		=> false,
		'url' 				=> 'options-general.php?page=business_information',
		'icon' 				=> WPMU_PLUGIN_URL.'/utilities/business.png',
		'title' 			=> 'Business Info',
		'help' 				=> __('Edit your Business Info'),
	);
	return $menu;
}
add_filter('easy_mode_menu', 'business_mode_menu');

