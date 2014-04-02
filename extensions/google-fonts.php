<?php
/**
 * Google Fonts - Loading Google Fonts for WordPress.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   Google Fonts
 * @version   0.1.0
 * @author     David Sutoyo <david@smallharbor.com>
 * @copyright  Copyright (c) 2012 - 2014, David Sutoyo
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

add_action( 'wp_enqueue_scripts', 'hybrid_addons_google_fonts' );

/**
 * Enqueues Google Fonts
 *
 * @since 0.3.5
 * @uses wp_enqueue_style Enqueues the stylesheet
 * @return void.
 */
function hybrid_addons_google_fonts() {

	$requested_fonts = get_theme_support( 'google-fonts' );

	$font_link = array();

	foreach( $requested_fonts[0] as $key => $font ) {
		if ( is_array( $font ) ) {
			$str = $key . ':';
			$arr = array();
			foreach ($font as $variant) {
				$arr[] = $variant;
			}
			$str .= implode(',', $arr);
			$font_link[] = $str;
		} else {
			$font_link[] = preg_replace( '/\s+/', '+', $font );
		}
	}

	$google_fonts = implode('|', $font_link);

	wp_enqueue_style( 'hybrid-google-fonts', 'http://fonts.googleapis.com/css?family='. $google_fonts, false, '', 'all' );
}

?>