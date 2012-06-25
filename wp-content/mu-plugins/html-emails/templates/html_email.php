<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div style="" class="content">
		<div style="padding: 10px; margin: 15px 10px 15px 10px; background-color: #E4F2FD; border: 1px solid #C6D9E9; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; line-height: 1.6em;" class="post">
			<table style="width: 100%;" class="post-details">
				<tr>
					<td valign="top">
						<h1 style="margin: 0; padding: 3px 0 10px 0; font-size: 20px; color: #555; font-family: Georgia, Times, Serif;" class="post-title">
							<?php echo $email_title ?>
							<span style="display:block; font-size: 14px; color: #999;"><?php echo $email_subtitle ?></span>
						</h1>
					</td>
				</tr>
			</table>
			<table class="content" width="100%" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; border: 1px solid #fff; background: #fff; margin: 5px 0;">
				<tr>
					<td>
						<?php htmlize_message_body($email_templates, $email_data); ?>
					</td>
				</tr>
			</table>
			<table class="footer">
				<tr>
					<td width="65">
						<img src="<?php htmlize_the_email_logo(); ?>" alt="WordPress logo" />
					</td>
					<td>
						<p>
							<a href="<?php bloginfo('url'); ?>" style="text-decoration: none; color: #0088cc;"><?php bloginfo('name'); ?></a> | <?php bloginfo('description'); ?><br />
							<small>
								<?php 
								/* translators: 1: email date, 2: email time */
								echo sprintf( __('This email was sent by WordPress on %1$s at %2$s', 'html-email'), date(htmlize_get_date_format()), date(htmlize_get_time_format()));
								?>
							</small>
						</p>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>