<?php
function remix_favicon() {
	echo remix_get_favicon();
}

function remix_get_favicon() {
	$favicon = home_url() . '/favicon.png';

	$output = '<link rel="shortcut icon" type="image/png" href="' . $favicon . '">' . "\n";
	echo apply_filters( 'remix_favicon', $output );
}


?>