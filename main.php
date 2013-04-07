<?php
/**
 * Remix - An add-on library for the Hybrid WordPress theme development framework.
 *
 * Remix is a collection of scripts intended for use with the Hybrid framework. Although it is
 * used with Hybrid, it is not affiliated in any way with Hybrid or Justin Tadlock. Remix provides some
 * slideshow scripts, extra widgets, as well as an alternative interface/back-end for theme settings.
 * It also makes use of WPAlchemy to generate extra meta boxes.
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
 * @package Remix
 * @version 0.2.0
 * @author David Sutoyo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// run actions to set up remix options and widgets
add_action( 'after_setup_theme', 'remix_init', 14 );
add_action( 'widgets_init', 'remix_register_widgets' );
		
function remix_init() {
	//echo trailingslashit( HYBRID_ADDONS ) . 'functions/walker.php';
	/* Sets the path to the core framework extensions directory. */
	define( 'HYBRID_ADDONS', get_template_directory() . '/includes' );

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

function remix_register_widgets() {
	if ( current_theme_supports( 'hybrid-core-widgets' ) ) :
		/* Load the core framework widget files. */
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-blog.php' );
		require_once( trailingslashit( HYBRID_ADDONS ) . 'classes/widget-flickr.php' );

		/* Register each of the core framework widgets. */
		register_widget( 'Remix_Widget_Blog' );
		register_widget( 'Remix_Widget_Flickr' );
	endif;
}


/**
 * Loads the theme options once and allows the input of the specific field the user would 
 * like to show. Theme settings are added with 'autoload' set to 'yes', so the settings are 
 * only loaded once on each page load. USE ONLY WITH THE REMIX THEME OPTIONS ADD-ON.
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