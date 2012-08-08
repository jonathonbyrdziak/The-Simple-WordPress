<table width="100%" style="padding: 10px;">
	<tr>
		<td>
			<p><?php _e( 'The password was reset and changed for the following user:', 'html-emails' ); ?></p>
			
			<table width="100%" style="padding: 10px;">
				<tr>
					<td valign="top" style="width: 60px; margin-right: 7px;" class="table-avatar">
						<?php htmlize_the_gravatar( $user->user_email ); ?>
					</td>
					
					<td valign="top">
						<div style="color: #222; margin-top: 4px;">
							<strong><?php _e('Username: ', 'html-emails'); ?></strong>
							<?php echo $user->user_login; ?>
						</div>
						<div style="color: #222; margin-top: 4px;" >
							<strong><?php _e('Email: ', 'html-emails'); ?></strong>
							<?php echo htmlize_maybe_linkify( 'mailto:' . $user->user_email, $user->user_email ); ?>
						</div>
					</td>
				</tr>
			</table>
			
			<table cellspacing="5" cellpadding="3">
				<tr>
					<?php htmlize_the_action_button( htmlize_get_user_edit_link( $user->ID ), __('Edit User', 'html-emails') ); ?>
					<?php htmlize_the_action_button( htmlize_get_users_edit_link(), __('See All Users', 'html-emails') ); ?>
				</tr>
			</table>
		</td>
	</tr>
</table>