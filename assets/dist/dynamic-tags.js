!function(t){"function"==typeof define&&define.amd?define("dynamicTags",t):t()}(function(){"use strict";function i(t){var e=t.match(new RegExp(this.REG.MATCH_TAGS,"ims")).groups,i=e.group,a=this.$root.groups[i];if(!a)return null;var o=[],s=(e.modifiers&&e.modifiers.replace(new RegExp(this.REG.MATCH_MODIFIERS,"imsg"),(t,e,i)=>{var s,i=i.split(new RegExp(this.REG.SPLIT_ARGS,"ims")),r=a.methods[e];(r=r||this.$root.modifiers[e])?(s=jQuery.extend({},r.arguments,!0),Object.keys(s).forEach((t,e)=>{i[e]&&(s[t]=i[e].replace(new RegExp(DTags.REG.UNESCAPE_ARG,"imsg"),t=>t.substr(1)))}),o.push({key:r.key,label:r.label,type:r.type,accepts:r.accepts,arguments:s,id:this.unique_id()})):o.push({unknown:!0,type:"unknown",key:e,match:t,id:this.unique_id()})}),""===e.property&&(e.property=":default"),[]),r=e.property.split("."),n=r.shift(),d=a.properties[n];for(d&&(d.key=n),s.push(d),n=r.shift();n;){if(!d||!d.properties){d=null;break}(d=d.properties[n])&&(d.key=n),s.push(d),n=r.shift()}return d=d||{},s=s.filter(Boolean),{string:t,group:a,property:d,path:s,modifiers:o}}function s(t,e=0,i="html"){var s=this.parseTag(t);if(!s)return t;if("text"===i)return s.property.label;t=s.property.label;return"method"===s.modifiers[0]?.type&&(t=s.modifiers[0].label,"string"==typeof(i=Object.values(s.modifiers[0].arguments)[0])&&i.length&&(t+=": "+i)),`<span
			class="dtag ${s.unknown?"dtag-u":""}"
			contenteditable="false"
			title="${s.string}"
			data-tag="${s.string}"
			data-index="${e}"
		><span class="dtag-content">${t=void 0===t?"(unknown)":t}</span></span>`.replace(/\s+/g," ")}var r={template:"#dtags-content-editor",data(){return{mode:"visual"}},mounted(){this.$nextTick(()=>{$(this.$el).on("click",".dtag",this.editTag)})},methods:{editTag(t){var t=$(t.currentTarget),e=this.$root.parseTag(t.data("tag"));e&&(e.index=t.data("index"),this.$root.activeGroup=e.group,this.$root.activeTag=e)},onCopy(t){if(this.save(),t.target instanceof HTMLElement&&t.target.classList.contains("dtag-content"))return t.clipboardData.setData("text/plain",t.target.parentElement.dataset.tag),void t.preventDefault();var e=document.getSelection();if(e.rangeCount){var i=e.getRangeAt(0);if(i.startContainer&&i.endContainer){var s=[];if(s.push(i.startContainer),i.startContainer!==i.endContainer){for(var r=i.startContainer.nextSibling,a=0;r!==i.endContainer&&r&&(s.push(r),r=r.nextSibling,!(1e3<++a)););s.push(i.endContainer)}e=this.getEditorContent(s,i.startOffset,i.endOffset);t.clipboardData.setData("text/plain",e),t.preventDefault()}}},onPaste(t){var e=(t.clipboardData||window.clipboardData).getData("text"),i=document.getSelection();if(!i.rangeCount)return!1;if(i.baseNode.parentNode.closest(".dtag"))return!1;t.preventDefault();e=this.$root.formatAsHTML(e),i.deleteFromDocument(),t=$(`<span class="paste">${e}</span>`).get(0),i.getRangeAt(0).insertNode(t),e=document.createRange();e.setStart(t,t.childNodes.length),e.setEnd(t,t.childNodes.length),i.removeAllRanges(),i.addRange(e),$(this.$el).find(".dtag").each((t,e)=>$(e).attr("data-index",t++))},insertContent(t){var e,i;"plain"===this.mode?this.$root.content+=t:(e=document.getSelection(),t=document.createTextNode(t),e.rangeCount&&this.$refs.editor.contains(e.anchorNode)?((i=e.getRangeAt(0)).deleteContents(),i.insertNode(t),e.removeAllRanges()):this.$refs.editor.append(t),this.save())},focus(){var t=this.$refs.editor,e=t.childNodes[t.childNodes.length-1],i=document.getSelection(),s=document.createTextNode(""),r=document.createRange();e?.classList?.contains("dtag")&&t.appendChild(document.createTextNode(" ")),t.appendChild(s),r.setStart(s,0),r.setEnd(s,0),i.removeAllRanges(),i.addRange(r)},save(){this.$root.content=this.getEditorContent(this.$refs.editor.childNodes)},getEditorContent(t,e=!1,i=!1){var d="",l=!0;return function t(e,i=!1,s=!1){for(var r=e.length,a=0;a<r;a++){var o,n=e[a];n instanceof HTMLBRElement?(d+="\n",l=!0):(n instanceof HTMLDivElement&&!1===l&&(d+="\n"),n instanceof HTMLElement&&n.classList.contains("dtag")&&(d+=n.dataset.tag),l=!1,"#text"!==n.nodeName||!n.textContent||(o=n.parentElement.classList).contains("dtag-content")||o.contains("dtag")||(d+=i&&0===a?n.textContent.substr(i).trimStart():s&&a===r-1?n.textContent.substr(0,s).trimEnd():n.textContent),t(n.childNodes,!1,!1))}}(t,e,i),d}},computed:{previewClass(){return"prev-small"}}},a={template:"#dtags-edit-tag",props:{tag:Object},data(){return{activeModifier:null,showMods:!1}},methods:{saveTag(){var i=[],t=this.tag.path.map(t=>t.key).join("."),e=(":default"===this.tag.property.key&&(t=t.slice(0,-9)),i.push(`@${this.tag.group.key}(${t})`),this.tag.modifiers.forEach(t=>{var e;t.unknown?i.push(t.match):(e=Object.values(t.arguments).map(t=>t?t.replace(new RegExp(this.$root.REG.ESCAPE_ARG,"imsg"),t=>"\\"+t):""),i.push(`.${t.key}(${e.join(",")})`))}),i.join("")),s=-1;this.$root.content=this.$root.content.replace(new RegExp(this.$root.REG.MATCH_TAGS,"imsg"),t=>(s++,this.tag.index===s?e:t)),this.$root.activeTag=null},deleteTag(){var e=-1;this.$root.content=this.$root.content.replace(new RegExp(this.$root.REG.MATCH_TAGS,"imsg"),t=>(e++,this.tag.index===e?"":t)),this.$root.activeTag=null},useModifier(t){this.tag.modifiers.push(jQuery.extend(!0,{},t,{id:this.$root.unique_id()})),this.showMods=!1},useCondition(t){this.tag.modifiers.push(jQuery.extend(!0,{},t,{id:this.$root.unique_id()})),this.tag.modifiers.push(jQuery.extend(!0,{},this.$root.modifiers.then,{id:this.$root.unique_id()})),this.tag.modifiers.push(jQuery.extend(!0,{},this.$root.modifiers.else,{id:this.$root.unique_id()}))},onDragStart(){this.$refs["mods-container"].classList.add("drag-active")},onDragEnd(){this.$refs["mods-container"].classList.remove("drag-active")}},computed:{pathText(){return[this.tag.group.title].concat(this.tag.path.filter(t=>":default"!==t.key).map(t=>t.label)).join(" > ")},modGroups(){var t=this.tag.property.type,e={},i={string:"Text modifiers",number:"Number modifiers",date:"Date modifiers",url:"URL modifiers",email:"Email modifiers",any:"General"},s=(Object.values(this.$root.modifiers).forEach(t=>{"modifier"===t.type&&(e[t.accepts]||(e[t.accepts]={type:t.accepts,label:i[t.accepts]||t.accepts,modifiers:[]}),e[t.accepts].modifiers.push(t))}),[]),r=e[t];return r&&(s.push(r),e[t]=void 0),s.concat(Object.values(e).filter(Boolean))}}},o={template:"#dtags-property-list",props:{properties:Object,path:{type:Array,default:[]}},emits:["select"],data(){return{depth:this.path.length-1,activeStack:null}},methods:{selectProperty(t,e){var i=this.path[0],e=this.path.slice(1).concat(e).join(".");this.$emit("select",i+`(${e})`)},propertyClick(t,e){"object"===t.type?this.activeStack=this.activeStack===t?null:t:this.selectProperty(t,e)}}},n={template:"#dtags-modifier",props:["modifier","index","editor","tag"],methods:{toggleActive(){this.editor.activeModifier=this.modifier===this.editor.activeModifier?null:this.modifier},deleteModifier(){this.editor.tag.modifiers=this.editor.tag.modifiers.filter(t=>t!==this.modifier)}},computed:{getLabel(){return this.modifier.unknown?this.modifier.key:this.modifier.label},getTypeLabel(){return this.modifier.unknown?"Unknown modifier":"method"===this.modifier.type?"Method":"control-structure"===this.modifier.type?"then"===this.modifier.key?"Then block":"else"===this.modifier.key?"Else block":"Conditional":"Modifier"}}},d={template:"#dtags-visibility-editor",data(){return{activePopup:null,sourceTarget:null,conditions:[[{type:null}]]}},methods:{removeCondition(t,e,i){e.splice(t,1),0===e.length&&1<this.conditions.length&&this.conditions.splice(i,1)},setProps(e){var i=this.$root.rules[e.type];i&&(jQuery.extend(e,i.props),Object.keys(e).forEach(t=>{"type"!==t&&void 0===i.props[t]&&delete e[t]}))}}},l={template:"#dtags-data-sources",emits:["select"],data(){return{activeGroup:this.$root.groups.post,search:""}},methods:{searchProperties(){let t={};return Object.values(this.$root.groups).forEach(e=>{let i={};Object.keys(e.properties).forEach(t=>{-1===t.indexOf(this.search)&&-1===e.properties[t].label.indexOf(this.search)||(i[t]=e.properties[t])}),Object.keys(i).length&&(t[e.key]=i)}),t},useMethod(t){this.$emit("select",`@${this.activeGroup.key}().${t.key}()`)}}};jQuery(t=>{var e;document.getElementById("dtags-container"),document.getElementById("dtags-container")&&(window.$=jQuery,(e=Vue.createApp({template:"#dtags-template",data(){return{mode:"default",groups:Dynamic_Tag_Groups,modifiers:Dynamic_Tag_Modifiers,rules:Dynamic_Tag_Rules,visible:!1,activeGroup:null,activeTag:null,content:"",search:"",REG:{MATCH_TAGS:/\@(?<group>[a-zA-Z0-9_]+)\((?<property>.*?(?<!\\))\)(?<modifiers>(?:\.[a-zA-Z0-9_]+(?:\(.*?(?<!\\)\)))+)?/,MATCH_MODIFIERS:/\.(?<mod>(?:[a-zA-Z0-9_])+)(?:\((?<args>.*?(?<!\\))\))/,SPLIT_ARGS:/(?<!\\),/,ESCAPE_ARG:/(?<!\\)([,)])/,UNESCAPE_ARG:/(?<!\\)\\([,)])/,MATCH_DYNAMIC_STRING:/@tags()(?<dynamic_string>.*?)@endtags()/}}},created(){(window.DTags=this).activeGroup=this._defaultGroup()},methods:{showAvailableFields(){this.activeTag=null,this.activeGroup=this._defaultGroup()},_defaultGroup(){return this.groups[Object.keys(this.groups)[0]]},unique_id(){return"id-"+Math.random().toString(10).substr(2,10)},edit(t,e){this.mode="default",this.content=t,this.visible=!0;var i=()=>{e(this.content),this.emitter.off("save",i)};this.emitter.on("save",i),setTimeout(()=>this.$refs.contentEditor.focus(),10)},save(){this.visible=!1,this.activeTag=null,this.activeGroup=this._defaultGroup(),this.content=this.content.trim(),this.emitter.emit("save"),this.content=""},editVisibility(i,s){this.mode="visibility",this.visible=!0,this.$nextTick(()=>{let t=this.$refs.visibilityEditor;t.conditions=Array.isArray(i)?i:[[{type:null}]];var e=()=>{t.conditions=t.conditions.map(t=>Array.isArray(t)&&(t=t.filter(t=>t&&"string"==typeof t.type&&t.type.length)).length?t:null).filter(Boolean),s(JSON.parse(JSON.stringify(t.conditions))),this.emitter.off("save",e)};this.emitter.on("save",e)})},discard(){this.visible=!1,this.activeTag=null,this.activeGroup=this._defaultGroup(),this.content=""},formatAsHTML(t){var e=0;return t.replace(new RegExp(this.REG.MATCH_TAGS,"imsg"),t=>this.displayTag(t,e++)).replace(new RegExp("\r?\n","g"),"<br>")},formatAsText(t){var e=0;return t.replace(new RegExp(this.REG.MATCH_TAGS,"imsg"),t=>this.displayTag(t,e++,"text"))},isDynamicString(t){return(t=""+t).match(new RegExp(this.REG.MATCH_DYNAMIC_STRING,"imsg"))},getDynamicString(t){return(t=""+t).replace("@tags()","").replace("@endtags()","")},formatRulesAsHTML(t){Array.isArray(t)||(t=[]);let e=[];return t.forEach(t=>{Array.isArray(t)||(t=[]);let i=[];t.forEach(e=>{if(this.rules[e?.type]){let t=[];if("dtag"===e.type){if(!(e.tag&&e.compare&&this.modifiers[e.compare]))return;t.push(this.displayTag(e.tag,0,"text")),t.push(this.modifiers[e.compare].label),e.arguments&&Object.values(e.arguments).filter(Boolean).length&&t.push(Object.values(e.arguments).filter(Boolean).join(", "))}else t.push(this.rules[e.type].label),e.value&&t.push(e.value);i.push("<p>"+t.join(" ")+"</p>")}}),i.length&&e.push('<div class="rule-group">'+i.join("")+"</div>")}),e.length?e.join('<p class="rule-divider">or</p>'):`<span class="elementor-control-field-description">
							No visibility rules added yet.
						</span>`},_clone(t){return jQuery.extend(!0,{},t)},parseTag:i,displayTag:s}})).config.globalProperties.emitter=Voxel_Backend.mitt(),e.component("content-editor",r),e.component("edit-tag",a),e.component("property-list",o),e.component("modifier",n),e.component("visibility-editor",d),e.component("data-sources",l),e.component("draggable",vuedraggable),e.mount("#dtags-container"),window._DTags=e)})});