<?php

function hybrid_addons_options_checkbox($var, $label) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');

    echo '<input class="option_checkbox" id="' . $prefix . '_theme_options[' . $var . ']" type="checkbox" name="' . $prefix . '_theme_options[' . $var . ']" value="true"';
    checked(TRUE, (bool) hybrid_get_option($var));
    echo ' />';

    echo '<label for="' . $prefix . '_theme_options[' . $var . ']">' . $label . '</label>';
}

?>