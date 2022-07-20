!function(e){"function"==typeof define&&define.amd?define("createPost",e):e()}(function(){"use strict";var s={template:"#create-post-media-popup",props:{multiple:{type:Boolean,default:!0},ignore:{type:Array,default:[]}},emits:["save","blur","open"],data(){return{page:1,files:[],selected:{},active:!1,loading:!0,hasMore:!1,firstLoad:!0}},methods:{getStyle(e){return e.type.startsWith("image/")?`background-image: url('${e.preview}');`:""},selectFile(e){this.selected[e.id]?delete this.selected[e.id]:this.selected[e.id]=e},loadMedia(){var e=Voxel_Config.ajax_url+"&action=list_media&page="+this.page;jQuery.get(e,e=>{this.loading=!1,e.files&&(this.files=this.files.concat(e.files)),this.hasMore=!!e.has_more})},loadMore(){this.loading=!0,this.page++,this.loadMedia()},openLibrary(){this.$emit("open"),this.firstLoad&&this.loadMedia(),this.firstLoad=!1,this.active=!this.active},isImage(e){return e.type.startsWith("image/")},save(){this.active=!1,this.$emit("save",this.selected),this.selected={}},clear(){this.selected={}}}},a={template:"#create-post-title-field",props:{field:Object}},l={template:"#create-post-text-field",props:{field:Object}},r={template:"#create-post-texteditor-field",props:{field:Object,repeaterIndex:Number},data(){return{rendered:!1}},created(){var e;this.field.in_repeater&&"plain-text"!==this.field.props.editorType&&(e=Voxel.helpers.sequentialId(),this.field.props.editorId+=e,this.field.props.toolbarId+=e,this.field.props.editorConfig.textarea_name+=e,this.field.props.editorConfig.tinymce.fixed_toolbar_container+=e)},mounted(){this.renderEditor()},methods:{renderEditor(){"plain-text"===this.field.props.editorType||this.rendered||this.field.step!==this.$root.currentStep.key||(this.$refs.editor.innerHTML=this.field.value,jQuery(()=>{this.field.props.editorConfig.tinymce.init_instance_callback=e=>e.fire("focus"),wp.oldEditor.initialize(this.field.props.editorId,this.field.props.editorConfig),tinyMCE.editors[this.field.props.editorId].on("change",e=>{this.field.value=e.target.getContent()})}),this.rendered=!0)}},watch:{"$root.currentStep"(){this.renderEditor()}}},o={template:"#create-post-texteditor-field",extends:r},d={template:"#create-post-number-field",props:{field:Object},created(){var e=parseFloat(this.field.value);isNaN(e)||(this.field.value=e)},methods:{increment(){"number"!=typeof this.field.value?this.setValue(this.field.props.min):this.setValue(this.field.value+this.field.props.step)},decrement(){"number"!=typeof this.field.value?this.setValue(this.field.props.min):this.setValue(this.field.value-this.field.props.step)},setValue(e){""===e||"number"!=typeof e?this.field.value=null:e<this.field.props.min?this.field.value=this.field.props.min:e>this.field.props.max?this.field.value=this.field.props.max:this.field.value=Number(e.toFixed(this.field.props.precision))}}},n={template:"#create-post-email-field",props:{field:Object}},u={template:"#create-post-url-field",props:{field:Object}},p={template:"#create-post-file-field",props:{field:Object,sortable:{type:Boolean,default:!0}},data(){return{accepts:""}},created(){null===this.field.value&&(this.field.value=[]),this.accepts=Object.values(this.field.props.allowedTypes).join(", ")},mounted(){this.updatePreviews(),jQuery(this.$refs.input).on("change",e=>{for(var t=0;t<e.target.files.length;t++){var i=e.target.files[t],s=URL.createObjectURL(i);this.field.value.push({source:"new_upload",name:i.name,type:i.type,preview:s,item:i})}this.$refs.input.value="",this.updatePreviews()}),jQuery(()=>{var e=jQuery(this.$refs.fileList);this.sortable&&(e.sortable({items:"> .ts-file",helper:"clone",appendTo:this.$el,containment:"parent",tolerance:"intersect",revert:150}),e.on("sortupdate",()=>{var i=[];e.find(".ts-file").each((e,t)=>{i.push(this.field.value[t.dataset.index])}),this.field.value=i,this.updatePreviews()})),e.find(".pick-file-input").on("click",e=>{e.preventDefault(),this.$refs.input.click()})})},unmounted(){setTimeout(()=>{Object.values(this.field.value).forEach(e=>{"new_upload"===e.source&&URL.revokeObjectURL(e.preview)})},10),this.sortable&&jQuery(this.$refs.fileList).sortable("destroy")},methods:{getStyle(e){return e.type.startsWith("image/")?`background-image: url('${e.preview}');`:""},updatePreviews(){var e=jQuery(this.$refs.fileList),s=[];this.field.value.forEach((e,t)=>{var i=e.type.startsWith("image/"),i=jQuery(`
					<div class="ts-file ${i?"ts-file-img":""}" style="${this.getStyle(e)}" data-index="${t}">
						<div class="ts-file-info">
							<i class="las la-cloud-upload-alt"></i><code></code>
						</div>
						<a href="#" class="ts-remove-file flexify"><i class="las la-times" aria-hidden="true"></i></a>
					</div>
				`);i.find("code").text(e.name),i.find("a").on("click",e=>{e.preventDefault(),this.field.value.splice(t,1),this.updatePreviews()}),s.push(i)}),e.find(".pick-file-input").siblings().remove(),e.append(s)},onMediaPopupSave(e){var t={};this.field.value.forEach(e=>{"existing"===e.source&&(t[e.id]=!0)}),Object.values(e).forEach(e=>{t[e.id]||this.field.value.push(e)}),this.updatePreviews()},onSubmit(t,i){var s=`files[${this.field.id}][]`;t[this.field.key]=[],this.field.value.forEach(e=>{"new_upload"===e.source?(i.append(s,e.item),t[this.field.key].push("uploaded_file")):"existing"===e.source&&t[this.field.key].push(e.id)})}}},h={template:"#create-post-phone-field",props:{field:Object}},c={template:"#create-post-switcher-field",props:{field:Object},data(){return{switcherId:"_switch-"+this.field.key}}},f={template:"#create-post-location-field",props:{field:Object},data(){return{map:null}},mounted(){Voxel.Maps.await(()=>{new Voxel.Maps.Autocomplete(this.$refs.addressInput,e=>{e?(this.field.value.address=e.address,this.field.value.latitude=e.latlng.getLatitude(),this.field.value.longitude=e.latlng.getLongitude(),this.map&&this.map.fitBounds(e.viewport)):this.field.value.address=this.$refs.addressInput.value}),this.field.value.map_picker&&this.setupMap()})},methods:{setupMap(){this.map||Voxel.Maps.await(()=>{this.map=new Voxel.Maps.Map({el:this.$refs.mapDiv}),this.marker=new Voxel.Maps.Marker({map:this.map});var e=this.getMarkerPosition();e&&(this.map.setCenter(e),this.marker.setPosition(e)),this.map.addListener("click",e=>{e=this.map.getClickPosition(e);this.field.value.latitude=e.getLatitude(),this.field.value.longitude=e.getLongitude(),Voxel.Maps.getGeocoder().geocode(e.toGeocoderFormat(),e=>{this.field.value.address=e.address})})})},getMarkerPosition(){return"number"!=typeof this.field.value.latitude||"number"!=typeof this.field.value.longitude?null:new Voxel.Maps.LatLng(this.field.value.latitude,this.field.value.longitude)},geolocate(){Voxel.Maps.getGeocoder().getUserLocation({fetchAddress:!0,receivedPosition:e=>{this.field.value.latitude=e.getLatitude(),this.field.value.longitude=e.getLongitude()},receivedAddress:e=>{this.field.value.address=e.address,this.map&&this.map.fitBounds(e.viewport)},positionFail:()=>Voxel.alert("Could not retrieve location, please check permissions.","error"),addressFail:()=>Voxel.alert("Could not determine address.","error")})}},watch:{"field.value.map_picker"(){this.setupMap()},"field.value.latitude"(){this.marker?.setPosition(this.getMarkerPosition())},"field.value.longitude"(){this.marker?.setPosition(this.getMarkerPosition())}}},m={template:"#create-post-work-hours-field",props:{field:Object},data(){return{}},methods:{addGroup(){this.field.value.push({days:[],status:"hours",hours:[]})},removeGroup(e){this.field.value.splice(this.field.value.indexOf(e),1)},removeHours(e,t){t.hours.splice(t.hours.indexOf(e),1)},addHours(e){e.hours.push({from:"09:00",to:"17:00"})},displayDays(e){return e.map(e=>this.field.props.weekdays[e]).filter(Boolean).join(", ")},displayTime(e){return new Date("2021-01-01 "+e).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/,"$1$3")},id(){return this.field.id+"."+Object.values(arguments).join(".")},isChecked(e,t){return t.includes(e)},check(e,t){t.includes(e)?t.splice(t.indexOf(e),1):t.push(e)},isDayAvailable(e,t){return t.days.includes(e)||this.unusedDays.includes(e)}},computed:{unusedDays(){var t=[];return this.field.value.forEach(e=>t=t.concat(e.days)),Object.keys(this.field.props.weekdays).filter(e=>!t.includes(e))}}};const v={template:"#create-post-term-list",props:["terms","parent-term","previous-list","list-key"],data(){return{taxonomyField:Voxel.helpers.getParent(this,"taxonomy-field"),scrollPosition:0}},methods:{selectTerm(e){e.children&&e.children.length?(this.taxonomyField.slide_from="right",this.scrollPosition=this.$refs.list.scrollTop,this.taxonomyField.active_list="terms_"+e.id):this.taxonomyField.selectTerm(e)},goBack(){this.taxonomyField.slide_from="left",this.scrollPosition=this.$refs.list.scrollTop,this.taxonomyField.active_list=this.previousList},beforeEnter(e){this.$nextTick(()=>{e.scrollTop=this.scrollPosition})}},computed:{termsWithChildren(){return this.terms.filter(e=>e.children&&e.children.length)}}};var y={template:"#create-post-taxonomy-field",name:"taxonomy-field",props:{field:Object},data(){return{value:{},terms:this.field.props.terms,active_list:"toplevel",slide_from:"right",search:"",displayValue:""}},created(){this.value=this.field.props.selected,this.displayValue=this._getDisplayValue()},methods:{onSave(){this.field.value=this.isFilled()?Object.keys(this.value):null,this.displayValue=this._getDisplayValue(),this.$refs.formGroup.$refs.popup.$emit("blur")},onBlur(){},onClear(){this.value={},this.search="",this.$refs.searchInput.focus()},isFilled(){return Object.keys(this.value).length},_getDisplayValue(){return Object.values(this.value).map(e=>e.label).join(", ")},selectTerm(e){this.value[e.slug]?delete this.value[e.slug]:this.field.props.multiple?this.value[e.slug]=e:this.value={[e.slug]:e}}},computed:{searchResults(){if(!this.search.trim().length)return!1;var t=[],i=e=>{-1!==e.label.toLowerCase().indexOf(this.search.trim().toLowerCase())&&t.push(e),e.children&&e.children.forEach(i)};return this.terms.forEach(i),t}}},g={template:"#create-post-product-field",props:{field:Object},data(){return{enabled:!0,base_price:null,price_id:null,notes:null,interval:{unit:"month",count:1},calendar:{make_available_next:null,bookable_per_instance:1,excluded_weekdays:{},excluded_days:[],timeslots:[{days:[],slots:[]}]},state:{excluded_weekdays:{},excluded_weekday_indexes:[],weekdays_display_value:"",weekday_indexes:{sun:0,mon:1,tue:2,wed:3,thu:4,fri:5,sat:6},recurring_dates:[]}}},created(){var e;null!==this.field.value&&(this.enabled=this.field.value.enabled,this.base_price=this.field.value.base_price,this.price_id=this.field.value.price_id,this.notes=this.field.value.notes,e=this.field.value.calendar,this.calendar={make_available_next:e.make_available_next,bookable_per_instance:e.bookable_per_instance||1,excluded_weekdays:e.excluded_weekdays||{},excluded_days:e.excluded_days||[],timeslots:e.timeslots||[{days:[],slots:[]}]},e=this.field.value.interval,this.interval={unit:e.unit||"month",count:e.count||1})},mounted(){this.$nextTick(()=>this.getRecurrences())},methods:{saveWeekdayExclusions(){this.calendar.excluded_weekdays=jQuery.extend({},this.state.excluded_weekdays),this.state.excluded_weekday_indexes=[],Object.keys(this.calendar.excluded_weekdays).forEach(e=>{this.state.excluded_weekday_indexes.push(this.state.weekday_indexes[e])});var t=[];Object.keys(this.field.props.weekdays).forEach(e=>{this.calendar.excluded_weekdays[e]&&t.push(this.field.props.weekdays[e])}),this.state.weekdays_display_value=t.join(", "),this.$refs.datePicker?.refresh(),this.$refs.weekdayExclusions?.$refs?.popup?.$emit("blur")},clearWeekdayExclusions(){this.state.excluded_weekdays={},this.saveWeekdayExclusions()},toggleWeekdayExclusion(e){this.state.excluded_weekdays[e]?delete this.state.excluded_weekdays[e]:this.state.excluded_weekdays[e]=!0},onSubmit(e,t){var i={};this.field.props.additions?.map(e=>{i[e.key]=e.values}),e[this.field.key]={enabled:this.enabled,base_price:this.base_price,price_id:this.price_id,interval:this.interval,calendar:{make_available_next:this.calendar.make_available_next,bookable_per_instance:this.calendar.bookable_per_instance,excluded_weekdays:Object.keys(this.calendar.excluded_weekdays),excluded_days:this.calendar.excluded_days,timeslots:this.calendar.timeslots},additions:i,notes:this.notes}},getRecurrences(){if("recurring-date"===this.field.props.calendar_type){let e=this.field.props.recurring_date_field,i=this.$root.$refs["field:"+e];var t;i&&i.getUpcoming&&(this.$watch(()=>this.$root.fields[e].value,t=()=>{let t=new Date;t.setDate(t.getDate()+this.calendar.make_available_next),this.state.recurring_dates=i.getUpcoming(20).filter(e=>e.start.getTime()<=t.getTime())},{deep:!0}),this.$watch(()=>this.calendar.make_available_next,t,{deep:!0}),t())}},formatRecurrence(e){var t=Voxel.helpers.dateFormat(e.start),e=Voxel.helpers.dateFormat(e.end);return t===e?t:t+" - "+e}}},x={template:'<div class="ts-calendar-wrapper ts-availability-calendar"><input type="hidden" ref="input"></div>',data(){return{picker:null,calendar:this.$parent.calendar,today:new Date}},mounted(){this.picker=new Pikaday({field:this.$refs.input,container:this.$el,bound:!1,firstDay:1,keyboardInput:!1,onSelect:e=>{var t=Voxel.helpers.dateFormatYmd(e);this.calendar.excluded_days.includes(t)?this.calendar.excluded_days=this.calendar.excluded_days.filter(e=>e!==t):this.calendar.excluded_days.push(t)},selectDayFn:e=>this.calendar.excluded_days.includes(Voxel.helpers.dateFormatYmd(e)),disableDayFn:e=>{if(e<this.today||this.$parent.state.excluded_weekday_indexes.includes(e.getDay()))return!0}})},unmounted(){this.picker.destroy()},methods:{refresh(){this.picker.draw()}}},k={template:"#create-post-product-timeslots",data(){return{timeslots:this.$parent.calendar.timeslots,field:this.$parent.field,create:{from:"09:00",to:"09:30"},generate:{from:"09:00",to:"17:00",length:30}}},created(){this.updateWeekdayExclusions()},methods:{isDayUsed(e,t){return-1<t.days.indexOf(e)},addSlotGroup(){this.timeslots.push({days:[],slots:[]})},isDayAvailable(e,t){return this.isDayUsed(e,t)||-1!==this.unusedDays.indexOf(e)},addSlot(e,t){var i=e.slots.find(e=>e.from===this.create.from&&e.to===this.create.to);this.create.from&&this.create.to&&!i&&e.slots.push({from:this.create.from,to:this.create.to}),this.closeSlotPopup(t)},closeSlotPopup(e){this.$refs[this.groupKey(e,"add")].$refs.popup.$emit("blur")},removeSlot(t,e){e.slots=e.slots.filter(e=>e!==t)},removeGroup(t){this.timeslots=this.timeslots.filter(e=>e!==t)},saveDays(e){this.$refs[this.groupKey(e)].$refs.popup.$emit("blur")},clearDays(e){e.days=[]},toggleDay(t,e){this.isDayUsed(t,e)?e.days=e.days.filter(e=>e!==t):e.days.push(t)},groupKey(e,t=""){t=t.length?"."+t:"";return this.field.key+".slots."+e+t},daysLabel(e,t){return e.days.map(e=>this.field.props.weekdays[e]).join(", ")||t},displaySlot(e){return e.from&&e.to?new Date("2021-01-01 "+e.from).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/,"$1$3")+" — "+new Date("2021-01-01 "+e.to).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/,"$1$3"):""},generateSlots(e,t){var i=this.generate.from.split(":"),s=this.generate.to.split(":"),a=this.generate.length,l=parseInt(i[0],10),i=parseInt(i[1],10),r=parseInt(s[0],10),s=parseInt(s[1],10);if(!(isNaN(l)||isNaN(i)||isNaN(r)||isNaN(s)||a<5)){var o=[],d=60*l+i,n=60*r+s;for(n<=d&&(n+=1440);d<n&&!(n<d+a);){var u={hour:Math.floor(d/60),minute:d%60},p={hour:Math.floor((d+a)/60),minute:(d+a)%60};24<=u.hour&&(u.hour-=24),24<=p.hour&&(p.hour-=24),o.push({from:[u.hour.toString().padStart(2,"0"),u.minute.toString().padStart(2,"0")].join(":"),to:[p.hour.toString().padStart(2,"0"),p.minute.toString().padStart(2,"0")].join(":")}),d+=a}e.slots=o,this.closeGeneratePopup(t)}},closeGeneratePopup(e){this.$refs[this.groupKey(e,"generate")].$refs.popup.$emit("blur")},updateWeekdayExclusions(){this.$parent.state.excluded_weekdays=this.unusedDays.reduce((e,t)=>(e[t]=!0,e),{}),this.$parent.saveWeekdayExclusions()}},computed:{unusedDays(){var t=[];return this.timeslots.forEach(e=>t=t.concat(e.days)),Object.keys(this.field.props.weekdays).filter(e=>!t.includes(e))}},watch:{unusedDays(){this.updateWeekdayExclusions()}}},_={template:"#create-post-ui-image-field",props:{field:Object}},w={template:"#create-post-ui-heading-field",props:{field:Object}},b={template:"#create-post-repeater-field",props:{field:Object},data(){return{rows:this.field.props.rows}},created(){this.rows.forEach(e=>this.$root.setupConditions(e))},methods:{addRow(){var e=Vue.reactive(jQuery.extend(!0,{},this.field.props.fields));this.rows.push(e),this.$root.setupConditions(e)},deleteRow(e){this.rows.splice(e,1)},onSubmit(t,a){t[this.field.key]=[],this.rows.forEach((e,i)=>{var s={};Object.values(e).forEach(e=>{var t=this.$refs[`row#${i}:`+e.key];!e.is_ui&&this.$root.conditionsPass(e)&&(t&&"function"==typeof t.onSubmit?t.onSubmit(s,a):null!==e.value&&(s[e.key]=e.value))}),t[this.field.key].push(s)})}}},D={template:"#create-post-timezone-field",props:{field:Object},data(){return{search:""}},methods:{onSave(){this.$refs.formGroup.blur()},onClear(){this.field.value=null,this.search=""}},computed:{choices(){return this.search.trim().length?this.field.props.list.filter(e=>-1!==e.toLowerCase().indexOf(this.search.trim().toLowerCase())):this.field.props.list}}},$={template:"#create-post-recurring-date-field",props:{field:Object},components:{datePicker:{template:"#recurring-date-picker",props:["modelValue","minDate"],data(){return{picker:null}},mounted(){this.picker=new Pikaday({field:this.$refs.input,container:this.$refs.calendar,bound:!1,firstDay:1,keyboardInput:!1,defaultDate:this.modelValue?new Date(this.modelValue):null,minDate:this.minDate?new Date(this.minDate):new Date,onSelect:e=>{this.$emit("update:modelValue",Voxel.helpers.dateFormatYmd(e))},selectDayFn:e=>this.modelValue&&this.modelValue===Voxel.helpers.dateFormatYmd(e)})},unmounted(){setTimeout(()=>this.picker.destroy(),200)},watch:{modelValue(){this.picker.draw()}}},dateRangePicker:{template:"#recurring-date-range-picker",props:["start","end"],data(){return{picker:null,activePicker:"start",value:{start:this.start?new Date(this.start+" 00:00:00"):null,end:this.end?new Date(this.end+" 00:00:00"):null}}},mounted(){this.picker=new Pikaday({field:this.$refs.input,container:this.$refs.calendar,bound:!1,firstDay:1,keyboardInput:!1,numberOfMonths:2,defaultDate:this.value.start,startRange:this.value.start,endRange:this.value.end,theme:"pika-range",onSelect:e=>{"start"===this.activePicker?(this.setStartDate(e),this.activePicker="end"):this.setEndDate(e),this.$emit("update:start",this.value.start?Voxel.helpers.dateFormatYmd(this.value.start):null),this.$emit("update:end",this.value.end?Voxel.helpers.dateFormatYmd(this.value.end):null),this.refresh()},selectDayFn:e=>!(!this.value.start||e.toDateString()!==this.value.start.toDateString())||(!(!this.value.end||e.toDateString()!==this.value.end.toDateString())||void 0),disableDayFn:e=>{if("end"===this.activePicker&&this.value.start&&e<this.value.start)return!0}}),this.setStartDate(this.value.start),this.setEndDate(this.value.end),this.refresh()},unmounted(){setTimeout(()=>this.picker.destroy(),200)},methods:{setStartDate(e){this.value.start=e,this.picker.setStartRange(e),this.value.end&&this.value.start>this.value.end&&this.setEndDate(null)},setEndDate(e){this.value.end=e,this.picker.setEndRange(e)},refresh(){this.picker.draw()},reset(){this.setStartDate(null),this.setEndDate(null),this.refresh(),this.activePicker="start"}},computed:{startLabel(){return this.value.start?Voxel.helpers.dateFormat(this.value.start):"From"},endLabel(){return this.value.end?Voxel.helpers.dateFormat(this.value.end):"To"}}}},methods:{add(){this.field.value.push({startDate:null,startTime:"00:00",endDate:null,endTime:"00:00",repeat:!1,frequency:1,unit:"week",until:null})},remove(e){this.field.value.splice(this.field.value.indexOf(e),1)},id(){return this.field.id+"."+Object.values(arguments).join(".")},clearDate(e){e.startDate=null,e.startTime=null,e.endDate=null,e.endTime=null,this.$refs.rangePicker.reset()},getStartDate(e){var t=e.startTime||"00:00:00",t=new Date(e.startDate+" "+t);return e.startDate&&isFinite(t)?t:null},getEndDate(e){var t=e.endTime||"00:00:00",t=new Date(e.endDate+" "+t);return e.endDate&&isFinite(t)?t:null},getUntilDate(e){var t=new Date(e.until);return e.until&&isFinite(t)?t:null},format(e){return Voxel.helpers.dateTimeFormat(e)},formatDate(e){return Voxel.helpers.dateFormat(e)},getUpcoming(t=10){let o=[],d=new Date;return this.field.value.forEach(e=>{let s=this.getStartDate(e),a=this.getEndDate(e);var l=this.getUntilDate(e);let r=t;if(s&&a&&(s>=d&&(o.push({start:new Date(s),end:new Date(a)}),r--),e.repeat&&1<=e.frequency&&l>s&&l>d)){let t=e.frequency,i=e.unit;if("week"===e.unit?(i="day",t*=7):"year"===e.unit&&(i="month",t*=12),s<d){for(;s<d;)"day"===i?(s=new Date(s.setDate(s.getDate()+t)),a=new Date(a.setDate(a.getDate()+t))):"month"===i&&(s=new Date(s.setMonth(s.getMonth()+t)),a=new Date(a.setMonth(a.getMonth()+t)));o.push({start:new Date(s),end:new Date(a)}),r--}for(let e=0;e<r&&("day"===i?(s=new Date(s.setDate(s.getDate()+t)),a=new Date(a.setDate(a.getDate()+t))):"month"===i&&(s=new Date(s.setMonth(s.getMonth()+t)),a=new Date(a.setMonth(a.getMonth()+t))),!(s>l));e++)o.push({start:new Date(s),end:new Date(a)})}}),o.sort((e,t)=>e.start.getTime()-t.start.getTime()).splice(0,t)}}},V={template:"#create-post-date-field",components:{datePicker:{template:"#create-post-date-field-picker",props:["modelValue"],data(){return{picker:null}},mounted(){this.picker=new Pikaday({field:this.$refs.input,container:this.$refs.calendar,bound:!1,firstDay:1,keyboardInput:!1,defaultDate:this.modelValue?new Date(this.modelValue):null,onSelect:e=>{this.$emit("update:modelValue",Voxel.helpers.dateFormatYmd(e))},selectDayFn:e=>this.modelValue&&this.modelValue===Voxel.helpers.dateFormatYmd(e)})},unmounted(){setTimeout(()=>this.picker.destroy(),200)},watch:{modelValue(){this.picker.draw()}}}},props:{field:Object},computed:{displayDate(){var e=this.field.value.time||"00:00:00",e=new Date(this.field.value.date+" "+e);return this.field.value.date&&isFinite(e)?this.field.props.enable_timepicker?Voxel.helpers.dateTimeFormat(e):Voxel.helpers.dateFormat(e):null}}},S={template:"#create-post-select-field",props:{field:Object}};window.Voxel.conditionHandlers={"text:equals":(e,t)=>t===e.value,"text:not_equals":(e,t)=>t!==e.value,"text:is_empty":(e,t)=>!t?.trim()?.length,"text:is_not_empty":(e,t)=>!!t?.trim()?.length,"text:contains":(e,t)=>t?.match(new RegExp(e.value,"i")),"taxonomy:contains":(e,t)=>Array.isArray(t)&&t.includes(e.value),"taxonomy:empty":(e,t)=>!Array.isArray(t)||!t.length,"taxonomy:not_empty":(e,t)=>Array.isArray(t)&&t.length,"switcher:checked":(e,t)=>!!t,"switcher:unchecked":(e,t)=>!t,"number:empty":(e,t)=>isNaN(parseFloat(t)),"number:equals":(e,t)=>parseFloat(t)===parseFloat(e.value),"number:gt":(e,t)=>parseFloat(t)>parseFloat(e.value),"number:gte":(e,t)=>parseFloat(t)>=parseFloat(e.value),"number:lt":(e,t)=>parseFloat(t)<parseFloat(e.value),"number:lte":(e,t)=>parseFloat(t)<=parseFloat(e.value),"number:not_empty":(e,t)=>!isNaN(parseFloat(t)),"number:not_equals":(e,t)=>parseFloat(t)!==parseFloat(e.value),"file:empty":(e,t)=>!Array.isArray(t)||!t.length,"file:not_empty":(e,t)=>Array.isArray(t)&&t.length,"date:empty":(e,t)=>!isFinite(new Date(t.date+" "+(t.time||"00:00:00"))),"date:gt":(e,t)=>{t=new Date(t.date+" "+(t.time||"00:00:00")),e=new Date(e.value);return!(!isFinite(t)||!isFinite(e))&&e<t},"date:lt":(e,t)=>{t=new Date(t.date+" "+(t.time||"00:00:00")),e=new Date(e.value);return!(!isFinite(t)||!isFinite(e))&&t<e},"date:not_empty":(e,t)=>isFinite(new Date(t.date+" "+(t.time||"00:00:00")))},window.render_create_post=()=>{Array.from(document.querySelectorAll(".ts-create-post")).forEach(e=>{var t,i;e.__vue_app__||(i=e,(t=Vue.createApp({el:i,mixins:[Voxel.mixins.base],data(){return{activePopup:null,fields:{},steps:[],post_type:{},post:null,step_index:null,submission:{processing:!1,done:!1,viewLink:null,editLink:null,message:null}}},created(){window.CP=this;var e=JSON.parse(this.$options.el.dataset.config);this.fields=e.fields,this.steps=e.steps,this.post_type=e.post_type,this.post=e.post||null,this.setupConditions(this.fields);let t=Voxel.getSearchParam("step");e=this.steps.findIndex(e=>e===t);t&&0<e?this.setStep(e):this.setStep(0)},mounted(){i.classList.toggle("ts-ready")},methods:{setupConditions(i){Object.values(i).forEach(r=>{r.conditions&&r.conditions.forEach(e=>{e.forEach(s=>{var e=s.source.split("."),t=e[0];let a=e[1]||null,l=i[t];if(l){let e=l.value;null!==a&&(e=e?e[a]:null),this.evaluateCondition(s,e,r,l),this.$watch(()=>null!==a?l.value?l.value[a]:null:l.value,(e,t)=>{let i=l.value;null!==a&&(i=i?i[a]:null),this.evaluateCondition(s,i,r,l)},{deep:!0})}})})})},evaluateCondition(e,t,i,s){var a=Voxel.conditionHandlers[e.type];a&&(e._passes=a(e,t,i,s))},conditionsPass(e){if(!e.conditions)return!0;var i=!1;return e.conditions.forEach(e=>{var t;e.length&&(t=!0,e.forEach(e=>{e._passes||(t=!1)}),t&&(i=!0))}),i},prevStep(){0<this.step_index&&this.setStep(this.step_index-1)},nextStep(){this.step_index<this.steps.length-1&&this.setStep(this.step_index+1)},setStep(e){this.step_index=e,0<this.step_index&&this.currentStep?Voxel.setSearchParam("step",this.currentStep.key):Voxel.deleteSearchParam("step")},submit(){this.submission.processing=!0;var i=new FormData,s={},e=(Object.values(this.fields).forEach(e=>{var t=this.$refs["field:"+e.key];!e.is_ui&&this.conditionsPass(e)&&(t&&"function"==typeof t.onSubmit?t.onSubmit(s,i):null!==e.value&&(s[e.key]=e.value))}),i.append("postdata",JSON.stringify(s)),jQuery.param({action:"create_post",post_type:this.post_type.key,post_id:this.post?.id}));jQuery.post({url:Voxel_Config.ajax_url+"&"+e,data:i,contentType:!1,processData:!1}).always(e=>{this.submission.processing=!1,e.success?(this.submission.done=!0,this.submission.viewLink=e.view_link,this.submission.editLink=e.edit_link,this.submission.message=e.message):e.errors?Voxel.alert(e.errors.join("<br>"),"error"):Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})}},computed:{currentStep(){return this.fields[this.steps[this.step_index]]}}})).component("form-popup",Voxel.components.popup),t.component("form-group",Voxel.components.formGroup),t.component("media-popup",s),t.component("term-list",v),t.component("field-title",a),t.component("field-text",l),t.component("field-texteditor",r),t.component("field-description",o),t.component("field-number",d),t.component("field-email",n),t.component("field-url",u),t.component("field-file",p),t.component("field-image",p),t.component("field-profile-avatar",p),t.component("field-profile-name",l),t.component("field-taxonomy",y),t.component("field-phone",h),t.component("field-switcher",c),t.component("field-location",f),t.component("field-work-hours",m),t.component("field-product",g),t.component("field-product-calendar",x),t.component("field-product-timeslots",k),t.component("field-ui-image",_),t.component("field-ui-heading",w),t.component("field-repeater",b),t.component("field-timezone",D),t.component("field-recurring-date",$),t.component("field-date",V),t.component("field-select",S),t.mount(e))})},window.render_create_post()});