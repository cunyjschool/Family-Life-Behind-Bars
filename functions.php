<?php
define( 'FLBB_VERSION', '0.3' );


if ( !class_exists( 'flbb' ) ) {

class flbb {
	
	var $options_group = 'flbb_';
	var $options_group_name = 'flbb_options';
	var $settings_page = 'flbb_settings';	
	
	/**
	 * __construct()
	 */
	function __construct() {
		
		add_action( 'after_setup_theme', array( &$this, 'init' ) );

		add_action( 'init', array( &$this, 'enqueue_resources' ) );
		add_action( 'init', array( &$this, 'register_menus' ) );
		
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		
		// Add the current options to our object
		$this->options = get_option( $this->options_group_name );
		
	} // END __construct()
	
	/**
	 * init()
	 */
	function init() {
		
		if ( is_admin() ) {
			add_action( 'admin_menu', array(&$this, 'add_admin_menu_items') );
		}		
		
	} // END init()
	
	/**
	 * admin_init()
	 */
	function admin_init() {

		$this->register_settings();

	} // END admin_init()
	
	/**
	 * add_admin_menu_items()
	 * Any admin menu items we need
	 */
	function add_admin_menu_items() {

		add_submenu_page( 'themes.php', 'Family Life Behind Bars Theme Options', 'FLBB Theme Options', 'manage_options', 'flbb_options', array( &$this, 'options_page' ) );			

	} // END add_admin_menu_items()	
	
	/**
	 * enqueue_resources()
	 * Enqueue any resources we need
	 */
	function enqueue_resources() {
		
		if ( !is_admin() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'neosmart_fb_wall_js', get_bloginfo('template_directory') . '/lib/jquery.neosmart.fb.wall/jquery.neosmart.fb.wall.js', array( 'jquery' ), FLBB_VERSION, true );
			wp_enqueue_style( 'neosmart_fb_wall_css', get_bloginfo('template_directory') . '/lib/jquery.neosmart.fb.wall/jquery.neosmart.fb.wall.css', false, FLBB_VERSION );
		} else {
		}
		
	} // END enqueue_resources()
	
	/**
	 * register_settings()
	 */
	function register_settings() {

		register_setting( $this->options_group, $this->options_group_name, array( &$this, 'settings_validate' ) );

		// Global options
		add_settings_section( 'flbb_fbwall', 'Homepage Facebook Wall', array(&$this, 'settings_fbwall_section'), $this->settings_page );
		add_settings_field( 'fbwall_enabled', 'Homepage Facebook Wall', array(&$this, 'settings_fbwall_enabled_option'), $this->settings_page, 'flbb_fbwall' );		
		add_settings_field( 'fbwall_id', 'Facebook ID', array(&$this, 'settings_fbwall_id_option'), $this->settings_page, 'flbb_fbwall' );

	} // END register_settings()
	
	/**
	 * settings_validate()
	 * Validation and sanitization on the settings field
	 */
	function settings_validate( $input ) {
		
		return $input;

	} // END settings_validate()
	
	/**
	 * Options page for the theme
	 */
	function options_page() {
		?>                                   
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br/></div>

			<h2><?php _e('Family Life Behind Bars Custom Theme Options', 'flbb-theme') ?></h2>

			<form action="options.php" method="post">

				<?php settings_fields( $this->options_group ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>

				<p class="submit"><input name="submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /></p>

			</form>
		</div>
		<?php
	} // END options_page()			
	
} // END class flbb

} // END if ( !class_exists( 'flbb' ) )

global $flbb;
$flbb = new flbb();

/**
 * flbb_share_this()
 */
function flbb_share_this() { ?>
	
	<span class='st_facebook' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_twitter' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_email' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_sharethis' st_title='{TITLE}' st_url='{URL}' ></span>

<?php } // END flbb_share_this()

?>