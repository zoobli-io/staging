		<div id="vx-alert"></div>
		<script type="text/html" id="vx-alert-tpl">
			<div class="ts-notice ts-notice-{type} flexify">
				<i class="las la-info-circle"></i>
				<p>{message}</p>
				<a href="#">Close</a>
			</div>
		</script>
		<div id="vx-assets-cache" class="hidden"></div>

		<?php wp_footer() ?>

		<?php do_action( 'voxel/body-end' ) ?>
	</body>
</html>
