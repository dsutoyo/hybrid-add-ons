jQuery(document).ready(function () {
  jQuery("#options_tabs").tabs();
  
  jQuery('#upload_custom_favicon').click(function() {
     formfield = jQuery('#remix_theme_options\\[custom_favicon\\]').attr('name');
     tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
     return false;
  });

  window.send_to_editor = function(html) {
     imgurl = jQuery('img',html).attr('src');
     jQuery('#evoke_theme_options\\[custom_favicon\\]').val(imgurl);
     tb_remove();
  }
});