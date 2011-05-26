<div class="<?php hybrid_get_prefix(); ?>_metabox">
 
	<p>
		<?php $mb->the_field('hide_sidebar'); ?>
		<input type="checkbox" name="<?php $mb->the_name(); ?>" value="1"<?php if ($mb->get_the_value()) echo ' checked="checked"'; ?>/>
		<label for="<?php $mb->the_name(); ?>">Hide Sidebar</label><br />
	</p>

</div>
