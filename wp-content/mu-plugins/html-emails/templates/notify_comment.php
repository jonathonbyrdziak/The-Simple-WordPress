<table width="100%" style="padding: 10px;">
	<tr>
		<td valign="top" style="width: 60px; margin-right: 7px;" class="table-avatar">
			<?php htmlize_the_gravatar($comment->comment_author_email, $comment->comment_author_url); ?>
		</td>
		<td valign="top">
			<div style="color: #999; font-size: 0.9em; margin-top: 4px;" class="meta">
				<strong>
					<?php echo htmlize_maybe_linkify( $comment->comment_author_url, $comment->comment_author, array('_target' => 'blank' )); ?>
					| <?php htmlize_the_date( $comment->comment_date ); ?> <?php htmlize_the_time( $comment->comment_date ); ?>
				</strong>
			</div>
			<?php if( $comment->comment_author_email || $comment->comment_author_IP ) : ?>
			<div>
				<?php if( $comment->comment_author_email ) echo htmlize_maybe_linkify( 'mailto:'.$comment->comment_author_email, $comment->comment_author_email ); ?>
				<?php if( $comment->comment_author_IP ) echo sprintf( __('(whois: %s)', 'html-emails'), htmlize_get_whois_link( $comment->comment_author_IP, $comment->comment_author_IP, array('_target' => 'blank' ) ) ); ?>
			</div>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 10px 0">
			<table>
				<tr>
					<td valign="top">
						<img align="left" src="<?php htmlize_the_image( 'blockquote.gif' ) ?>" alt="quote" style="margin-right: 10px;" />
					</td>
					<td valign="top">
						<blockquote style="font-size: 16px; font-family: Georgia, Times, Serif; margin: 0 0 15px 5px;"><?php echo wp_specialchars_decode( $comment->comment_content ); ?></blockquote>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr />
			<?php _e('Actions you can take:', 'html-emails') ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="5" cellpadding="3">
				<tr>
					<?php if( $comment_moderate ) : ?>
						<?php htmlize_the_action_button( htmlize_get_comment_admin_link('approve', $comment->comment_ID), __('Approve', 'html-emails'), '#006505' ); ?>
					<?php endif; ?>
					
					<?php if ( EMPTY_TRASH_DAYS ) : ?>
						<?php htmlize_the_action_button( htmlize_get_comment_admin_link('trash', $comment->comment_ID), __('Trash', 'html-emails'), '#BC0B0B' ); ?>
					<?php else : ?>
						<?php htmlize_the_action_button( htmlize_get_comment_admin_link('delete', $comment->comment_ID), __('Delete', 'html-emails'), '#BC0B0B' ); ?>
					<?php endif; ?>
					
					<?php htmlize_the_action_button( htmlize_get_comment_admin_link('spam', $comment->comment_ID), __('Spam', 'html-emails'), '#BC0B0B' ); ?>
					
					<?php htmlize_the_action_button( htmlize_get_comment_link($comment->comment_post_ID), sprintf( __('See all %ss', 'html-emails'), $comment_type_text), '#21759B' ); ?>
				</tr>
			</table>
			<?php if( $comment_moderate ) : ?>
			<p>
				<?php echo sprintf( _n('Currently <strong>%s</strong> comment is waiting for approval.', 'Currently <strong>%s</strong> comments are waiting for approval.', $comments_waiting), number_format_i18n($comments_waiting) ); ?>
				<a href="<?php echo admin_url('edit-comments.php?comment_status=moderated'); ?>" target="_blank">
					<?php _e('Visit moderation panel', 'html-emails'); ?>
				</a>
			</p>
			<?php endif; ?>
		</td>
	</tr>
</table>