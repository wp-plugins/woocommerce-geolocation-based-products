<?php
/*
Plugin Name: WooCommerce Geolocation Based Products
Plugin URI: http://splashingpixels.com/
Description: A WooCommerce plugin/extension that adds ability for your store to show/hide products based on visitors geolocation.
Version: 1.1.2
Author: Roy Ho
Author URI: http://royho.me

Copyright: (c) 2014 Roy Ho

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Geolocation_Based_Products' ) ) :

/**
 * main class.
 *
 * @package  WC_Geolocation_Based_Products
 */
class WC_Geolocation_Based_Products {
	private static $_this;

	/**
	 * init
	 *
	 * @access public
	 * @since 1.0.0
	 * @return bool
	 */
	public function __construct() {
		self::$_this = $this;

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Required functions
		require_once( 'woo-includes/woo-functions.php' );

		if ( is_woocommerce_active() ) {
			if ( is_admin() ) {
				include_once( 'includes/class-wc-geolocation-based-products-admin.php' );

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			} else {
				include_once( 'includes/class-wc-geolocation-based-products-frontend.php' );
			}
		} else {
			
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );

		}

		return true;
	}

	/**
	 * public access to instance object
	 *
	 * @since 1.1.1
	 * @return bool
	 */
	public function get_instance() {
		return self::$_this;
	}

	/**
	 * load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'wc_geolocation_based_products_plugin_locale', get_locale(), 'woocommerce-geolocation-based-products' );

		load_textdomain( 'woocommerce-geolocation-based-products', trailingslashit( WP_LANG_DIR ) . 'woocommerce-geolocation-based-products' . '/' . 'woocommerce-geolocation-based-products' . '-' . $locale . '.mo' );

		load_plugin_textdomain( 'woocommerce-geolocation-based-products', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		return true;
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Geolocation Based Products Plugin requires WooCommerce to be installed and active. %s', 'woocommerce-geolocation-based-products' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @param mixed $links
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( $links, array(
			'<a href="' . admin_url( 'edit.php?post_type=product&page=geolocation_products' ) . '">' . __( 'Settings', 'woocommerce-geolocation-based-products' ) . '</a>',
		) );
	}
}

add_action( 'plugins_loaded', 'woocommerce_geolocation_based_products_init', 0 );

/**
 * init function
 *
 * @package  WC_Geolocation_Based_Products
 * @since 1.0.0
 * @return bool
 */
function woocommerce_geolocation_based_products_init() {
	new WC_Geolocation_Based_Products();

	return true;
}

endif;