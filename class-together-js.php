<?php
/**
 * TogetherJS
 *
 * @package   TogetherJS
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      http://github.com/barryceelen
 * @copyright 2013 Barry Ceelen
 */

/**
 * Plugin class.
 *
 * @package TogetherJS
 * @author  Barry Ceelen <b@rryceelen.com>
 */
class TogetherJS {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.0.1
	 *
	 * @var     string
	 */
	const VERSION = '0.0.1';

	/**
	 * Unique identifier.
	 *
	 * @since    0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'together-js';

	/**
	 * Instance of this class.
	 *
	 * @since    0.0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Filterable options.
	 *
	 * @since    0.0.1
	 *
	 * @var      array
	 */
	public static $options = array();

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     0.0.1
	 */
	private function __construct() {

		$default_options = array(
			'label_start' => __( 'Start TogetherJS', 'bla' ),
			'label_stop'  => __( 'End TogetherJS', 'bla' ),
		);

		self::$options = apply_filters( $this->plugin_slug . '_options', $default_options );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load admin JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Load public-facing JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add link to admin bar
		add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar_link' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.1
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register and enqueue JavaScript.
	 *
	 * @return void Exits if user is not logged in
	 *
	 * @since     0.0.1
	 */
	public function enqueue_scripts() {

		if ( ! is_user_logged_in() )
			return;

		wp_register_script(
			'together-js',
			'https://togetherjs.com/togetherjs-min.js',
			array()
		);

		wp_enqueue_script(
			$this->plugin_slug . '-script',
			plugins_url( 'js/main.js', __FILE__ ),
			array( 'jquery', 'together-js' ),
			self::VERSION,
			true
		);

		wp_localize_script(
			$this->plugin_slug . '-script',
			'pluginTogetherJsVars',
			array(
				'labelStart' => self::$options['label_start'],
				'labelStop' => self::$options['label_stop']
			)
		);
	}

	/**
	 * Renders the admin bar link.
	 *
	 * @todo     Get label from settings.
	 * @since    0.0.1
	 */
	public function admin_bar_link() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'id'        => $this->plugin_slug,
			'title'     => self::$options['label_start'],
			'href'      => '#',
			'meta'      => array(
				'class' => 'hide-if-no-js'
				// Handled by main.js
				// 'onclick' => 'TogetherJS(this); return false'
			),
		) );
	}
}
