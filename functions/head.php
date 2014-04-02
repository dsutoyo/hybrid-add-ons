<?php
/**
 * Functions for handling favicons
 *
 * @package    Hybrid Addons
 * @subpackage Functions
 * @author     David Sutoyo <david@smallharbor.com>
 * @copyright  Copyright (c) 2012 - 2014, David Sutoyo
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Output the favicon
 *
 * @since 0.1.1
 */
function hybrid_addons_favicon() {
	echo hybrid_addons_get_favicon();
}

/**
 * Get our favicon
 *
 * @since 0.1.1
 */
function hybrid_addons_get_favicon() {
	$favicon = home_url() . '/favicon.png';

	$output = '<link rel="shortcut icon" type="image/png" href="' . $favicon . '">' . "\n";
	echo apply_filters( 'hybrid_addons_favicon', $output );
}


?>