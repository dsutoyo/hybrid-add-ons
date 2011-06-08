<?php

function remix_options_media($var) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');
    if ( $the_theme_options[$var] ) {
      echo '<img src="' . $the_theme_options[$var] . '" />';
      echo '<p><small><em>Preview may be smaller than actual image size</em></small></p>';
      echo '<div class="remove_image">';
      remix_options_checkbox( 'delete_' . $var );
      remix_options_label_for( 'delete_' . $var, 'Remove image', 'option_checkbox_label' );
      echo '</div>';
    }
    echo '<div class="element">';
    echo '<input id="' . $prefix . '_theme_options[' . $var . ']" class="option_field" type="text" name="' . $prefix . '_theme_options[' . $var . ']" value="' . $the_theme_options[$var] . '" />';
    echo '<a id="upload_' . $var . '" class="remix-media-upload" href="#">Upload</a>';
    echo '</div>';
}

?>