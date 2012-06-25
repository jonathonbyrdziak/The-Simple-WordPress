<?php

function htmlize_get_plain_text_message( $html ) {
	$h2t =& new html2text( $html );
	return $h2t->get_text(); 
}

function htmlize_get_blogname( ) {
	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	return wp_specialchars_decode( get_option('blogname'), ENT_QUOTES );
}

function htmlize_get_wp_email( ) {
	return 'wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
}

function htmlize_get_message_headers( ) {
	return "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
}

function htmlize_the_email_logo( ) {
	echo htmlize_get_email_logo();
}
function htmlize_get_email_logo( ) {
	$email_logo = htmlize_get_image( 'wp-watermark.png' );
	return apply_filters( 'html_email_email_logo', $email_logo );
}

function htmlize_the_image( $image_name ) {
	echo htmlize_get_image( $image_name );
}
function htmlize_get_image( $image_name ) {
	return HTMLIZE_URL . '/images/' . $image_name;
}

function htmlize_get_date_format() {
	$format = get_option('date_format');
	return ($format ? $format : 'F j, Y');
}

function htmlize_get_time_format() {
	$format = get_option('time_format');
	return ($format ? $format : 'g:i a');
}

function htmlize_the_gravatar( $email, $url, $size = 50 ) {
	echo htmlize_get_gravatar( $email, $url, $size );
}
function htmlize_get_gravatar( $email, $url, $size = 50 ) {
	$gravatar = get_avatar( $email, $size );
	
	$gravatar = htmlize_maybe_linkify( $url, $gravatar, array('_target' => 'blank' ) );
		
	return $gravatar;
}

function htmlize_maybe_linkify( $url, $content, $attributes = null ) {
	if( $url ) {
		$attributes_string = '';
		if( is_array( $attributes ) ) {
			foreach($attributes as $attribute => $value) {
				$attributes_string .= ' '. $attribute . '="'. $value .'"';
			}
		}
		if( !$content ) $content = $url;
		return '<a href="'. $url .'"'. $attributes_string.'>' . $content . '</a>';
	} else
		return $content;
}

function htmlize_the_time( $time, $format = '' ) {
	echo htmlize_get_time( $time, $format );
}
function htmlize_get_time( $time, $format = '' ) {
	if( !$format ) $format = htmlize_get_time_format();
	return mysql2date( $format, $time );
}
function htmlize_the_date( $date, $format = '' ) {
	echo htmlize_get_date( $date, $format );
}
function htmlize_get_date( $date, $format = '' ) {
	if( !$format ) $format = htmlize_get_date_format();
	return mysql2date( $format, $date );
}

function htmlize_the_whois_link( $ip, $content = '' ) {
	echo htmlize_get_whois_link( $ip, $content );
}
function htmlize_get_whois_link( $ip, $content = '' ) {
	$whois_url = 'http://ws.arin.net/cgi-bin/whois.pl?queryinput=' . $ip;
	return htmlize_maybe_linkify( $whois_url, $content );
}

function htmlize_the_comment_admin_link( $action, $comment_id ) {
	echo htmlize_get_comment_admin_link( $action, $comment_id );
}
function htmlize_get_comment_admin_link( $action, $comment_id ) {
	return admin_url() . "comment.php?action=$action&c=$comment_id";
}

function htmlize_the_comment_link( $post_id ) {
	echo htmlize_get_comment_link( $post_id );
}
function htmlize_get_comment_link( $post_id ) {
	return get_permalink( $post_id ) . '#comments';
}

function htmlize_get_comment_type_text( $comment_type = '' ) {
	// TODO: need support for custom comment types
	switch( $comment_type ) {
		case 'trackback':
			$comment_type_text = __('Trackback', 'html-emails');
			break;
		case 'pingback':
			$comment_type_text = __('Pingback', 'html-emails');
			break;
		case 'comment':
		default:
			$comment_type_text = __('Comment', 'html-emails');
			break;
	}
	return $comment_type_text;
}

function htmlize_get_user_profile_link( ) {
	return admin_url('profile.php');
}
function htmlize_get_user_edit_link( $user_id ) {
	return admin_url('user-edit.php?user_id=') . $user_id;
}
function htmlize_get_users_edit_link( $user_id ) {
	return admin_url('users.php');
}

function htmlize_the_action_button( $url, $content, $colour = '#21759B', $attributes = null ) {

	if( !is_array( $attributes ) ) $attributes = array();
	$attributes['style'] = 'padding: 3px 0; width: 100px; background: '. $colour .'; border: 1px solid '. $colour .'; text-align: center; border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px;';
	
	$attributes_string = '';
	if( is_array( $attributes ) ) {
		foreach($attributes as $attribute => $value) {
			$attributes_string .= ' '. $attribute . '="'. $value .'"';
		}
	}
	
	?>
	<td <?php echo $attributes_string; ?>>
		<a href="<?php echo $url; ?>" style="display: block; color:#fff; font-weight: bold;" target="_blank">
			<?php echo $content; ?>
		</a>
	</td>
	<?php
}

function htmlize_debug( ) {
	wp_notify_postauthor(1, 'comment');
	wp_notify_postauthor(48, 'trackback');
	wp_notify_moderator(1);
	wp_new_user_notification(1, 'abcd');
	wp_password_change_notification(new WP_User(1));
}
?>