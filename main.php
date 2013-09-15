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
		
function hybrid_addons_init() {
	/* Sets the path to the addons directory. */
	define( 'HYBRID_ADDONS', get_template_directory() . '/includes' );

	/* Sets the path to the addons directory URI. */
	define( 'HYBRID_ADDONS_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );

	require_once( trailingslashit( HYBRID_ADDONS ) . 'functions/head.php' );
    
	require_if_theme_supports( 'cleaner-code', trailingslashit( HYBRID_ADDONS ) . 'functions/declutter.php' );

    /* Add more shortcodes to Hybrid if supported. */
	require_if_theme_supports( 'hybrid-core-shortcodes', trailingslashit( HYBRID_ADDONS ) . 'functions/shortcodes.php' );
	

	require_if_theme_supports( 'foundation-walker', trailingslashit( HYBRID_ADDONS ) . 'functions/menu.php' );

	require_if_theme_supports( 'cleaner-head', trailingslashit( HYBRID_ADDONS ) . 'functions/clean.php' );

	require_if_theme_supports( 'header-elements', trailingslashit( HYBRID_ADDONS ) . 'admin/admin.php' );

	require_if_theme_supports( 'google-fonts', trailingslashit( HYBRID_ADDONS ) . 'extensions/google-fonts.php' );

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

?>