<div class="wrap">

<h2><?php _e('Spam Filter', 'avh-fdas');?></h2>

<p>So far, this simple spam filter has prevented <strong><?php echo (int)$deleted_count; ?></strong> spam comments from ever hitting your system.</p>

<?php if ($words) { ?>
	<h3>Some spam got through?</h3>
	<p>No worries, you can inspect those now.</p>
	<p>Here are the words that are unique to your spam comments:</p>

	<ul>
	<?php foreach ($words as $word => $count) { ?>
		<li>
			<strong><?php echo $word;?></strong> (<?php echo $count;?>)
			<br />
			<?php if (!@$this->_data['no_stop_words'] && current_user_can('manage_network_options')) { ?>
				<?php if (in_array($word, $stop_words)) { ?>
					Already on your words blacklist
				<?php } else { ?>
					<a href="<?php echo admin_url('edit-comments.php?page=avh-fdas_spam_filter&action=stop_word&word=' . esc_attr($word));?>">Add to my words blacklist</a>
				<?php } ?>
			<?php } ?>
			<a href="<?php echo admin_url('edit-comments.php?page=avh-fdas_spam_filter&action=kill_spam&word=' . esc_attr($word));?>">Delete spam comments with this word</a>
		</li>
	<?php } ?>
	</ul>
<?php } ?>

</div>