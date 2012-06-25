<?php
if ( ! function_exists('wp_notify_postauthor') ) :
	function wp_notify_postauthor ( $comment_id, $comment_type='' ) {
		$comment = get_comment( $comment_id );
		$post    = get_post( $comment->comment_post_ID );
		$user    = get_userdata( $post->post_author );
		$current_user = wp_get_current_user();
		
		if ( $comment->user_id == $post->post_author ) return false; // The author moderated a comment on his own post
		
		if ( '' == $user->user_email ) return false; // If there's no email to send the comment to
		
		$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
		
		$blogname = htmlize_get_blogname();
		
		if ( empty( $comment_type ) ) $comment_type = 'comment';
		$comment_type_text = htmlize_get_comment_type_text( $comment_type );
		
		$email_data = array();
		$email_data['email_title']	 = sprintf( __('New %s on your post', 'html-emails'), $comment_type_text );
		/* translators: 1: post title, 2: post id */
		$subtitle = sprintf( __('<em>%1$s</em> #%2$s'), $post->post_title, $comment->comment_post_ID );
		$email_data['email_subtitle']  = htmlize_maybe_linkify( get_permalink( $comment->comment_post_ID ), $subtitle );
		$email_data['email_templates'] = array( "notify_postauthor_$comment_type.php", 'notify_postauthor.php', 'notify_comment.php' );
		
		$email_data['email_data'] = array(
				'comment' 			=> $comment,
				'comment_type' 		=> $comment_type,
				'comment_type_text' => $comment_type_text,
				'comment_moderate' 	=> false,
				'post'			=> $post,
				'user'			=> $user,
				'current_user' 	=> $current_user
			);
		
		$notify_message = htmlize_message( $email_data );
		/* translators: 1: blog name, 2: comment type, 3: post title */
		$subject = sprintf( __('[%1$s] %2$s: "%3$s"'), $blogname, $comment_type_text, $post->post_title );
		$wp_email = htmlize_get_wp_email();
		
		if ( '' == $comment->comment_author ) {
			$from = "From: \"$blogname\" <$wp_email>";
			if ( '' != $comment->comment_author_email )
				$reply_to = "Reply-To: $comment->comment_author_email";
		} else {
			$from = "From: \"$comment->comment_author\" <$wp_email>";
			if ( '' != $comment->comment_author_email )
				$reply_to = "Reply-To: \"$comment->comment_author_email\" <$comment->comment_author_email>";
		}
		
		$message_headers = "$from\n";
		$message_headers .= htmlize_get_message_headers();
		
		if ( isset($reply_to) )
			$message_headers .= $reply_to . "\n";
		
		$to_email = $user->user_email;
		$notify_message = apply_filters('comment_notification_text', $notify_message, $comment_id);
		$subject = apply_filters('comment_notification_subject', $subject, $comment_id);
		$message_headers = apply_filters('comment_notification_headers', $message_headers, $comment_id);
		
		wp_mail($to_email, $subject, $notify_message, $message_headers);
		
		return true;
	}
endif;

if ( !function_exists('wp_notify_moderator') ) :
	function wp_notify_moderator( $comment_id ) {
		global $wpdb;
		
		if( get_option( "moderation_notify" ) == 0 )
			return true;
		
		$comment = get_comment( $comment_id );
		$post = get_post( $comment->comment_post_ID );
		
		$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
		$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");
		
		$comment_type = $comment->comment_type ? $comment->comment_type : 'comment';
		$comment_type_text = htmlize_get_comment_type_text( $comment_type );
		
		$blogname = htmlize_get_blogname();
		
		$email_data = array();
		
		$email_data['email_title'] = sprintf( __('New %s awaiting approval'), $comment_type_text );
		/* translators: 1: post title, 2: post id */
		$subtitle = sprintf( __('<em>%1$s</em> #%2$s'), $post->post_title, $comment->comment_post_ID );
		$email_data['email_subtitle'] = htmlize_maybe_linkify( get_permalink( $comment->comment_post_ID ), $subtitle );
		$email_data['email_templates'] = array( "notify_moderator_$comment_type.php", 'notify_moderator.php', 'notify_comment.php' );
		
		$email_data['email_data'] = array(
			'comment' 			=> $comment,
			'comment_type' 		=> $comment_type,
			'comment_type_text' => $comment_type_text,
			'comment_moderate' 	=> true,
			'comments_waiting'  => $comments_waiting,
			'post'				=> $post
		);
		
		$notify_message = htmlize_message( $email_data );
		
		/* translators: 1: blog name, 2: post title */
		$subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );
		$to_email = get_option('admin_email');
		
		$message_headers = htmlize_get_message_headers();
		
		$notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
		$subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
		$message_headers = apply_filters('comment_moderation_headers', $message_headers);
		
		@wp_mail($to_email, $subject, $notify_message, $message_headers);
		
		return true;
	}
