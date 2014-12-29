<div class="hobbes-syncs-main-headerwordpress">
		<h2>
		<?php _e( 'Hobbes Syncs', 'hobbes-syncs' ); ?> <span class="hobbes-syncs-version"><?php echo HSYNCC_VER; ?></span>
		<span style="position: absolute; top: 8px;" id="hobbes-syncs-save-indicator"><span style="float: none; margin: 16px 0px -5px 10px;" class="spinner"></span></span>
	</h2>
			<div class="subsubsub hobbes-syncs-nav-tabs">
					
				<a class="{{#is _current_tab value="#hobbes-syncs-panel-post_types"}}current {{/is}}" href="#hobbes-syncs-panel-post_types"><?php _e('Post Types', 'hobbes-syncs') ; ?></a> <a style="color:#666">|</a>
				<a class="{{#is _current_tab value="#hobbes-syncs-panel-remote_sites"}}current {{/is}}" href="#hobbes-syncs-panel-remote_sites"><?php _e('Remote Sites', 'hobbes-syncs') ; ?></a> <a style="color:#666">|</a>

		
		</div>		
		<div class="clear"></div>

	<span class="wp-baldrick" id="hobbes-syncs-field-sync" data-event="refresh" data-target="#hobbes-syncs-main-canvas" data-callback="hsyncc_canvas_init" data-type="json" data-request="#hobbes-syncs-live-config" data-template="#main-ui-template"></span>
</div>

<form id="hobbes-syncs-main-form" action="?page=hobbes_syncs" method="POST">
	<?php wp_nonce_field( 'hobbes-syncs', 'hobbes-syncs-setup' ); ?>
	<input type="hidden" value="hobbes_syncs" name="id" id="hobbes_syncs-id">
	<input type="hidden" value="{{_current_tab}}" name="_current_tab" id="hobbes-syncs-active-tab">

		<div id="hobbes-syncs-panel-post_types" class="hobbes-syncs-editor-panel" {{#is _current_tab value="#hobbes-syncs-panel-post_types"}}{{else}} style="display:none;" {{/is}}>
		<h4><?php _e('Select the post types to sync', 'hobbes-syncs') ; ?> <small class="description"><?php _e('Post Types', 'hobbes-syncs') ; ?></small></h4>
		<?php
		// pull in the general settings template
		include HSYNCC_PATH . 'includes/templates/post_types-panel.php';
		?>
	</div>
	<div id="hobbes-syncs-panel-remote_sites" class="hobbes-syncs-editor-panel" {{#is _current_tab value="#hobbes-syncs-panel-remote_sites"}}{{else}} style="display:none;" {{/is}}>
		<h4><?php _e('Set Sites To Sync To', 'hobbes-syncs') ; ?> <small class="description"><?php _e('Remote Sites', 'hobbes-syncs') ; ?></small></h4>
		<?php
		// pull in the general settings template
		include HSYNCC_PATH . 'includes/templates/remote_sites-panel.php';
		?>
	</div>


	
	<div class="clear"></div>
	<div class="hobbes-syncs-footer-bar">
		<button type="submit" class="button button-primary wp-baldrick" data-action="hsyncc_save_config" data-active-class="none" data-load-element="#hobbes-syncs-save-indicator" data-before="hsyncc_get_config_object" ><?php _e('Save Changes', 'hobbes-syncs') ; ?></button>
	</div>	

</form>

{{#unless _current_tab}}
	{{#script}}
		jQuery(function($){
			$('.hobbes-syncs-nav-tab').first().find('a').trigger('click');
		});
	{{/script}}
{{/unless}}
<hr>
<div class="clear"></div>
<div id="hobbes-syncs-panel-local_keys" class="hobbes-syncs-editor-panel" >
<h4><?php _e('This Site\s API Keys', 'hobbes-syncs') ; ?> <small class="description"><?php _e('Remote Sites', 'hobbes-syncs') ; ?></small></h4>
<?php
// pull in the general settings template
include HSYNCC_PATH . 'includes/templates/local_keys-panel.php';
?>
</div>

<div class="clear"></div>
