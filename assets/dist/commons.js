!function(e){"function"==typeof define&&define.amd?define("commons",e):e()}(function(){"use strict";window.Voxel={mixins:{},components:{},Maps:{await(e){Voxel.Maps.Loaded?e():document.addEventListener("maps:loaded",()=>e())}},helpers:{getParent(e,t){for(var o=null,r=e.$parent;r&&!o;)r.$options.name===t&&(o=r),r=r.$parent;return o},dateFormatYmd(e){return[e.getFullYear(),("0"+(e.getMonth()+1)).slice(-2),("0"+e.getDate()).slice(-2)].join("-")},dateTimeFormat(e){return e.toLocaleString(void 0,{dateStyle:"medium",timeStyle:"short"})},dateFormat(e){return e.toLocaleString(void 0,{dateStyle:"medium"})},timeFormat(e){return e.toLocaleString(void 0,{timeStyle:"short"})},debounce(t,o=200){let r;return(...e)=>{clearTimeout(r),r=setTimeout(()=>{t.apply(this,e)},o)}},viewportPercentage(e){var e=e.getBoundingClientRect(),t=window.innerHeight;if(e.top>=t||e.bottom<=0)return 0;if(e.top<0&&e.bottom>t)return 1;var o=e.top<0?Math.abs(e.top):0,r=e.bottom>t?e.bottom-t:0;return(e.height-o-r)/t},sequentialId(){return this.count||(this.count=1),this.count++}},alert(e,t="info",o=7500){var t=document.getElementById("vx-alert-tpl").textContent.replace("{type}",t).replace("{message}",e),r=jQuery(t).hide();r.find("> a").on("click",e=>{e.preventDefault(),r.fadeOut(100,()=>r.remove())}),"number"==typeof o&&0<o&&setTimeout(()=>r.fadeOut(100,()=>r.remove()),o),r.appendTo(jQuery("#vx-alert")),r.fadeIn(100)},authRequired(e="{login} or {register} to proceed."){var t=`<a href="${Voxel_Config.login_url}">Log-in</a>`,o=`<a href="${Voxel_Config.register_url}">Register</a>`;Voxel.alert(e.replace("{login}",t).replace("{register}",o),"info",7500)},getSearchParam(e){return new URL(window.location).searchParams.get(e)},setSearchParam(e,t){var o=new URL(window.location);o.searchParams.set(e,t),window.history.replaceState(null,null,o)},deleteSearchParam(e){var t=new URL(window.location);t.searchParams.delete(e),window.history.replaceState(null,null,t)},async share(e){try{await navigator.share(e)}catch(e){}},copy(e){navigator.clipboard.writeText(e).then(()=>Voxel.alert("Copied to clipboard.","info",1500))}},window.Voxel.mixins.base={data(){return{widget_id:null,post_id:null}},mounted(){this.widget_id=this.$root.$options.el.closest(".elementor-element").dataset.id,this.post_id=this.$root.$options.el.closest(".elementor").dataset.elementorId}},window.Voxel.mixins.blurable={props:{preventBlur:{type:String,default:""}},mounted(){requestAnimationFrame(()=>{document.addEventListener("mousedown",this._click_outside_handler)})},unmounted(){document.removeEventListener("mousedown",this._click_outside_handler)},methods:{_click_outside_handler(e){var t=".triggers-blur";this.preventBlur.length&&(t+=","+this.preventBlur),e.target.closest(t)||this.$emit("blur")}}},window.Voxel.components.popup={template:"#voxel-popup-template",mixins:[Voxel.mixins.blurable],props:{onBlur:Function,target:Object,saveLabel:String,clearLabel:String,showSave:{type:Boolean,default:!0},showClear:{type:Boolean,default:!0}},data(){return{styles:""}},mounted(){var e,t,o,r;this.target&&(e=this.target.getBoundingClientRect(),o=this.$refs["popup-box"],t=jQuery(this.target).offset(),o=Math.max(e.width,parseFloat(window.getComputedStyle(o).minWidth)),r=jQuery("body").innerWidth(),r=t.left+o>r?r-o:t.left,this.styles=`
				top: ${t.top+e.height}px;
				left: ${r}px;
				width: ${o}px;
				position: absolute;
			`),requestAnimationFrame(()=>{var e=this.$el.querySelector(".autofocus");e&&e.focus()})}},window.Voxel.components.formGroup={template:"#voxel-form-group-template",props:{popupKey:String,saveLabel:String,clearLabel:String,wrapperClass:String,showSave:{type:Boolean,default:!0},showClear:{type:Boolean,default:!0},defaultClass:{type:Boolean,default:!0},preventBlur:{type:String,default:""}},components:{"form-popup":window.Voxel.components.popup},data(){return{popupTarget:null}},mounted(){this.popupTarget=this.$el.querySelector(".ts-popup-target")||this.$el},methods:{blur(){this.$refs.popup.$emit("blur")},onPopupBlur(){this.$root.activePopup=null,this.$emit("blur",this)}}},window.render_static_popups=()=>{Array.from(document.querySelectorAll(".ts-popup-component")).forEach(e=>{var t;e.__vue_app__||((t=Vue.createApp({template:e.innerHTML,data(){return{active:!1,screen:"main",widget_id:null,post_id:null,slide_from:"left",window:window,navigator:window.navigator,Voxel:window.Voxel}},mounted(){this.widget_id=this.$el.parentElement.closest(".elementor-element").dataset.id,this.post_id=this.$el.parentElement.closest(".elementor").dataset.elementorId,this.$refs.target.onclick=e=>{e.preventDefault(),this.active=!0}}})).component("form-popup",{template:`
				<div class="ts-popup-root elementor" :class="'elementor-'+$root.post_id" v-cloak>
					<div class="ts-form ts-search-widget elementor-element" :style="styles" ref="popup" :class="'elementor-element-'+$root.widget_id">
						<div class="ts-field-popup-container">
							<div class="ts-field-popup triggers-blur" ref="popup-box">
								<div class="ts-popup-content-wrapper">
									<slot></slot>
								</div>
								<slot name="footer"></slot>
							</div>
						</div>
					</div>
				</div>
			`,mixins:[Voxel.mixins.blurable],props:["onBlur","target"],data(){return{styles:""}},mounted(){var e,t,o,r;this.target&&(e=this.target.getBoundingClientRect(),o=this.$refs["popup-box"],t=jQuery(this.target).offset(),o=Math.max(e.width,parseFloat(window.getComputedStyle(o).minWidth)),r=jQuery("body").innerWidth(),r=t.left+o>r?r-o-20:t.left,this.styles=`
						top: ${t.top+e.height}px;
						left: ${r}px;
						width: ${o}px;
						position: absolute;
					`),requestAnimationFrame(()=>{var e=this.$el.querySelector(".autofocus");e&&e.focus()})}}),t.component("popup",{props:["wrapper"],template:`
				<teleport to="body">
				 	<transition name="form-popup">
				 		<form-popup v-if="$root.active" ref="popup" @blur="$root.active = false" :target="$root.$refs.target" :class="wrapper">
				 			<template #default>
								<slot></slot>
				 			</template>
				 			<template #footer>
								<slot name="footer"></slot>
				 			</template>
				 		</form-popup>
				 	</transition>
				</teleport>
			`,methods:{blur(){this.$refs.popup.$emit("blur")}}}),t.mount(e))})},window.render_static_popups(),jQuery(document).on("voxel:markup-update",window.render_static_popups),jQuery(o=>{o(".ts-expand-hours").on("click",e=>{e.preventDefault(),o(e.currentTarget).parents(".ts-work-hours").toggleClass("active")}),o(".ts-plan-tabs a").on("click",e=>{e.preventDefault();var t=e.target.dataset.id,e=o(e.target.parentElement),e=(e.addClass("ts-tab-active").siblings().removeClass("ts-tab-active"),e.parent().next());e.find(`.ts-plan-container:not([data-group="${t}"])`).addClass("hidden"),e.find(`.ts-plan-container[data-group="${t}"]`).removeClass("hidden")}),o("a[vx-action]").on("click",o=>{o.preventDefault(),o.target.classList.add("vx-pending");var e=o.target.href;jQuery.get(e,e=>{if(e.success){if(e.message&&Voxel.alert(e.message,e.message_type||"success"),e.redirect_to)return void window.location.replace(e.redirect_to)}else{var t=Voxel_Config.l10n.ajaxError,e=e.message||t;Voxel.alert(e,"error")}o.target.classList.remove("vx-pending")}).fail(()=>{var e=Voxel_Config.l10n.ajaxError,e=response.message||e;Voxel.alert(e,"error"),o.target.classList.remove("vx-pending")})});var e=()=>{o(".ts-action-follow:not(.vx-event-follow)").on("click",o=>{if(o.currentTarget.classList.add("vx-event-follow"),o.preventDefault(),!Voxel_Config.is_logged_in)return Voxel.authRequired();o.currentTarget.classList.add("vx-pending"),jQuery.get(o.currentTarget.href).always(e=>{var t;e.success?o.currentTarget.classList.toggle("active"):(t=Voxel_Config.l10n.ajaxError,e=e.message||t,Voxel.alert(e,"error")),o.currentTarget.classList.remove("vx-pending")})})};e(),jQuery(document).on("voxel:markup-update",e),o(".post-feed-nav a").on("click",t=>{t.preventDefault();let e=t.currentTarget;var o,t=e.classList.contains("prev-page")?"prev":"next";let r=e.closest(".elementor-widget-container");if(r){let e=r.querySelector(".post-feed-grid");!e||(o=e.querySelector(".ts-preview"))&&(o=o.scrollWidth,e.scrollBy({left:"prev"==t?-o:o,behavior:"smooth"}))}}),o(".elementor-widget-ts-post-feed .post-feed-grid").on("scroll",Voxel.helpers.debounce(o=>{let r=o.currentTarget,a=r.closest(".elementor-widget-container");if(a){o=r.querySelector(".ts-preview");if(o){let e=a.querySelector(".post-feed-nav .prev-page"),t=a.querySelector(".post-feed-nav .next-page");r.scrollLeft>=o.scrollWidth?e.classList.remove("disabled"):e.classList.add("disabled"),r.offsetWidth+r.scrollLeft+10>=r.scrollWidth?t.classList.add("disabled"):t.classList.remove("disabled")}}},100)),o(".ts-nav-menu.ts-custom-links").each((e,t)=>{let a={};o(t).find('.ts-item-link[href^="#"]').each((e,t)=>{try{var o=t.attributes.href.value,r=document.querySelector(o);r&&(a[o]={section:r,toggle:t})}catch(e){}}),Object.values(a).length&&(t=()=>{let t=[];Object.values(a).forEach(e=>{t.push({target:e,pct:Voxel.helpers.viewportPercentage(e.section)})}),t.sort((e,t)=>t.pct-e.pct);var e=t[0];e&&e.pct&&o(e.target.toggle).parent().addClass("current-menu-item").siblings().removeClass("current-menu-item")},o(window).on("scroll",Voxel.helpers.debounce(t,100)),t())})})});
