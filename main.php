<?php
/**
 * Hybrid Addons - An add-on library for the Hybrid WordPress theme development framework.
 *
 * Hybrid Addons is a collection of scripts intended for use with the Hybrid framework. Although it is
 * used with Hybrid, it is not affiliated in any way with Hybrid or Justin Tadlock. Hybrid Addons provides some
 * slideshow scripts, extra widgets, as well as an alternative interface/back-end for theme settings.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Hybrid Addons
 * @version 0.3.5
 * @author David Sutoyo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// run actions to set up hybrid-addon options and widgets
add_action( 'after_setup_theme', 'hybrid_addons_init', 14 );
add_action( 'widgets_init', 'hybrid_addons_register_widgets' );
add_action( 'wp_enqueue_script', 'hybrid_addons_enqueue_scripts' );
		
function hybrid_addons_init() {
	//echo trailingslashit( HYBRID_ADDONS ) . 'functions/walker.php';

	/* Sets the path to the addons directory. */
	define( 'HYBRID_ADDONS', get_template_directory() . '/includes' );

	/* Sets the path to the addons directory URI. */
	define( 'HYBRID_ADDONS_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );

	/* Specify our Foundation version */
	define( 'FOUNDATION_VERSION', '4.2.2');

	require_once( trailingslashit( HYBRID_ADDONS ) . 'functions/numbers.php' );

	require_once( trailingslashit( HYBRID_ADDONS ) . 'functions/head.php' );

	require_if_theme_supports( 'more-theme-options', trailingslashit( HYBRID_ADDONS ) . 'options/fields.php' );
    
	require_if_theme_supports( 'cleaner-code', trailingslashit( HYBRID_ADDONS ) . 'functions/declutter.php' );

    /* Load the shortcodes if supported. */
	require_if_theme_supports( 'hybrid-core-shortcodes', trailingslashit( HYBRID_ADDONS ) . 'functions/shortcodes.php' );
	
	/* Load the post meta box if supported. */
	require_if_theme_supports( 'hybrid-core-post-meta-box', trailingslashit( HYBRID_ADDONS ) . 'admin/post-meta-box.php' );

	require_if_theme_supports( 'foundation-walker', trailingslashit( HYBRID_ADDONS ) . 'functions/menu.php' );

	require_if_theme_supports( 'cleaner-head', trailingslashit( HYBRID_ADDONS ) . 'functions/clean.php' );
    
}

/**
 * Registers the Hybrid Addons Widgets.
 *
 * @since 0.1.0
 * @uses register_widget Registers the widgets
 * @return void.
 */
function hybrid_addons_register_widgets() {
	if ( current_theme_supports( 'hybrid-core-widgets' ) ) :
		/* Load the core framework widget files. */
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-blog.php' );
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-flickr.php' );

		/* Register each of the core framework widgets. */
		register_widget( 'Hybrid_Addons_Widget_Blog' );
		register_widget( 'Hybrid_Addons_Widget_Flickr' );
	endif;
}

/**
 * Enqueues Foundation scripts.
 *
 * @since 0.3.5
 * @uses enqueue_scripts Enqueues scripts and styles
 * @return void.
 */
function hybrid_addons_enqueue_scripts() {
	// ===== Modernizr
	/*wp_enqueue_script( 'modernizr', HYBRID_ADDONS_URI . 'foundation/js/vendor/custom.modernizr.js', '', FOUNDATION_VERSION );*/

	// ===== Foundation
	if ( current_theme_supports( 'foundation-base') ) {
		wp_enqueue_script( 'foundation', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Alerts
	if ( current_theme_supports( 'foundation-alerts') ) {
		wp_enqueue_script( 'foundation-alerts', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.alerts.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Clearing
	if ( current_theme_supports( 'foundation-clearing') ) {
		wp_enqueue_script( 'foundation-clearing', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.clearing.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Cookies
	if ( current_theme_supports( 'foundation-cookie') ) {
		wp_enqueue_script( 'foundation-cookie', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.cookie.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Dropdown
	if ( current_theme_supports( 'foundation-dropdown') ) {
		wp_enqueue_script( 'foundation-dropdown', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.dropdown.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Forms
	if ( current_theme_supports( 'foundation-forms') ) {
		wp_enqueue_script( 'foundation-forms', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.forms.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Joyride
	if ( current_theme_supports( 'foundation-joyride') ) {
		wp_enqueue_script( 'foundation-joyride', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.joyride.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Magellan
	if ( current_theme_supports( 'foundation-magellan') ) {
		wp_enqueue_script( 'foundation-magellan', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.magellan.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Orbit
	if ( current_theme_supports( 'foundation-orbit') ) {
		wp_enqueue_script( 'foundation-orbit', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.orbit.js', 'jquery', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Reveal
	if ( current_theme_supports( 'foundation-reveal') ) {
		wp_enqueue_script( 'foundation-reveal', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.reveal.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Section
	if ( current_theme_supports( 'foundation-section') ) {
		wp_enqueue_script( 'foundation-section', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.section.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Tooltips
	if ( current_theme_supports( 'foundation-tooltips') ) {
		wp_enqueue_script( 'foundation-tooltips', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.tooltips.js', '', FOUNDATION_VERSION, true);
	}

	// ===== Foundation Topbar
	if ( current_theme_supports( 'foundation-topbar') ) {
		wp_enqueue_script( 'foundation-topbar', HYBRID_ADDONS_URI . 'foundation/js/foundation/foundation.topbar.js', '', FOUNDATION_VERSION, true);
	}
}

/**
 * Loads the theme options once and allows the input of the specific field the user would 
 * like to show. Theme settings are added with 'autoload' set to 'yes', so the settings are 
 * only loaded once on each page load. USE ONLY WITH THE HYBRID ADDON THEME OPTIONS.
 *
 * @since 0.1.0
 * @uses get_option() Gets an option from the database.
 * @uses hybrid_get_prefix() Gets the prefix of the theme.
 * @global object $hybrid The global Hybrid object.
 * @global array $hybrid_settings Deprecated. Developers should use hybrid_get_setting().
 * @param string $option The specific theme setting the user wants.
 * @return string|int|array $settings[$option] Specific setting asked for.
 */
function hybrid_get_option( $option = '' ) {
	global $hybrid, $hybrid_settings;

	if ( !$option )
		return false;

	if ( !isset( $hybrid->settings ) )
		$hybrid->settings = $hybrid_settings = get_option( hybrid_get_prefix() . '_theme_options' );

	if ( !is_array( $hybrid->settings ) || empty( $hybrid->settings[$option] ) )
		return false;

	if ( is_array( $hybrid->settings[$option] ) )
		return $hybrid->settings[$option];
	else
		return wp_kses_stripslashes( $hybrid->settings[$option] );
}

?>