endif;

if ( !function_exists('wp_password_change_notification') ) :
	function wp_password_change_notification(&$user) {
		$wp_user = new WP_User( $user->ID );
		
		$admin_email = get_option('admin_email');
		
		// send a copy of password change notification to the admin
		// but check to see if it's the admin whose password we're changing, and skip this
		if ( $wp_user->user_email != $admin_email ) {
			
			$blogname = htmlize_get_blogname();
			$subject = sprintf(__('[%s] Password Changed', 'html-emails'), $blogname);
			
			$email_data = array(
				'email_title' => 'Password Changed',
				'email_subtitle' => '',
				'email_templates' => array( 'password_change_admin.php' ),
			);
			
			$email_data['email_data'] = array(
				'user' => $wp_user,
			);
			
			$message = htmlize_message( $email_data );
			$message_headers = htmlize_get_message_headers();
			
			wp_mail($admin_email, $subject, $message, $message_headers);
		}
	}
endif;

if ( !function_exists('wp_new_user_notification') ) :
	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
		$user = new WP_User( $user_id );
		
		wp_new_user_admin_notification( $user );
		
		if ( empty($plaintext_pass) )
			return;
		
		wp_new_user_user_notification( $user, $plaintext_pass );
	}
	
	function wp_new_user_admin_notification( $user ) {
		$admin_email = get_option('admin_email');
		$user_login = stripslashes($user->user_login);
		$user_email = stripslashes($user->user_email);
		
		$blogname = htmlize_get_blogname();
		
		$subject = sprintf(__('[%s] New User Registration', 'html-emails'), $blogname);
		
		$email_data = array(
			'email_title' => 'New user registration',
			'email_subtitle' => '',
			'email_templates' => array( 'new_user_admin.php' )
		);
		
		$email_data['email_data'] = array(
			'user' => $user,
			'blogname' => $blogname,
		);
		
		$message = htmlize_message( $email_data );
		$message_headers = htmlize_get_message_headers();
		
		@wp_mail($admin_email, $subject, $message, $message_headers);
	}
	
	function wp_new_user_user_notification( $user, $password ) {
		
		$blogname = htmlize_get_blogname();
		
		$user_email = $user->user_email;
		$welcome_msg = sprintf( __('Hello %s. Your account has been created and is ready. Your login information is below! Happy WordPress-ing!', 'html-emails'), $user->display_name);
		$links = array();
		
		$email_data = array(
			'email_title' => 'Welcome to ' . $blogname,
			'email_subtitle' => '',
			'email_templates' => array( 'new_user_user.php' )
		);
		
		$email_data['email_data'] = array(
			'user' => $user,
			'user_password' => $password,
			'welcome_msg' => apply_filters('wp_new_user_notification_welcome_message', $welcome_msg), // configurable welcome message
			'links' => apply_filters('wp_new_user_notification_links', $links),
			'admin_email' => apply_filters('wp_new_user_notification_admin_email', ''),
		);
		
		$subject = sprintf(__('[%s] Your username and password', 'html-emails'), $blogname);
		
		$message = htmlize_message( $email_data );
		$message_headers = htmlize_get_message_headers();
		
		wp_mail($user_email, $subject, $message, $message_headers);
	}
	
endif;

?>