<?php
$keys = \jp_keyed_request\auth\get::get_keys( false );
$not_generated = __( 'Key has not been generated', 'hobbes-syncs' );
?>
<div class="hsyncs-config-group">
	<label style="float:left;"><?php _e( 'Local Keys', 'syncs' ); ?></label>
	<div style="margin-left: 180px; padding-top: 6px;" id="hsync-local-keys">

		<div><span class="label"><?php _e( 'Public Key', 'syncs' ); ?> - </span>
			<?php echo pods_v( 'public', $keys, $not_generated ); ?>
		</div>
		<div><span class="label"><?php _e( 'Private Key', 'syncs' ); ?> - </span>
			<?php echo pods_v( 'private', $keys, $not_generated ); ?>
		</div>
	</div>
	<div id="hsyncs-generate-keys">
		<a href="#" class="button-primary" id="hsync-generate-keys"><?php _e( 'Generate New Keys', 'hsync' ); ?></a>
	</div>
</div>
<?php
	$nonce = wp_create_nonce( 'hobbes-syncs' );
?>
<script type="text/javascript">


	jQuery(function($) {
		$( "#hsync-generate-keys" ).click( function ( event ) {

			event.preventDefault();

			var data = {
				action : "hsync_generate_keys",
				nonce: "<?php echo $nonce; ?>"
			};

			$.post(ajaxurl, data, function(response) {
				location.reload();
			});

		} );
	});

</script>

