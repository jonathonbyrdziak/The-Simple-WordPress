<div class="wrap">
	<h2><?php _e('Spam Filter', 'avh-fdas');?></h2>

	<form action="settings.php" method="post">

	<?php settings_fields('avh-fdas-spam_filter'); ?>
	<?php do_settings_sections('avh-fdas_spam_filter_page'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</p>
	</form>

</div>