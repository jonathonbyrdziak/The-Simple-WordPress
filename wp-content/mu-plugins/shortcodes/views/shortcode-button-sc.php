<?php extract($args[1]); ?>
<a class="<?php echo $size; ?>-button shortcode" href="<?php echo $href; ?>" style="background-color: <?php echo $bgcolor; ?>; color: <?php echo $fontcolor; ?>;">
	<?php echo stripslashes( $text ); ?>
	<span class="button_to_left" style="background-color: <?php echo $bgcolor; ?>;"></span>
	<span class="button_to_right" style="background-color: <?php echo $bgcolor; ?>;"></span>
</a>