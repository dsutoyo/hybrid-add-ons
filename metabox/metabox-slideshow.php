<div class="evoke_metabox">
 
 	<p class="howto">To deactivate a slide, set its status to "Pending Review" or "Draft"</p>
 
	<p>
		<?php $mb->the_field('slide_caption'); ?>
	  <label for="<?php $mb->the_name(); ?>">Slide Caption:</label><br />
		<textarea name="<?php $mb->the_name(); ?>" style="width:99%;"><?php $mb->the_value(); ?></textarea>
	</p>

</div>
