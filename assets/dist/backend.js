!function(e){"function"==typeof define&&define.amd?define("backend",e):e()}(function(){"use strict";jQuery(s=>{var e,t;document.getElementById("voxel-icon-picker")&&((e=Vue.createApp({template:"#voxel-icon-picker-template",data(){return{activePack:null,config:window.Voxel_Icon_Picker_Config,search:"",searchResults:{},inited:!1,opened:!1,fileFrame:null}},created(){window.Voxel_Icon_Picker=this},methods:{selectIcon(e,t){var i=this.config[t],i={value:i.displayPrefix+" "+i.prefix+e,library:t};this.emitter.emit("save",i)},init(){Object.values(this.config).forEach(e=>{this.loadPack(e)}),this.setPack(Object.keys(this.config)[0])},loadStylesheet(e){s(`link[href="${e}"]`).length||s("head").append(s("<link>",{rel:"stylesheet",type:"text/css",href:e}))},setPack(e){this.activePack=this.config[e],this.activePack.loaded||this.loadPack(this.activePack)},loadPack(t){t.loaded||(t.enqueue&&t.enqueue.forEach(e=>this.loadStylesheet(e)),t.url&&this.loadStylesheet(t.url),s.getJSON(t.fetchJson,e=>{t.list=e?.icons||[],t.loaded=!0}))},open(){this.inited||(this.init(),this.inited=!0),this.opened=!0},close(){this.opened=!1},edit(e,t){this.open();var i=e=>{t(e),this.emitter.off("save",i),this.close()};this.emitter.on("save",i)},filter(){this.searchResults={},Object.values(this.config).forEach(e=>{var t=[];this.loadPack(e),e.list.forEach(e=>{-1!==e.indexOf(this.search)&&t.push(e)}),t.length&&(this.searchResults[e.name]=t)})},getSVG(t){this.fileFrame||(this.fileFrame=wp.media({states:new wp.media.controller.Library({multiple:!1,library:wp.media.query({type:["image/svg+xml"]})})}));var e=Vue.toRaw(this.fileFrame);e.open(),e.once("select",()=>{e.state().get("selection").each(e=>t(e.toJSON()))})},getIconPreview(e,t){var i,a=e?.substr(0,e.indexOf(":")),s=e?.substr(e.indexOf(":")+1);return"svg"===a?(i=e=>`<img src='${e.url}' alt='${e.alt}'>`,(e=wp.media.attachment(s)).get("url")?t(i(e.toJSON())):(async()=>wp.media.attachment(s).fetch())().then(e=>t(i(e)))):this.config[a]?(e=this.config[a],this.loadPack(e),t(`<i class='${s}'></i>`)):t("")}}})).config.globalProperties.emitter=Voxel_Backend.mitt(),e.mount("#voxel-icon-picker"),t=(e,t)=>{var i,a,t=s(t);t.data("icon-picker-inited")||(t.data("icon-picker-inited",!0),i=t.find("input"),a=t.find(".icon-preview"),Voxel_Icon_Picker.getIconPreview(i.val(),e=>a.html(e).attr("title",i.val())),t.find(".choose-icon, .icon-preview").on("click",e=>{e.preventDefault(),Voxel_Icon_Picker.edit(i.val(),e=>{e&&i.val(e.library+":"+e.value),Voxel_Icon_Picker.getIconPreview(i.val(),e=>a.html(e).attr("title",i.val()))})}),t.find(".clear-icon").on("click",e=>{e.preventDefault(),i.val(""),a.html("").attr("title","")}),t.find(".upload-svg").on("click",e=>{e.preventDefault(),Voxel_Icon_Picker.getSVG(e=>{e&&i.val("svg:"+e.id),Voxel_Icon_Picker.getIconPreview(i.val(),e=>a.html(e).attr("title",i.val()))})}))},window.voxel_init_icon_pickers=()=>{s(".ts-icon-picker:not(.ts-icon-picker-vue)").each(t)},window.voxel_init_icon_pickers())}),jQuery(e=>{var t,i;document.getElementById("voxel-reorder-terms")&&(t=document.getElementById("voxel-reorder-terms"),(i=Vue.createApp({data(){return{terms:JSON.parse(t.dataset.terms)}},methods:{getTermTree(){return this.terms.map(e=>({id:e.id,terms:this.getChildTerms(e)}))},getChildTerms(e){return e.children.map(e=>({id:e.id,terms:this.getChildTerms(e)}))},onSubmit(){this.$refs.termsInput.value=JSON.stringify(this.getTermTree())}}})).component("draggable",vuedraggable),i.component("term-list",{template:"#voxel-reorder-term-list-component",props:["terms","group","level","parent"],data(){return{items:this.terms}},methods:{onDragStart(){(this.$parent.$refs.list?this:this.$parent).$parent.$refs.list.classList.add("drag-active")},onDragEnd(){(this.$parent.$refs.list?this:this.$parent).$parent.$refs.list.classList.remove("drag-active"),1===this.level?this.$root.terms=this.items:this.parent.children=this.items},toggleCollapse(e){e.target.parentElement.classList.toggle("collapsed")}}}),i.mount("#voxel-reorder-terms"))});var i={template:`
		<div class="ts-icon-picker ts-icon-picker-vue">
			<div class="icon-preview" v-html="previewMarkup" @click.prevent="openLibrary" :title="modelValue"></div>
			<div class="basic-ul">
				<li><a href="#" @click.prevent="openLibrary" class="ts-button ts-faded">Choose Icon</a></li>
				<li><a href="#" @click.prevent="uploadSVG" class="ts-button ts-faded">Upload SVG</a></li>
				<li><a href="#" @click.prevent="clear" class="ts-button ts-faded icon-only"><i class="lar la-trash-alt icon-sm"></i></a></li>
			</div>
		</div>
	`,props:["modelValue"],data(){return{previewMarkup:""}},created(){this.preview(this.modelValue)},methods:{preview(e){Voxel_Icon_Picker.getIconPreview(e,e=>this.previewMarkup=e)},openLibrary(){Voxel_Icon_Picker.edit(this.modelValue,e=>{this.setValue(e.library+":"+e.value)})},uploadSVG(){Voxel_Icon_Picker.getSVG(e=>{this.setValue("svg:"+e.id)})},clear(){this.setValue("")},setValue(e){this.preview(e),this.$emit("update:modelValue",e)}}},e=(jQuery(e=>{var t=document.getElementById("voxel-term-settings");if(t){window.$=jQuery;let e=JSON.parse(t.dataset.config).fields||{};t=Vue.createApp({data(){return{fields:{icon:e.icon||"",image:e.image||"",area:{address:e.area?.address||"",swlat:e.area?.swlat||"",swlng:e.area?.swlng||"",nelat:e.area?.nelat||"",nelng:e.area?.nelng||""}}}},mounted(){new Voxel.Maps.Autocomplete(this.$refs.addressInput,e=>{e?this.usePlaceData(e):(this.fields.area.address=null,this.fields.area.swlat=null,this.fields.area.swlng=null,this.fields.area.nelat=null,this.fields.area.nelng=null)})},methods:{usePlaceData(e){var t=e.viewport.getSouthWest(),i=e.viewport.getNorthEast();this.fields.area.address=e.address,this.fields.area.swlat=this._shortenPoint(t.getLatitude()),this.fields.area.swlng=this._shortenPoint(t.getLongitude()),this.fields.area.nelat=this._shortenPoint(i.getLatitude()),this.fields.area.nelng=this._shortenPoint(i.getLongitude())},_shortenPoint(e){return e.toString().substr(0,e<0?9:8)}}});t.component("media-select",Voxel_Backend.components.Media_Select),t.component("icon-picker",i),t.mount("#voxel-term-settings")}}),{props:["modelValue","editable","unlocked"],template:`
		<input type="text" :value="modelValue" ref="input" :disabled="!editing" @blur="save">
		<div v-if="editable" class="edit-key">
			<a v-if="!editing" @click.prevent="edit" href="#" class="ts-button ts-faded" tabindex="-1">
				<i class="las la-lock"></i>
			</a>
			<a v-if="editing" @click.prevent="save" href="#" class="ts-button ts-faded" tabindex="-1">
				<i class="las la-unlock"></i>
			</a>
		</div>
	`,data(){return{editing:!1}},created(){this.unlocked&&this.enable()},methods:{edit(){this.editable&&(this.editing=!0,this.$refs.input.focus(),requestAnimationFrame(()=>{this.$refs.input.focus()}))},enable(){this.editable&&(this.editing=!0)},save(){this.editing=!1,this.$emit("update:modelValue",Voxel_Backend.helpers.slugify(this.$refs.input.value))}}});window.Voxel_Backend={mitt:function(a){return{all:a=a||new Map,on(e,t){const i=a.get(e);i&&i.push(t)||a.set(e,[t])},off(e,t){const i=a.get(e);i&&i.splice(i.indexOf(t)>>>0,1)},emit(t,i){(a.get(t)||[]).slice().map(e=>{e(i)}),(a.get("*")||[]).slice().map(e=>{e(t,i)})}}},helpers:{isAnyPartOfElementInViewport(e){var e=e.getBoundingClientRect(),t=window.innerHeight||document.documentElement.clientHeight,i=window.innerWidth||document.documentElement.clientWidth,t=e.top<=t&&0<=e.top+e.height,i=e.left<=i&&0<=e.left+e.width;return t&&i},slugify(e){return e.toString().trim().toLowerCase().replace(/\s+/g,"-").replace(/[^\w\-\.]+/g,"").replace(/^-+/,"")}},components:{Field_Key:e,Taxonomy_Select:{props:["modelValue","taxonomies"],template:`
		<select ref="select" :value="modelValue" @change="save">
			<option v-for="choice in choices" :value="choice.key">{{ choice.label }}</option>
			<option value="__create"> &mdash; Create new taxonomy</option>
		</select>
		<div v-if="modelValue === '__create'" class="ts-row wrap-row create-tax-group">
			<div class="ts-form-group ts-col-1-3">
				<label>Taxonomy label</label>
				<input type="text" v-model="customTax.label" @input="setTaxonomyKey" ref="taxLabel">
			</div>
			<div class="ts-form-group ts-col-1-3">
				<label>Taxonomy key</label>
				<input type="text" v-model="customTax.key">
			</div>
			<div class="ts-form-group ts-col-1-3">
				<a href="#" class="ts-button ts-faded" @click.prevent="createTaxonomy">Create</a>
			</div>
		</div>
	`,data(){return{choices:[],customTax:{label:"",key:""}}},created(){console.log(this),this.choices=this.taxonomies},methods:{save(){this.$emit("update:modelValue",this.$refs.select.value),"__create"===this.modelValue&&this.$refs.taxLabel.focus()},setTaxonomyKey(){var e=this.$root.config.settings.key,t=Voxel_Backend.helpers.slugify(this.customTax.label).replace(/-/g,"_");this.customTax.key=e+"_"+t},createTaxonomy(){jQuery.post({url:Voxel_Config.ajax_url+"&action=create_taxonomy",data:{post_type:this.$root.config.settings.key,label:this.customTax.label,key:this.customTax.key},success:e=>{e.success?(this.choices[e.taxonomy.key]=e.taxonomy,this.$emit("update:modelValue",e.taxonomy.key)):alert(e.message)}})}}},Media_Select:{props:["modelValue","fileType","multiple"],template:`
		<div class="ts-media-select">
			<template v-if="modelValue">
				<img v-if="selected" :src="selected.sizes.thumbnail?.url || selected.url" :alt="selected.alt">
				<p>Attachment #{{ modelValue }}</p>
			</template>
			<div class="basic-ul">
				<a class="ts-button ts-faded" href="#" @click.prevent="open">Pick image</a>
				<a class="ts-button ts-faded icon-only" href="#" v-if="selected" @click.prevent="clear"><i class="lar la-trash-alt icon-sm"></i></a>
			</div>
		</div>
	`,data(){return{fileFrame:null,opened:!1,inited:!1,selected:null}},created(){var e;this.modelValue&&((e=wp.media.attachment(this.modelValue)).get("url")?this.selected=e.toJSON():(async()=>wp.media.attachment(this.modelValue).fetch())().then(e=>this.selected=e))},methods:{open(){this.inited||(this.inited=!0,e={},this.multiple&&(e.multiple=!0),this.fileType&&(e.library=wp.media.query({type:"string"==typeof this.fileType?[this.fileType]:this.fileType})),this.fileFrame=wp.media({states:new wp.media.controller.Library(e)}));var e,t=Vue.toRaw(this.fileFrame);t.open(),t.once("select",()=>{t.state().get("selection").each(e=>{this.selected=e.toJSON(),this.$emit("update:modelValue",this.selected.id)})}),this.opened=!0},clear(){this.selected=null,this.$emit("update:modelValue",null)}}}},alert(e,t="info",i=7500){var t=document.getElementById("vx-alert-tpl").textContent.replace("{type}",t).replace("{message}",e),a=jQuery(t).hide();a.find("> a").on("click",e=>{e.preventDefault(),a.fadeOut(100,()=>a.remove())}),"number"==typeof i&&0<i&&setTimeout(()=>a.fadeOut(100,()=>a.remove()),i),a.appendTo(jQuery("#vx-alert")),a.fadeIn(100)},_nav_dtags(t){DTags.edit(t.querySelector("input").value,e=>{t.querySelector("input").value=e})}},document.addEventListener("DOMContentLoaded",()=>{Array.from(document.querySelectorAll(".vx-use-vue")).forEach(e=>{e.__vue_app__||Vue.createApp({el:e,data(){return{config:null,state:{},tab:null}},created(){this.config=jQuery.extend(!0,this.config,JSON.parse(this.$options.el.dataset.config)),this.tab=this.config.tab},watch:{tab(){this.config.tab=this.tab;var e=new URL(window.location);e.searchParams.set("tab",this.tab),window.history.replaceState(null,null,e)}}}).mount(e)})})});
