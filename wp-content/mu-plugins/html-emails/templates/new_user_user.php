<table width="100%" style="padding: 10px;">
	<tr>
		<td>
			<p>
				<?php if( $welcome_msg ) echo $welcome_msg; ?>
			</p>
			
			<table width="100%" style="padding: 10px;">
				<tr>
					<td valign="top" style="width: 60px; margin-right: 7px;" class="table-avatar">
						<?php htmlize_the_gravatar( $user->data->user_email ); ?>
					</td>
					<td valign="top">
						<div style="color: #222; margin-top: 4px;">
							<strong><?php _e('Username: ', 'html-emails'); ?></strong>
							<?php echo $user->data->user_login; ?>
						</div>
						<div style="color: #222; margin-top: 4px;">
							<strong><?php _e('Password: ', 'html-emails'); ?></strong>
							<?php echo $user_password; ?>
						</div>
					</td>
				</tr>
			</table>
			
			<table cellspacing="5" cellpadding="3">
				<tr>
					<?php htmlize_the_action_button( wp_login_url(), __('Login', 'html-emails'), '#006505' ); ?>
					<?php htmlize_the_action_button( htmlize_get_user_profile_link(), __('Edit Profile', 'html-emails') ); ?>
					<?php if( count( $links ) > 0 ) : ?>
						<?php foreach( $links as $link_url => $link_text ) : ?>
							<?php htmlize_the_action_button( $link_url, $link_text ); ?>
						<?php endforeach ?>
					<?php endif; ?>
					<?php if( $admin_email ) : ?>
						<?php htmlize_the_action_button( 'mailto:' . $admin_email, __('Contact Administrator', 'html-emails') ); ?>
					<?php endif; ?>
				</tr>
			</table>
		</td>
	</tr>
</table>