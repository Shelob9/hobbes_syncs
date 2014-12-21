	<button type="button" class="button button-small wp-baldrick" data-request="hsyncc_get_default_setting" data-add-node="remote_sites"><?php _e( 'Add New Site', 'hobbes-syncs' ); ?></button>
	<br>
	<br>
	{{#unless remote_sites}}
		<p class="description"><?php _e( "No Sites", 'hobbes-syncs' ); ?></p>
	{{/unless}}
	{{#each remote_sites}}
	<div class="node-wrapper hobbes-syncs-card-item" style="display:block;">
		<span style="color:#a1a1a1;" class="dashicons dashicons-admin-generic hobbes-syncs-card-icon"></span>
		<div class="hobbes-syncs-card-content">
			<input id="hobbes-syncs-remote_sites-name-{{_id}}" type="hidden" name="remote_sites[{{_id}}][_id]" value="{{_id}}">
			
		<div class="hobbes-syncs-config-group">
			<label for="hobbes-syncs-remote_sites-site_name-{{_id}}"><?php _e( 'Site Name', 'hobbes-syncs' ); ?></label>
			<input id="hobbes-syncs-remote_sites-site_name-{{_id}}" type="text" class="regular-text" name="remote_sites[{{_id}}][site_name]" value="{{site_name}}" >
			
		</div>
		<div class="hobbes-syncs-config-group">
			<label for="hobbes-syncs-remote_sites-json_url-{{_id}}"><?php _e( 'JSON URL', 'hobbes-syncs' ); ?></label>
			<input id="hobbes-syncs-remote_sites-json_url-{{_id}}" type="text" class="regular-text" name="remote_sites[{{_id}}][json_url]" value="{{json_url}}" required="required">
			<p class="description" style="margin-left: 190px;"> The URL for the remote site's json api</p>
		</div>
		<div class="hobbes-syncs-config-group">
			<label for="hobbes-syncs-remote_sites-consumer_key-{{_id}}"><?php _e( 'Consumer Key', 'hobbes-syncs' ); ?></label>
			<input id="hobbes-syncs-remote_sites-consumer_key-{{_id}}" type="text" class="regular-text" name="remote_sites[{{_id}}][consumer_key]" value="{{consumer_key}}" required="required">
			<p class="description" style="margin-left: 190px;"> oAuth consumer key for remote site</p>
		</div>
		<div class="hobbes-syncs-config-group">
			<label for="hobbes-syncs-remote_sites-consumer_secret-{{_id}}"><?php _e( 'Consumer Secret', 'hobbes-syncs' ); ?></label>
			<input id="hobbes-syncs-remote_sites-consumer_secret-{{_id}}" type="text" class="regular-text" name="remote_sites[{{_id}}][consumer_secret]" value="{{consumer_secret}}" required="required">
			<p class="description" style="margin-left: 190px;"> oAuth consumer secret for remote site</p>
		</div>
		</div>
		<button type="button" class="button button-small" style="padding: 0px; margin: 3px 0px; position: absolute; left: 14px; top: 6px;" data-remove-parent=".node-wrapper"><span class="dashicons dashicons-no-alt" style="padding: 0px; margin: 0px; line-height: 23px;"></span></button>
	</div>
	{{/each}}