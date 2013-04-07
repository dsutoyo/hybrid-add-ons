<?php
function remix_favicon() {
	echo remix_get_favicon();
}

function remix_get_favicon() {

	$output = '<link rel="shortcut icon" type="image/png" href="http://wordpress.dev/wp-content/themes/schema/asset/images/favicon.png">' . "\n";
	echo apply_filters( 'remix_favicon', $output );
}


?>