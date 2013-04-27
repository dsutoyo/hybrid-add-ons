<?php
function hybrid_addons_favicon() {
	echo hybrid_addons_get_favicon();
}

function hybrid_addons_get_favicon() {
	$favicon = home_url() . '/favicon.png';

	$output = '<link rel="shortcut icon" type="image/png" href="' . $favicon . '">' . "\n";
	echo apply_filters( 'hybrid_addons_favicon', $output );
}


?>