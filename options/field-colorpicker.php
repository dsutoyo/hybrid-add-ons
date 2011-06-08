<?php

function remix_options_colorpicker($var) {
    $prefix = hybrid_get_prefix();
    $the_theme_options = get_option($prefix.'_theme_options'); ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('#<?php echo $prefix."_theme_options\\\\[".$var."\\\\]" ?>').ColorPicker({
        onSubmit: function(hsb, hex, rgb) {
        	$('#<?php echo $prefix."_theme_options\\\\[".$var."\\\\]" ?>').val('#'+hex);
        },
        onBeforeShow: function () {
        	$(this).ColorPickerSetColor(this.value);
        	return false;
        },
        onChange: function (hsb, hex, rgb) {
        	$('#cp_<?php echo $prefix."_theme_options\\\\[".$var."\\\\]" ?> div').css({'backgroundColor':'#'+hex, 'backgroundImage': 'none', 'borderColor':'#'+hex});
        	$('#cp_<?php echo $prefix."_theme_options\\\\[".$var."\\\\]" ?>').prev('input').attr('value', '#'+hex);
        }
      })	
      .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
      });
    });
    </script>
    <?php remix_options_text_field( $var ); ?>
    <div id="cp_<?php echo $prefix . "_theme_options[" . $var . "]" ?>" class="cp_box">
      <div style="background:<?php echo $the_theme_options[$var] ?>;border-color:<?php echo $the_theme_options[$var] ?>"> 
      </div>
    </div>

    <?php
}

?>