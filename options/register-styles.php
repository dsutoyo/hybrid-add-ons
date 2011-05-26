<?php

$section_args = array(
    'section_id' => 'options_styles',
    'section_title' => 'Colors and Styles'
);

$field_args = array(
    'font' => array(
        'field_title' => 'Font Style',
        'field_type' => 'select',
        'field_array' => array(
      	    "serif" => "Serif",
      	    "sans-serif" => "Sans Serif",
      	),
        'field_description' => ''
    ),
    'link_color' => array(
        'field_title' => 'Link Color',
        'field_type' => 'colorpicker',
        'field_description' => ''
    ),
    'hover_color' => array(
        'field_title' => 'Hover Color',
        'field_type' => 'colorpicker',
        'field_description' => ''
    ),
    'nav_link_color' => array(
        'field_title' => 'Nav Link Color',
        'field_type' => 'colorpicker',
        'field_description' => ''
    )
);

remix_build_options_section($section_args, $field_args);

function evoke_options_styles_section_text() { ?>
    <p><?php _e('Edit colors and styles', hybrid_get_textdomain()) ?></p>
<?php }

?>