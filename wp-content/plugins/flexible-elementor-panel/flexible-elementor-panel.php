<?php
/**
Plugin Name: Flexible Elementor Panel
Plugin URI: https://wordpress.org/plugins/flexible-elementor-panel
Description: This is an add-on for popular page builder Elementor. Makes Elementor Widgets Panel flexible, draggable and folding that more space and opportunities.
Version: 1.9.2
Author: Flexible-Elementor-Panel.com
Author URI: https://flexible-elementor-panel.com
Text Domain: fep
Domain Path: /languages
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//define var
$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$plugin_version = $plugin_data['Version'];

define( 'FEP_VERSION', $plugin_version );
define( 'FEP_FILE', __FILE__ );
define( 'FEP_URL', plugins_url( '/', __FILE__ ) );
define( 'FEP_PATH', plugin_dir_path( __FILE__ ) );

use Elementor\Core\Settings\Manager as SettingsManager;
use FEP\Core\Settings\General\Manager as FEP_Manager;

/**
 * Main Elementor FEP Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elementor_FEP_Extension {

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.4.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_FEP_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_FEP_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'fep', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		/////////////// continues if all conditionnal be okay

		// Plugin Activation
		add_action( 'admin_notices', [ $this, 'admin_notice_fep_activation' ] );

		// Register Styles
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'fep_styles' ], 8 );

		// Register Scripts
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'fep_scripts' ], 8 );

		// Include plugin files
		$this->includes();

		// Init setting panel FEP
		$this->init_panel();

		$settings = FEP_Manager::get_settings();

		// Add simple script in front end for close the first accodion elementor widget
		if ( $settings['accordion_options'] == 'yes' ) {
			add_action( 'wp_footer', [ $this, 'elementor_accordion_off_script_print' ], 99 );
		}

	}


	/**
	 * Includes Files
	 *
	 * Include Required Module Files from plugin FEP
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function includes() {

		if( is_admin() ) {
			require_once FEP_PATH . 'admin/admin.php';
		}

		require_once FEP_PATH . 'inc/settings/manager.php';
		require_once FEP_PATH . 'inc/settings/model.php';

	}


	/**
	 * Scripts
	 *
	 * Register the scripts of plugin FEP
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	private function init_panel() {

		// add the settings from the file /inc/settings/manager.php
    	SettingsManager::add_settings_manager( new FEP_Manager() );

    }


	/**
	 * Plugin activation
	 *
	 * Run when plugin activating
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fep_activation() {

		set_transient( 'fep-admin-notice-activation', true, 5 );

    }


	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'fep' ),
			'<strong>' . esc_html__( 'Flexible Elementor Panel', 'fep' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'fep' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'fep' ),
			'<strong>' . esc_html__( 'Flexible Elementor Panel', 'fep' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'fep' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'fep' ),
			'<strong>' . esc_html__( 'Flexible Elementor Panel', 'fep' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'fep' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}


	/**
	 * Admin notice
	 *
	 * Notive when plugin actived
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_fep_activation() {

		/* Check transient, if available display notice */
		if( get_transient( 'fep-admin-notice-activation' ) ) {
			$message = sprintf(
				esc_html__( 'Thanks you to use our plugin %1$s, %2$s', 'fep' ),
				'<strong>' . esc_html__( 'Flexible Elementor Panel', 'fep' ) . '</strong>',
				'<a href="/wp-admin/admin.php?page=fep-options">' . esc_html__( 'go to the information page for understand how to configure it', 'fep' ) . '</a>'
			);

			printf( '<div class="notice notice-info is-dismissible"><p>%1$s</p></div>', $message );

			/* Delete transient, only display this notice once. */
			delete_transient( 'fep-admin-notice-activation' );
		}

	}


	/**
	 * Styles
	 *
	 * Register the styles of plugin FEP
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fep_styles() {

		wp_enqueue_style( 'flexible-elementor-panel', plugins_url( '/assets/css/flexible-elementor-panel.css', __FILE__ ), false, constant( 'FEP_VERSION' ), 'all' );
		wp_enqueue_style( 'flexible-elementor-panel-night-skin', plugins_url( '/assets/css/flexible-elementor-panel-night-skin.css', __FILE__ ), false, constant( 'FEP_VERSION' ), 'all' );

	}


	/**
	 * Scripts
	 *
	 * Register the scripts of plugin FEP
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function fep_scripts() {

		$settings = FEP_Manager::get_settings();

		wp_enqueue_script( 'onmutate-js', plugins_url( '/assets/js/libs/jquery.onmutate.min.js', __FILE__ ), false, '1.4.2', true );

		wp_register_script( 'fep-functions-js', plugins_url( '/assets/js/fep-functions.js', __FILE__ ), false, constant( 'FEP_VERSION' ), true );
		wp_localize_script( 'fep-functions-js', 'PageFront', array(
			'Permalink' => get_permalink(),
		));
		wp_localize_script( 'fep-functions-js', 'fepConfig', $settings );
		wp_enqueue_script( 'fep-functions-js' );

		wp_register_script( 'flexible-elementor-panel-js', plugins_url( '/assets/js/flexible-elementor-panel.js', __FILE__ ), false, constant( 'FEP_VERSION' ), true );
		wp_localize_script( 'flexible-elementor-panel-js', 'fepConfig', $settings );
		wp_enqueue_script( 'flexible-elementor-panel-js' );


		// Localize the script with new data
		$translation_array = array(
			'exit_tooltip' => __( 'Exit', 'fep' ),
		);
		wp_localize_script( 'flexible-elementor-panel-js', 'fep', $translation_array );

	}


	/**
	 * Scripts FrontEnd print
	 *
	 * Register the scripts in footer of Wordpress in FrontEnd
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function elementor_accordion_off_script_print() { ?>
		<script>
            window.onload = function () {
                jQuery( '.elementor-accordion .elementor-tab-title' ).first().removeClass( 'elementor-active' );
                jQuery( '.elementor-accordion .elementor-tab-content' ).first().css( 'display', 'none' ).removeClass('elementor-active');
            };
		</script>
		<?php
	}

}

Elementor_FEP_Extension::instance();

// Plugin Activation
register_activation_hook( __FILE__, [ 'Elementor_FEP_Extension', 'fep_activation' ] );


/**
 * Wrappers for default l10n functions to include in your theme or plugin.
 */
 if ( ! function_exists( 'e_l10n__' ) ) {
 	/**
 	 * Wrapper for __() gettext function.
 	 * @param  string $string     Translatable text string
 	 * @param  string $textdomain Text domain, default: woocommerce
 	 * @return void
 	 */
 	function e_l10n__( $string, $textdomain = 'elementor' ) {
 		return __( $string, $textdomain );
 	}
 }
 if ( ! function_exists( 'e_l10n_e' ) ) {
 	/**
 	 * Wrapper for _e() gettext function.
 	 * @param  string $string     Translatable text string
 	 * @param  string $textdomain Text domain, default: woocommerce
 	 * @return void
 	 */
 	function e_l10n_e( $string, $textdomain = 'elementor' ) {
 		return _e( $string, $textdomain );
 	}
 }
if ( ! function_exists( 'epro_l10n__' ) ) {
	/**
	 * Wrapper for __() gettext function.
	 * @param  string $string     Translatable text string
	 * @param  string $textdomain Text domain, default: woocommerce
	 * @return void
	 */
	function epro_l10n__( $string, $textdomain = 'elementor-pro' ) {
		return __( $string, $textdomain );
	}
}
if ( ! function_exists( 'epro_l10n_e' ) ) {
	/**
	 * Wrapper for _e() gettext function.
	 * @param  string $string     Translatable text string
	 * @param  string $textdomain Text domain, default: woocommerce
	 * @return void
	 */
	function epro_l10n_e( $string, $textdomain = 'elementor-pro' ) {
		return _e( $string, $textdomain );
	}
}
/* repeat for other l10n function, see wp-includes/l10n.php */
