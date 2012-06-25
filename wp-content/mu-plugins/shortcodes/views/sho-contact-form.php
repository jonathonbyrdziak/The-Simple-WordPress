<div id="adm-contact">
	<form action="<?php echo get_permalink(); ?>/#adm-contact" id="contact-form">
		<p>
			<span class="label"><?php echo __('Name', 'wip'); ?> </span>
			<input type="text" name="hname" id="hname" value="" />
			<span class="req"> *</span>
		</p>
		<p>
			<span class="label"><?php echo __('E-mail', 'wip'); ?> </span>
			<input type="text" name="hmail" id="hmail" value="" />
			<span class="req"> *</span>
		</p>
		<p>
			<span class="label"><?php echo __('Subject', 'wip'); ?> </span>
			<input type="text" name="hsubj" id="hsubj" value="" />
			<span class="req"> *</span>
		</p>
		<p style="vertical-align: top;">
			<span class="label"><?php echo __('Message', 'wip'); ?> </span>
			<textarea name="hmess" id="hmess" rows="2" cols="2" ></textarea>
			<span class="req"> *</span>
		</p>
		<p>
			<span class="label">&nbsp;</span>
			<input type="submit" name="submit" id="contact_submit" class="button" value="<?php echo  __('Send Message', 'wip'); ?>" />
			<span class="contactload" style="display: none;"><?php echo __('Please wait', 'wip'); ?></span>
		</p>
		<div class="clear"></div>
	</form>
</div>