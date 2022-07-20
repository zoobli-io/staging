<?php
/**
 * Page templates - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-templates-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row">
			<div class="ts-col-1-1">
				<div class="ts-tab-heading">
					<h1>Templates</h1>
					<p>Design the templates for this post type</p>
				</div>
				<div class="inner-tab ts-row wrap-row">
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.single)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/single.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Single page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.card)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Preview card</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.archive)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/archive.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Archive page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.form)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/submit.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Submit page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
	</div>
</script>
