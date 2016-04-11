<?php
/**
 * Contains main plugin class.
 *
 * @package   TogetherJS
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      http://github.com/barryceelen
 * @copyright 2013 Barry Ceelen
 */

/**
 * Main plugin class.
 *
 * @since 0.0.1
 */
class TogetherJS {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Filterable options.
	 *
	 * @since 0.0.1
	 *
	 * @var array
	 */
	public static $options = array();

	/**
	 * Initialize the plugin.
	 *
	 * @since 0.0.1
	 *
	 * @todo  Implement TogetherJSConfig_cloneClicks for admin elements, eg. to expand meta boxes?
	 *        Set TogetherJSConfig_cloneClicks to true by default?
	 *        See: https://togetherjs.com/docs/#configuring-togetherjs
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load admin JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Load public-facing JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add link to admin bar.
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_link' ), 9999 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		$domain = 'together-js';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register and enqueue JavaScript.
	 *
	 * @return void Currently only usable via the Toolbar, exit if user is not logged in.
	 *
	 * @since 0.0.1
	 */
	public function enqueue_scripts() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		wp_register_script(
			'together-js',
			'https://togetherjs.com/togetherjs-min.js',
			array()
		);

		wp_enqueue_script(
			'together-js-script',
			plugins_url( 'js/main.js', __FILE__ ),
			array( 'jquery', 'together-js' ),
			self::VERSION,
			true
		);

		$current_user = wp_get_current_user();

		$options = array(
			'enableShortcut' => false,
			'siteName'       => get_bloginfo( 'name' ),
			'toolName'       => 'TogetherJS',
			'userAvatar'     => $this->get_avatar( $current_user->user_email ),
			'userName'       => $current_user->user_login,
		);

		wp_localize_script(
			'together-js-script',
			'pluginTogetherJsVars',
			array(
				'buttonEl'   => '#wp-admin-bar-together-js .ab-item',
				'labelStart' => apply_filters( 'plugin-together-js-label-start', __( 'Start TogetherJS', 'together-js' ) ),
				'labelStop'  => apply_filters( 'plugin-together-js-label-stop', __( 'End TogetherJS', 'together-js' ) ),
				'options'    => apply_filters( 'plugin-together-js-options', $options ),
			)
		);
	}

	/**
	 * Renders the admin bar link.
	 *
	 * @since 0.0.1
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
	 */
	public function add_admin_bar_link( $wp_admin_bar ) {

		if ( class_exists( 'Airplane_Mode_Core' ) && Airplane_Mode_Core::enabled() ) {
			return;
		}

		$wp_admin_bar->add_menu(
			array(
				'id'        => 'together-js',
				'title'     => apply_filters( 'plugin-together-js-label-start', __( 'Start TogetherJS', 'together-js' ) ),
				'href'      => '#',
				'meta'      => array(
					'class' => 'hide-if-no-js',
				),
			)
		);
	}

	/**
	 * Get avatar by email address
	 *
	 * @since  0.0.1
	 *
	 * @param string $email Email address.
	 */
	private function get_avatar( $email ) {

		$size = '40';
		$email_hash = md5( strtolower( trim( $email ) ) );
		$default = get_option( 'avatar_default' );

		if ( is_ssl() ) {
			$host = 'https://secure.gravatar.com';
		} else {
			$host = sprintf( 'http://%d.gravatar.com', ( hexdec( $email_hash[0] ) % 2 ) );
		}

		if ( 'mystery' === $default ) {
			// Note: ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com').
			$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}";
		} elseif ( 'blank' === $default ) {
			$default = $email ? 'blank' : includes_url( 'images/blank.gif' );
		} elseif ( 'gravatar_default' === $default ) {
			$default = '';
		} elseif ( strpos( $default, 'http://' ) === 0 ) {
			$default = add_query_arg( 's', $size, $default );
		}

		$out  = "$host/avatar/";
		$out .= $email_hash;
		$out .= '?s='.$size;
		$out .= '&amp;d=' . urlencode( $default );

		$rating = get_option( 'avatar_rating' );

		if ( ! empty( $rating ) ) {
			$out .= "&amp;r={$rating}";
		}

		return $out;
	}
}

global $togetherjs;
$togetherjs = TogetherJS::get_instance();
