<?php 



/**
 * Displays a welcome panel to introduce users to WordPress.
 *
 * @since 3.3
 */
function takeover_welcome_panel() {
	global $wp_version;

	if ( ! current_user_can( 'edit_theme_options' ) )
		return;

	$classes = 'welcome-panel';

	$option = get_user_meta( get_current_user_id(), 'show_welcome_panel', true );
	/*
	// 0 = hide, 1 = toggled to show or single site creator, 2 = multisite site owner
	$hide = 0 == $option || ( 2 == $option && wp_get_current_user()->user_email != get_option( 'admin_email' ) );
	if ( $hide )
		$classes .= ' hidden';
	*/
	list( $display_version ) = explode( '-', $wp_version );
	?>
	<div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
	<a class="welcome-panel-close" href="<?php echo esc_url( admin_url( '?welcome=0' ) ); ?>"><?php _e('Dismiss'); ?></a>
	<div class="wp-badge"><?php printf( __( 'Version %s' ), $display_version ); ?></div>

	<div class="welcome-panel-content">
	<h3><?php _e( 'Welcome to your new Red Rokk site! ' ); ?></h3>
	<p class="about-description"><?php _e( 'If you need help getting started, check out our documentation on <a href="http://codex.wordpress.org/First_Steps_With_WordPress">First Steps with WordPress</a>. If you&#8217;d rather dive right in, here are a few things most people do first when they set up a new WordPress site. If you need help, use the Help tabs in the upper right corner to get information on how to use your current screen and where to go for more assistance.' ); ?></p>
	<div class="welcome-panel-column-container">
	<div class="welcome-panel-column">
		<h4><span class="icon16 icon-settings"></span> <?php _e( 'Basic Settings' ); ?></h4>
		<p><?php _e( 'Here are a few easy things you can do to get your feet wet. Make sure to click Save on each Settings screen.' ); ?></p>
		<ul>
		<li><?php echo sprintf(	__( '<a href="%s">Choose your privacy setting</a>' ), esc_url( admin_url('options-privacy.php') ) ); ?></li>
		<li><?php echo sprintf( __( '<a href="%s">Select your tagline and time zone</a>' ), esc_url( admin_url('options-general.php') ) ); ?></li>
		<li><?php echo sprintf( __( '<a href="%s">Turn comments on or off</a>' ), esc_url( admin_url('options-discussion.php') ) ); ?></li>
		<li><?php echo sprintf( __( '<a href="%s">Fill in your profile</a>' ), esc_url( admin_url('profile.php') ) ); ?></li>
		</ul>
	</div>
	<div class="welcome-panel-column">
		<h4><span class="icon16 icon-page"></span> <?php _e( 'Add Real Content' ); ?></h4>
		<p><?php _e( 'Check out the sample page & post editors to see how it all works, then delete the default content and write your own!' ); ?></p>
		<ul>
		<li><?php echo sprintf( __( 'View the <a href="%1$s">sample page</a> and <a href="%2$s">post</a>' ), esc_url( get_permalink( 2 ) ), esc_url( get_permalink( 1 ) ) ); ?></li>
		<li><?php echo sprintf( __( 'Delete the <a href="%1$s">sample page</a> and <a href="%2$s">post</a>' ), esc_url( admin_url('edit.php?post_type=page') ), esc_url( admin_url('edit.php') ) ); ?></li>
		<li><?php echo sprintf( __( '<a href="%s">Create an About Me page</a>' ), esc_url( admin_url('edit.php?post_type=page') ) ); ?></li>
		<li><?php echo sprintf( __( '<a href="%s">Write your first post</a>' ), esc_url( admin_url('post-new.php') ) ); ?></li>
		</ul>
	</div>
	<div class="welcome-panel-column welcome-panel-last">
		<h4><span class="icon16 icon-appearance"></span> <?php _e( 'Customize Your Site' ); ?></h4>
		<?php
		$ct = current_theme_info();
		if ( empty ( $ct->stylesheet_dir ) ) :
			echo '<p>';
			printf( __( '<a href="%s">Install a theme</a> to get started customizing your site.' ), esc_url( admin_url( 'themes.php' ) ) );
			echo '</p>';
		else:
			$customize_links = array();
			if ( 'twentyeleven' == $ct->stylesheet )
				$customize_links[] = sprintf( __( '<a href="%s">Choose light or dark</a>' ), esc_url( admin_url( 'themes.php?page=theme_options' ) ) );

			if ( current_theme_supports( 'custom-background' ) )
				$customize_links[] = sprintf( __( '<a href="%s">Set a background color</a>' ), esc_url( admin_url( 'themes.php?page=custom-background' ) ) );

			if ( current_theme_supports( 'custom-header' ) )
				$customize_links[] = sprintf( __( '<a href="%s">Select a new header image</a>' ), esc_url( admin_url( 'themes.php?page=custom-header' ) ) );

			if ( current_theme_supports( 'widgets' ) )
				$customize_links[] = sprintf( __( '<a href="%s">Add some widgets</a>' ), esc_url( admin_url( 'widgets.php' ) ) );

			if ( ! empty( $customize_links ) ) {
				echo '<p>';
				printf( __( 'Use the current theme &mdash; %1$s &mdash; or <a href="%2$s">choose a new one</a>. If you stick with %3$s, here are a few ways to make your site look unique.' ), $ct->title, esc_url( admin_url( 'themes.php' ) ), $ct->title );
				echo '</p>';
			?>
			<ul>
				<?php foreach ( $customize_links as $customize_link ) : ?>
				<li><?php echo $customize_link ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
			} else {
				echo '<p>';
				printf( __( 'Use the current theme &mdash; %1$s &mdash; or <a href="%2$s">choose a new one</a>.' ), $ct->title, esc_url( admin_url( 'themes.php' ) ) );
				echo '</p>';
			}
		endif; ?>
	</div>
	</div>
	<p class="welcome-panel-dismiss"><?php printf( __( 'Already know what you&#8217;re doing? <a href="%s">Dismiss this message</a>.' ), esc_url( admin_url( '?welcome=0' ) ) ); ?></p>
	</div>
	</div>
	<?php
}



add_action('all_admin_notices', 'takeover_index');
function takeover_index()
{
	if (get_current_screen()->base != 'dashboard') return;
	takeover_dashboard();
	die();
}

function takeover_dashboard() 
{
$title = __('Dashboard');
$parent_file = 'index.php';
	
$today = current_time('mysql', 1);
?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<?php takeover_welcome_panel(); ?>

<div id="dashboard-widgets-wrap">

<?php wp_dashboard(); ?>

<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->

<?php require(ABSPATH . 'wp-admin/admin-footer.php'); 
}