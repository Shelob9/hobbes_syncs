<?php

$hobbes_syncs = get_option( '_hobbes_syncs' );

?>
<div class="wrap" id="hobbes-syncs-main-canvas">
	<span class="wp-baldrick spinner" style="float: none; display: block;" data-target="#hobbes-syncs-main-canvas" data-callback="hsyncc_canvas_init" data-type="json" data-request="#hobbes-syncs-live-config" data-event="click" data-template="#main-ui-template" data-autoload="true"></span>
</div>

<div class="clear"></div>

<input type="hidden" class="clear" autocomplete="off" id="hobbes-syncs-live-config" style="width:100%;" value="<?php echo esc_attr( json_encode($hobbes_syncs) ); ?>">

<script type="text/html" id="main-ui-template">
	<?php
	// pull in the join table card template
	include HSYNCC_PATH . 'includes/templates/main-ui.php';
	?>	
</script>





