<?php
/**
 * Template for managing site and post type templates in wp-admin.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
}

$edit_link = admin_url( 'post.php?post=%d&action=elementor' );
$all_templates_link = admin_url( 'edit.php?elementor_library_category=voxel-template&post_type=elementor_library' );
?>
<div class="edit-cpt-header">
	<div class="ts-container cpt-header-container">
		<div class="ts-row wrap-row">
			<div class="ts-col-1-2 v-center ">
				<h1>Templates<p>Voxel template directory</p></h1>
			</div>
			<div class="cpt-header-buttons ts-col-1-2 v-center">
				<a href="<?= esc_url( $all_templates_link ) ?>" class="ts-button ts-faded">
					View all Voxel templates
				</a>
			</div>
		</div>
		<span class="ts-separator"></span>
	</div>
</div>

<div class="ts-container ts-theme-options vx-use-vue" data-config="<?= esc_attr( wp_json_encode( [
	'tab' => 'general',
] ) ) ?>">
	<div class="ts-row wrap-row">
		<div class="ts-col-1-1">
			<ul class="inner-tabs inner-tabs template-nav">
				<li :class="{'current-item': config.tab === 'general'}">
					<a href="#" @click.prevent="config.tab = 'general'">General</a>
				</li>
				<li :class="{'current-item': config.tab === 'membership'}">
					<a href="#" @click.prevent="config.tab = 'membership'">Membership</a>
				</li>
				<li :class="{'current-item': config.tab === 'orders'}">
					<a href="#" @click.prevent="config.tab = 'orders'">Orders</a>
				</li>
				<li :class="{'current-item': config.tab === 'style_kits'}">
					<a href="#" @click.prevent="config.tab = 'style_kits'">Style kits</a>
				</li>
				<?php foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ): ?>
					<li :class="{'current-item': config.tab === 'postType:<?= esc_attr( $post_type->get_key() ) ?>'}">
						<a href="#" @click.prevent="config.tab = 'postType:<?= esc_attr( $post_type->get_key() ) ?>'">
							<?= $post_type->get_label() ?>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	<div class="ts-row wrap-row" v-cloak>
		<div class="ts-col-1-1">
			<div v-if="config.tab === 'general'" class="ts-template-section">
				<div class="ts-template-heading">
					<h2>Header and footer</h2>
				</div>
				<div class="ts-container post-types-con ts-template-list">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.header' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/header.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Header</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.header' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.footer' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/footer.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Footer</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.footer' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.timeline' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/timeline.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Timeline</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.timeline' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?>
											
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.privacy_policy' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/prvc.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Privacy Policy</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.privacy_policy' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.terms' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/prvc.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Terms & Conditions</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.terms' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.404' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/404.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">404 Not Found</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.404' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.restricted' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/restricted.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Restricted content</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.restricted' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div v-else-if="config.tab === 'membership'" class="ts-template-section">
				<div class="ts-template-heading">
					<h2>Login & registration</h2>
				</div>
				<div class="ts-container post-types-con ts-template-list">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.auth' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/login.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Login & Registration</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.auth' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.pricing' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/plans.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Pricing plans</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.pricing' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.current_plan' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/plans.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Current plan</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.current_plan' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div v-else-if="config.tab === 'orders'" class="ts-template-section">
				<div class="ts-template-heading">
					<h2>Orders and booking</h2>
				</div>
				<div class="ts-container post-types-con ts-template-list">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.orders' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/orders.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Orders page</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.orders' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?>		
										</a>
									</li>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.stripe_account' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/orders.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Stripe Connect account</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.stripe_account' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div v-else-if="config.tab === 'style_kits'" class="ts-template-section">
				<div class="ts-template-heading">
					<h2>Style kits</h2>
				</div>
				<div class="ts-container post-types-con ts-template-list">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-4">
							<div class="single-field tall">
								<a href="<?= esc_url( sprintf( $edit_link, \Voxel\get( 'templates.kit_popups' ) ) ) ?>" target="_blank" class="field-head">
									<img src="<?php echo esc_url( \Voxel\get_image('post-types/orders.png') ) ?>" alt="" class="icon-sm">
									<p class="field-name">Popup styles</p>
									<span class="field-type">Edit with Elementor</span>
								</a>
								<ul class="basic-ul">
									<li>
										<a href="<?= add_query_arg( 'p', \Voxel\get( 'templates.kit_popups' ), home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?>
										</a>
									</li>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
						<div class="ts-col-1-4">
							<div class="single-field tall empty">
								<a href="#" class="field-head"></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ): ?>
				<?php $templates = $post_type->get_templates() ?>
				<div v-else-if="config.tab === 'postType:<?= esc_attr( $post_type->get_key() ) ?>'" class="ts-template-section">
					<div class="ts-template-heading">
						<h2><?= $post_type->get_label() ?></h2>
					</div>
					<div class="ts-container post-types-con ts-template-list">
						<div class="ts-row wrap-row">
							<div class="ts-col-1-4">
								<div class="single-field tall">
									<a href="<?= esc_url( sprintf( $edit_link, $templates['single'] ) ) ?>" target="_blank" class="field-head">
										<img src="<?php echo esc_url( \Voxel\get_image('post-types/single.png') ) ?>" alt="" class="icon-sm">
										<p class="field-name">Single page</p>
										<span class="field-type">Edit with Elementor</span>
									</a>
									<ul class="basic-ul">
										<li>
											<a href="<?= add_query_arg( 'p', $templates['single'], home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="ts-col-1-4">
								<div class="single-field tall">
									<a href="<?= esc_url( sprintf( $edit_link, $templates['card'] ) ) ?>" target="_blank" class="field-head">
										<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
										<p class="field-name">Preview card</p>
										<span class="field-type">Edit with Elementor</span>
									</a>
									<ul class="basic-ul">
										<li>
											<a href="<?= add_query_arg( 'p', $templates['card'], home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="ts-col-1-4">
								<div class="single-field tall">
									<a href="<?= esc_url( sprintf( $edit_link, $templates['archive'] ) ) ?>" target="_blank" class="field-head">
										<img src="<?php echo esc_url( \Voxel\get_image('post-types/archive.png') ) ?>" alt="" class="icon-sm">
										<p class="field-name">Archive page</p>
										<span class="field-type">Edit with Elementor</span>
									</a>
									<ul class="basic-ul">
										<li>
											<a href="<?= add_query_arg( 'p', $templates['archive'], home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
										</li>
									</ul>
								</div>
							</div>

							<div class="ts-col-1-4">
								<div class="single-field tall">
									<a href="<?= esc_url( sprintf( $edit_link, $templates['form'] ) ) ?>" target="_blank" class="field-head">
										<img src="<?php echo esc_url( \Voxel\get_image('post-types/submit.png') ) ?>" alt="" class="icon-sm">
										<p class="field-name">Submit page</p>
										<span class="field-type">Edit with Elementor</span>
									</a>
									<ul class="basic-ul">
										<li>
											<a href="<?= add_query_arg( 'p', $templates['form'], home_url('/') ) ?>" target="_blank" class="ts-button ts-faded icon-only"><?php \Voxel\render_icon( 'line-awesome:las la-eye' ) ?></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
