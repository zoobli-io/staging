<!DOCTYPE html>
<html class="no-js" <?php language_attributes() ?>>
	<head>
		<meta charset="<?php bloginfo('charset') ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head() ?>
	</head>
	<body <?php body_class() ?>><?php wp_body_open() ?>
		<script type="text/html" id="voxel-popup-template">
			<div class="ts-popup-root elementor" :class="'elementor-'+$root.post_id" v-cloak>
				<div class="ts-form ts-search-widget elementor-element" :class="'elementor-element-'+$root.widget_id" :style="styles" ref="popup">
					<div class="ts-field-popup-container">
						<div class="ts-field-popup triggers-blur" ref="popup-box">
							<div class="ts-popup-content-wrapper">
								<slot></slot>
							</div>
							<div class="ts-popup-controller" v-if="showSave || showClear">
								<ul class="flexify simplify-ul">
									<li class="flexify" @click.prevent="$emit('clear')">
										<a v-if="showClear" href="#" class="ts-btn ts-btn-1">
											{{ clearLabel || 'Clear' }}
										</a>
									</li>
									<li class="flexify">
										<a v-if="showSave" href="#" class="ts-btn ts-btn-2" @click.prevent="$emit('save')">
											{{ saveLabel || 'Save' }}
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>
		<script type="text/html" id="voxel-form-group-template">
			<div :class="{'ts-form-group': defaultClass}">
				<slot name="trigger"></slot>
				<teleport to="body">
					<transition name="form-popup">
						<form-popup
							ref="popup"
							v-if="$root.activePopup === popupKey"
							:class="wrapperClass"
							:target="popupTarget"
							:show-save="showSave"
							:show-clear="showClear"
							:save-label="saveLabel"
							:clear-label="clearLabel"
							:prevent-blur="preventBlur"
							@blur="onPopupBlur"
							@save="$emit('save', this);"
							@clear="$emit('clear', this);"
						>
							<slot name="popup"></slot>
						</form-popup>
					</transition>
				</teleport>
			</div>
		</script>
