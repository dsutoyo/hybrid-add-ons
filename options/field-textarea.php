<?php

function remix_options_textarea($var) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');
    echo '<textarea class="option_textarea" id="'.$prefix.'_theme_options['.$var.']" name="'.$prefix.'_theme_options['.$var.']">'.$the_theme_options[$var].'</textarea>';
}

?>