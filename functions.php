<?php
define( 'FLBB_VERSION', '0.4' );


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
		
		if ( is_admin_bar_showing() ) {
			add_action( 'admin_bar_menu', array( &$this, 'add_admin_bar_items' ), 70 );
		}
		
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
	 * add_admin_bar_items()
	 * Custom items for the FLBB theme to WordPress' admin bar
	 */
	function add_admin_bar_items() {
		global $wp_admin_bar;
		
		// Add theme management links for users who can	
		if ( current_user_can('edit_theme_options') ) {
			$args = array(
				'title' => 'Theme Options',
				'href' => admin_url( 'themes.php?page=flbb_options' ),
				'parent' => 'appearance',
			);
			$wp_admin_bar->add_menu( $args );
		}
		
	} // END add_admin_bar_items()
	
	/**
	 * enqueue_resources()
	 * Enqueue any resources we need
	 */
	function enqueue_resources() {		
		
		if ( !is_admin() ) {
			wp_enqueue_script( 'jquery' );
			// Only add the Facebook wall functionality if it's enabled
			$options = $this->options;	 		
			if ( isset( $options['fbwall_enabled'] ) && $options['fbwall_enabled'] == 'on' ) {
				wp_enqueue_script( 'neosmart_fb_wall_js', get_stylesheet_directory_uri() . '/lib/jquery.neosmart.fb.wall/jquery.neosmart.fb.wall.js', array( 'jquery' ), FLBB_VERSION );
				wp_enqueue_style( 'neosmart_fb_wall_css', get_stylesheet_directory_uri() . '/lib/jquery.neosmart.fb.wall/jquery.neosmart.fb.wall.css', false, FLBB_VERSION );
			}
		}
		
	} // END enqueue_resources()
	
	/**
	 * register_settings()
	 */
	function register_settings() {

		register_setting( $this->options_group, $this->options_group_name, array( &$this, 'settings_validate' ) );

		// Global options
		add_settings_section( 'flbb_fbwall', 'Homepage Facebook Wall', array( &$this, 'settings_fbwall_section' ), $this->settings_page );	
		add_settings_field( 'fbwall_enabled', 'Homepage Facebook Wall', array( &$this, 'settings_fbwall_enabled_option' ), $this->settings_page, 'flbb_fbwall' );		
		add_settings_field( 'fbwall_id', 'Facebook ID', array( &$this, 'settings_fbwall_id_option' ), $this->settings_page, 'flbb_fbwall' );
		add_settings_field( 'fbwall_at', 'Access Token', array( &$this, 'settings_fbwall_at_option' ), $this->settings_page, 'flbb_fbwall' );		
		add_settings_field( 'fbwall_item', 'Number of posts to display', array( &$this, 'settings_fbwall_items_option' ), $this->settings_page, 'flbb_fbwall' );		
		add_settings_field( 'fbwall_title', 'Title', array( &$this, 'settings_fbwall_title_option' ), $this->settings_page, 'flbb_fbwall' );			

	} // END register_settings()
	
	/**
	 * settings_fbwall_enabled_option()
	 * Whether or not the Facebook wall functionality appears on the homepage
	 */
	function settings_fbwall_enabled_option() {
		
		$options = $this->options;

		echo '<select id="fbwall_enabled" name="' . $this->options_group_name . '[fbwall_enabled]">';
		echo '<option value="off"';
		if ( isset( $options['fbwall_enabled'] ) && $options['fbwall_enabled'] == 'off' ) {
			echo ' selected="selected"';
		}		
		echo '>Disabled</option>';
		echo '<option value="on"';
		if ( isset( $options['fbwall_enabled'] ) && $options['fbwall_enabled'] == 'on' ) {
			echo ' selected="selected"';
		}		
		echo '>Enabled</option>';
		echo '</select>';
		
	} // END settings_fbwall_enabled_option()
	
	/**
	 * settings_fbwall_title_option()
	 * Title to use with the Facebook Wall
	 */
	function settings_fbwall_title_option() {
		
		$options = $this->options;

		echo '<input id="fbwall_title" name="' . $this->options_group_name . '[fbwall_title]"';
		if ( isset( $options['fbwall_title'] ) ) {
			echo ' value="' . $options['fbwall_title'] . '"';
		}		
		echo ' size="80" />';
		echo '<p class="description">(optional) This appears above the Facebook Wall</p>';
		
	} // END settings_fbwall_title_option()	
	
	/**
	 * settings_fbwall_id_option()
	 * Facebook ID to use with the Facebook wall
	 */
	function settings_fbwall_id_option() {
		
		$options = $this->options;

		echo '<input id="fbwall_id" name="' . $this->options_group_name . '[fbwall_id]"';
		if ( isset( $options['fbwall_id'] ) ) {
			echo ' value="' . $options['fbwall_id'] . '"';
		}		
		echo ' size="80" />';
		echo '<p class="description">Your Facebook ID is what appears in the URL of your Page or Profile'
			. '<br />(e.g. For facebook.com/cunyjschool, "cunyjschool is the ID")</p>';
		
	} // END settings_fbwall_id_option()
	
	/**
	 * settings_fbwall_at_option()
	 * Facebook Access Token to use with the Facebook wall
	 */
	function settings_fbwall_at_option() {
		
		$options = $this->options;

		echo '<input id="fbwall_at" name="' . $this->options_group_name . '[fbwall_at]"';
		if ( isset( $options['fbwall_at'] ) ) {
			echo ' value="' . $options['fbwall_at'] . '"';
		}		
		echo ' size="80" />';
		echo '<p class="description">You need to create an access token in order to display your Facebook Wall.</p>';
		
	} // END settings_fbwall_at_option()	
	
	/**
	 * settings_fbwall_items_option()
	 * Whether or not the Facebook wall functionality appears on the homepage
	 */
	function settings_fbwall_items_option() {
		
		$options = $this->options;

		echo '<select id="fbwall_items" name="' . $this->options_group_name . '[fbwall_items]">';
		for ( $i = 1; $i <= 10; $i++ ) {
			echo '<option value="' . $i . '"';
			if ( isset( $options['fbwall_items'] ) && $options['fbwall_items'] == $i ) {
				echo ' selected="selected"';
			}		
			echo '>' . $i . '</option>';
		}
		echo '</select>';
		
	} // END settings_fbwall_items_option()		
	
	/**
	 * settings_validate()
	 * Validation and sanitization on the settings field
	 */
	function settings_validate( $input ) {
		
		if ( $input['fbwall_enabled'] != 'on' ) {
			$input['fbwall_enabled'] != 'off';
		}
		$input['fbwall_title'] = wp_kses( $input['fbwall_title'] );
		$input['fbwall_id'] = wp_kses( $input['fbwall_id'] );		
		$input['fbwall_items'] = (int)$input['fbwall_items'];
		
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

/**
 * flbb_facebook_wall()
 * Display a Facebook wall based on the FB Wall jQuery plugin
 */
function flbb_facebook_wall() {
	global $flbb;
	
	// If the Facebook Wall is enabled
	if ( isset( $flbb->options['fbwall_enabled'] ) && $flbb->options['fbwall_enabled'] == 'on' ) {
		if ( isset( $flbb->options['fbwall_title'] ) && $flbb->options['fbwall_title'] ) {
			echo '<div class="home-title">' . $flbb->options['fbwall_title'] . '</div>';
		}
		echo '<div id="flbb-facebook-wall"></div>';
		echo "<script type='text/javascript'>jQuery('#flbb-facebook-wall').fbWall({"
			. "id:'" . $flbb->options['fbwall_id'] . "',"
			. "accessToken:'" . $flbb->options['fbwall_at'] . "'," 
			. "showGuestEntries:true,"
			. "showComments:true,"
			. "max:" . $flbb->options['fbwall_items'] . ","
			. "timeConversion:12"
			. "});</script>";
		
	}
	
} // END flbb_facebook_wall()

?>