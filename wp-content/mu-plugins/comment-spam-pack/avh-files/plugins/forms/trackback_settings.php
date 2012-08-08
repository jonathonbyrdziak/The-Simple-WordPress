<div class="wrap">
	<h2><?php _e('Trackback Validation', 'avh-fdas');?></h2>

	<form action="settings.php" method="post">

	<?php settings_fields('avh-fdas-trackback'); ?>
	<?php do_settings_sections('avh-fdas_trackback_page'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</p>
	</form>

</div>

<script type="text/javascript">
(function ($) {
$(function() {

function toggle_allowed_urls() {
	if ($("#strict_checks-yes").is(":checked")) $("#allowed_urls").attr("disabled", true);
	else $("#allowed_urls").attr("disabled", false);
}

$("#strict_checks-yes").change(toggle_allowed_urls);
$("#strict_checks-no").change(toggle_allowed_urls);
toggle_allowed_urls();

});
})(jQuery);
</script>