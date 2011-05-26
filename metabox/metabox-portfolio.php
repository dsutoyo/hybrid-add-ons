<div class="<?php hybrid_get_prefix(); ?>_metabox">
 
	<p>
		<?php $mb->the_field('featured_video'); ?>
		<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
	</p>
	
	<p class="howto">Enter a YouTube or Vimeo video URL. It is recommended that you set a featured image to display on the front page and archive pages.</p>

</div>
