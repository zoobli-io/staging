!function(e){"function"==typeof define&&define.amd?define("postTypeEditor",e):e()}(function(){"use strict";var i={template:"#post-type-settings-template"},s={template:"#post-type-templates-template",methods:{editWithElementor(e){return this.$root.options.elementor_edit_link.replace("{id}",e)}}},o={template:"#post-type-search-forms-template"},n={template:"#post-type-search-filters-template",data(){return{filter_types:this.$root.options.filter_types,active:null}},methods:{isActive(e){return this.active===e},toggleActive(e){return this.active=e===this.active?null:e},addFilter(e){var t=$.extend(!0,{},e);if(!e.singular){for(var i=1,s=t.type,o=s;this.$root.getFilterByKey(o);)o=s+"-"+ ++i,27===i&&(o=s+"-twentyseven");t.key=o}this.$root.config.search.filters.push(t),this.active=t},canAddFilter(t){return!t.singular||!this.$root.config.search.filters.find(e=>e.type===t.type)},deleteFilter(t){this.$root.config.search.filters=this.$root.config.search.filters.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},r={template:"#post-type-search-order-template",data(){return{active:null,activeClause:null}},methods:{isActive(e){return this.active===e},toggleActive(e){this.active=e===this.active?null:e,this.active&&this.active.clauses.length&&(this.activeClause=this.active.clauses[0])},addOrderingOption(){var e={key:"order-"+(Math.floor(9e3*Math.random())+1e3),label:"Custom order",clauses:[]};this.$root.config.search.order.push(e),this.active=e},deleteOrderingOption(t){this.$root.config.search.order=this.$root.config.search.order.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")},addClause(e,t){e=$.extend(!0,{},e);t.clauses.push(e),this.activeClause=e},deleteClause(t,e){e.clauses=e.clauses.filter(e=>e!==t)},getClauseLabel(e){return this.$root.options.orderby_type_labels[e.type]}}},a={template:"#post-type-fields-template",data(){return{field_types:this.$root.options.field_types,field_presets:this.$root.options.field_presets,active:null}},methods:{isActive(e){return this.active===e},setActive(e){this.active=e},toggleActive(e){return this.active=e===this.active?null:e},addField(e){var t=$.extend(!0,{},e);if(!e.singular){for(var i=1,s=t.type,o=s;this.$root.getFieldByKey(o);)o=s+"-"+ ++i,27===i&&(o=s+"-twentyseven");t.key=o}this.$root.config.fields.push(t),t.__first_edit=!0,this.active=t},deleteField(t){this.$root.config.fields=this.$root.config.fields.filter(e=>e!==t)},canAddPreset(t){return!this.$root.config.fields.find(e=>e.key===t.key)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},l={template:`
		<div class="ts-icon-picker ts-icon-picker-vue">
			<div class="icon-preview" v-html="previewMarkup" @click.prevent="openLibrary" :title="modelValue"></div>
			<div class="basic-ul">
				<li><a href="#" @click.prevent="openLibrary" class="ts-button ts-faded">Choose Icon</a></li>
				<li><a href="#" @click.prevent="uploadSVG" class="ts-button ts-faded">Upload SVG</a></li>
				<li><a href="#" @click.prevent="clear" class="ts-button ts-faded icon-only"><i class="lar la-trash-alt icon-sm"></i></a></li>
			</div>
		</div>
	`,props:["modelValue"],data(){return{previewMarkup:""}},created(){this.preview(this.modelValue)},methods:{preview(e){Voxel_Icon_Picker.getIconPreview(e,e=>this.previewMarkup=e)},openLibrary(){Voxel_Icon_Picker.edit(this.modelValue,e=>{this.setValue(e.library+":"+e.value)})},uploadSVG(){Voxel_Icon_Picker.getSVG(e=>{this.setValue("svg:"+e.id)})},clear(){this.setValue("")},setValue(e){this.preview(e),this.$emit("update:modelValue",e)}}},d={template:"#post-type-field-modal-template",props:["field"],data(){return{tab:"general"}},mounted(){this.field.__first_edit&&this.$refs.keyInput?.enable()},methods:{save(){this.$parent.active=null,delete this.field.__first_edit}}},c={template:"#post-type-field-list-item",props:{field:Object,showDelete:Boolean}},p={template:"#post-type-field-props-template",props:{field:Object,repeater:Object},data(){return{tab:"general"}}},h={template:"#post-type-field-conditions-template",props:{field:Object,repeater:Object},data(){return{conditions:[],fields:(this.repeater||this.$root.config).fields}},created(){this.conditions=this.field.conditions,this.$watch(()=>this.field["enable-conditions"],(e,t)=>{!0!==e||this.conditions.length||this.conditions.push([{source:"",type:""}])})},methods:{setProps(t){var i=this.$root.options.condition_types[t.type];i&&(jQuery.extend(t,i.props,{source:t.source}),Object.keys(t).forEach(e=>{"type"!==e&&void 0===i.props[e]&&delete t[e]}))},getConditionGroups(e){e=e.source.split(".");let t=e[0];var e=e[1]||null,i=this.fields.find(e=>e.key===t);if(!i)return[];i=this.$root.options.supported_conditions[i.type];return null===i?[]:Array.isArray(i)?this._getConditionGroups(i):"object"==typeof i&&null!==e&&Array.isArray(i[e]?.supported_conditions)?this._getConditionGroups(i[e].supported_conditions):[]},_getConditionGroups(e){let i=[];return e.forEach(t=>{var e=Object.values(this.$root.options.condition_types).filter(e=>e.group===t);e.length&&i.push({key:t,label:t.toUpperCase(),types:e})}),i},removeCondition(e,t,i){t.splice(e,1),0===t.length&&1<this.field.conditions.length&&this.field.conditions.splice(i,1)},getSubFields(e){e=this.$root.options.supported_conditions[e.type];return"object"!=typeof e||Array.isArray(e)||null===e?null:e},hasConditions(e){return Array.isArray(this.$root.options.supported_conditions[e.type])}}},u={template:"#post-type-repeater-fields-template",props:{field:Object},data(){return{field_types:this.$root.options.field_types,active:null}},methods:{toggleActive(e){return this.active=e===this.active?null:e},addField(e){var t=$.extend(!0,{},e);if(!e.singular){for(var i=1,s=t.type,o=s;this.field.fields.find(e=>e.key===o);)o=s+"-"+ ++i;t.key=o}this.field.fields.push(t),this.active=t},deleteField(t){this.field.fields=this.field.fields.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}},f={template:`
		<input type="text" readonly :value="modelValue" @click="edit">
	`,props:["modelValue"],data(){return{previewMarkup:""}},methods:{edit(){DTags.edit(this.modelValue,e=>{this.$emit("update:modelValue",e)})}}},g={template:"#post-type-select-field-choices",props:{field:Object},data(){return{active:null}},methods:{add(){var e={value:"",label:"",icon:""};this.field.choices.push(e),this.active=e},remove(t){this.field.choices=this.field.choices.filter(e=>e!==t)},dragStart(){this.$refs["fields-container"].classList.add("drag-active")},dragEnd(){this.$refs["fields-container"].classList.remove("drag-active")}}};jQuery(e=>{var t;document.getElementById("voxel-edit-post-type")&&(window.$=jQuery,(t=Vue.createApp({data(){return{tab:"general",subtab:"base",config:Post_Type_Config,submit_config:"",options:Post_Type_Options,indexing:{loaded:!1,items_total:null,items_indexed:null,running:!1,running_offset:null,running_total:null,running_has_more:null}}},created(){window.PTE=this;var e=new URL(window.location).searchParams.get("tab");e&&(e=e.split("."),this.setTab(e[0],e[1])),this.options.auto_index&&this.indexPosts()},methods:{setTab(e,t=""){this.tab=e,this.subtab=t;var i=new URL(window.location);i.searchParams.set("tab",t?e+"."+t:e),window.history.replaceState(null,null,i)},prepareSubmission(){this.submit_config=JSON.stringify(this.config)},getFieldByKey(t){return this.config.fields.find(e=>e.key===t)},getFilterByKey(t){return this.config.search.filters.find(e=>e.key===t)},getFieldsByType(t){return"string"==typeof t&&(t=[t]),this.config.fields.filter(e=>t.includes(e.type))},getFiltersByType(t){return"string"==typeof t&&(t=[t]),this.config.search.filters.filter(e=>t.includes(e.type))},getProductAdditionsByType(e,t){"string"==typeof t&&(t=[t]);let i=this.$root.options.product_types[e["product-type"]];return i?i.additions.filter(e=>t.includes(e.type)):[]},getIndexData(){this.indexing.loaded||jQuery.get(Voxel_Config.ajax_url,{action:"posts.get_index_data",post_type:this.config.settings.key},e=>{this.indexing.loaded=!0,this.indexing.items_total=e.items_total,this.indexing.items_indexed=e.items_indexed,this.indexing.table_name=e.table_name,this.indexing.table_exists=e.table_exists})},indexPosts(){this.indexing.running=!0,jQuery.get(Voxel_Config.ajax_url,{action:"posts.index_all",post_type:this.config.settings.key},e=>{this.indexing.running_total=e.total,this.indexing.running_offset=e.offset,this.indexing.running_has_more=e.has_more,e.has_more&&this.indexPosts()})}},computed:{indexingStatus(){if(!this.indexing.running)return"";if(null===this.indexing.running_offset)return"Indexing posts...";if(!this.indexing.running_has_more)return 0===(e=this.indexing.running_total)?"No posts to index.":`Indexed ${e}/${e} (100%)`;var e=this.indexing.running_total,t=this.indexing.running_offset;let i=t/e*100;return`Indexing ${t}/${e} (${i.toFixed(1)}%)`}}})).component("general-settings",i),t.component("page-templates",s),t.component("search-forms",o),t.component("search-filters",n),t.component("search-order",r),t.component("field-key",Voxel_Backend.components.Field_Key),t.component("taxonomy-select",Voxel_Backend.components.Taxonomy_Select),t.component("media-select",Voxel_Backend.components.Media_Select),t.component("icon-picker",l),t.component("form-fields",a),t.component("field-conditions",h),t.component("field-modal",d),t.component("field-list-item",c),t.component("draggable",vuedraggable),t.component("repeater-fields",u),t.component("field-props",p),t.component("dtag-input",f),t.component("select-field-choices",g),t.mount("#voxel-edit-post-type"))})});
