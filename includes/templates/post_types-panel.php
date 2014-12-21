<div class="hsyncs-config-group">
	<label style="float:left;"><?php _e( 'Post Types', 'hsyncs' ); ?></label>
	<div style="margin-left: 180px; padding-top: 6px;">
		<?php
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach($post_types as $type){?>
			<label style="display:block; margin-bottom:4px;"><input type="checkbox" data-live-sync="true" name="post_type[<?php echo $type->name; ?>]" value="1" {{#if post_type/<?php echo $type->name; ?>}}checked="checked"{{/if}}> <?php echo $type->label; ?></label>
		<?php } ?>
	</div>
</div>


