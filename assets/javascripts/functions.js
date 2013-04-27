jQuery(document).ready(function () {
  
  jQuery("#options_tabs").tabs();
  
  jQuery('.hybrid-addon-media-upload').click(function() {
      thefield = jQuery(this).attr('id');
      thefield = thefield.replace('upload_', '');
      formfield = jQuery('#evoke_theme_options\\[' + thefield + '\\]').attr('name');
      tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
      return false;
  });

  window.send_to_editor = function(html) {
      imgurl = jQuery('img',html).attr('src');
      jQuery('#evoke_theme_options\\[' + thefield + '\\]').val(imgurl);
      tb_remove();
  }
  
});