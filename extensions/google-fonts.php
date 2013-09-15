<?php

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