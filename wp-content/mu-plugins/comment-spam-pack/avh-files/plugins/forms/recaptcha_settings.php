<div class="wrap">
	<h2><?php _e('ReCaptcha', 'avh-fdas');?></h2>

	<form action="settings.php" method="post">

	<?php settings_fields('avh-fdas-recaptcha'); ?>
	<?php do_settings_sections('avh-fdas_recaptcha_page'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</p>
	</form>

</div>

<script type="text/javascript">
(function($) {
$(function () {

function toggle_themes () {
	var full = $("#appearance").val();
	if ('full' == full) {
		$('input[name="avh-recaptcha[theme]"]').attr('disabled', false);
		$("#avh-recaptcha-themes").show();
	} else {
		$('input[name="avh-recaptcha[theme]"]').attr('disabled', true);
		$("#avh-recaptcha-themes").hide();
	}
}

$("#appearance").change(toggle_themes);
toggle_themes();

});
})(jQuery);
</script>