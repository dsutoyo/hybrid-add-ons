<?php
/**
 * Functions for removing WordPress version numbers from wp_head
 *
 * @package    Hybrid Addons
 * @subpackage Functions
 * @author     David Sutoyo <david@smallharbor.com>
 * @copyright  Copyright (c) 2012 - 2014, David Sutoyo
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Launch operation cleanup */
add_action( 'init', 'hybrid_addons_cleaner_wp_head' );

/* Remove WP version from RSS */
add_filter( 'the_generator', 'hybrid_addons_remove_wp_version_from_rss' );

/**
 * Removes WordPress generated links in wp_head
 *
 * @since 0.1.0
 * @access public
 */
function hybrid_addons_cleaner_wp_head() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );

	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// index link
	remove_action( 'wp_head', 'index_rel_link' );

	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );

	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

	// WP version
	remove_action( 'wp_head', 'wp_generator' );

	// remove WP version from css
	//add_filter( 'style_loader_src', 'hybrid_addons_remove_wp_version_from_css_js', 9999 );
	
	// remove WP version from scripts
	//add_filter( 'script_loader_src', 'remove_wp_version_from_css_js', 9999 );
}

/**
 * Removes WordPress version from RSS feed
 *
 * @since 0.1.0
 * @access public
 */
function hybrid_addons_remove_wp_version_from_rss() {
	return '';
}

/**
 * Removes WordPress version from scripts
 *
 * @since 0.1.0
 * @access public
 */
function hybrid_addons_remove_wp_version_from_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}