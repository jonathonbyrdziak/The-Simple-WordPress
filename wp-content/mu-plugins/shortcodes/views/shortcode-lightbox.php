<?php extract($args[1]); ?>
<link rel="stylesheet" href="<?php echo five_get_plugin_url('shortcodes'); ?>/modules/prettyPhoto/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script src="/?sho_script=../modules/prettyPhoto/setup.prettyPhoto" type="text/javascript"></script>

<?php 
switch ($type)
{
	case 'iframe':
		
		$width = $width?$width:'500';
		$height = $height?$height:'350';
		$link = $link?$link:'Click Here';
		?>
		
<a href="<?php echo $url; ?>?iframe=true&width=<?php echo $width; ?>&height=<?php echo $height; ?>" rel="shoLightbox">
	<?php echo $link; ?>
</a>
		
		<?php 
		break;
	case 'image':
	default:
		?>
		
<a href="<?php echo $url; ?>" rel="shoLightbox">
	<?php if ($link): ?>
		<?php echo $link; ?>
	<?php else: ?>
		<img src="<?php echo $url; ?>" <?php echo ($iwidth)?"width='$iwidth'":''; ?> <?php echo ($iheight)?"height='$iheight'":''; ?>/>
	<?php endif; ?>
</a>
		
		<?php 
		break;
}

?>
