<script type="text/javascript">

jQuery(document).ready(function () {
    <?php
    global $media_upload_ids;
    $prefix = hybrid_get_prefix();
    foreach ( $media_upload_ids as $var ) { ?>
  
        jQuery('#upload_<?php echo $var; ?>').click(function() {
            formfield = jQuery('#<?php echo $prefix; ?>_theme_options\\[<?php echo $var; ?>\\]').attr('name');
            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });

        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#<?php echo $prefix; ?>_theme_options\\[<?php echo $var; ?>\\]').val(imgurl);
            tb_remove();
        }
    
    <?php } ?>
});

</script>