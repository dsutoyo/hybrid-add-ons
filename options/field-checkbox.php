<?php

function remix_options_checkbox($var, $check = '') {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options');
    if ($check == '') {
      echo '<input class="option_checkbox" id="'.$prefix.'_theme_options['.$var.']" type="checkbox" name="'.$prefix.'_theme_options['.$var.']" value="true" />';
    } else {
      echo '<input class="option_checkbox" id="'.$prefix.'_theme_options['.$var.']" type="checkbox" name="'.$prefix.'_theme_options['.$var.']" value="true"';
      checked(TRUE, (bool) get_option($var));
      echo ' />';
    }
}

?>