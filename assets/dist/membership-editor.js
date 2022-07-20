!function(e){"function"==typeof define&&define.amd?define("membershipEditor",e):e()}(function(){"use strict";var t={template:"#membership-edit-plan",data(){return{tab:"general",plan:this.$root.activePlan,submissionValue:"",loading:!1,mode:"live",createPrice:{live:null,test:null},priceDefaults:{amount:null,currency:null,type:"recurring",interval:"month",intervalCount:1}}},created(){this.createPrice.live=jQuery.extend(!0,{},this.priceDefaults),this.createPrice.test=jQuery.extend(!0,{},this.priceDefaults)},methods:{addSubmission(){this.$root.postTypes[this.submissionValue]&&(this.plan.submissions[this.submissionValue]=1)},save(){this.loading=!0;var e=Voxel_Config.ajax_url+"&action=membership.update_plan";jQuery.post(e,{plan:this.plan},e=>{this.loading=!1,e.success||alert("Error:\n - "+e.errors.join("\n - "))})},archivePlan(){var e;(this.plan.archived||confirm("This plan will no longer be available to new users. Do you want to continue?"))&&(this.loading=!0,e=Voxel_Config.ajax_url+"&action=membership.archive_plan",jQuery.post(e,{plan:this.plan},e=>{this.loading=!1,e.success?this.plan.archived=!this.plan.archived:alert("Error:\n - "+e.errors.join("\n - "))}))},insertPrice(){this.loading=!0;var e=Voxel_Config.ajax_url+"&action=membership.create_price";jQuery.post(e,{plan:this.plan.key,mode:this.mode,price:this.createPrice[this.mode]},e=>{this.loading=!1,e.success?(this.$root.activePlan.pricing[this.mode]=e.pricing[this.mode],this.createPrice[this.mode]=jQuery.extend(!0,{},this.priceDefaults)):alert("Error:\n - "+e.errors.join("\n - "))})},stripeProductUrl(){return"live"===this.mode?"https://dashboard.stripe.com/products/"+this.plan.pricing.live.product_id:"https://dashboard.stripe.com/test/products/"+this.plan.pricing.test.product_id},syncPrices(){this.loading=!0;var e=Voxel_Config.ajax_url+"&action=membership.sync_prices";jQuery.get(e,{plan:this.plan.key,mode:this.mode},e=>{this.loading=!1,e.success?this.$root.activePlan.pricing[this.mode]=e.pricing[this.mode]:alert("Error:\n - "+e.errors.join("\n - "))})},togglePrice(e){this.loading=!0;var i=Voxel_Config.ajax_url+"&action=membership.toggle_price";jQuery.get(i,{plan:this.plan.key,mode:this.mode,price:e},e=>{this.loading=!1,e.success?this.$root.activePlan.pricing[this.mode]=e.pricing[this.mode]:alert("Error:\n - "+e.errors.join("\n - "))})}}};jQuery(e=>{var i=document.getElementById("vx-membership-settings");i&&(window.$=jQuery,(i=Vue.createApp({el:i,data(){return{plans:[],postTypes:[],plan:{key:"",label:"",description:""},activePlan:null,showArchive:!1}},created(){this.config=jQuery.extend(!0,this.config,JSON.parse(this.$options.el.dataset.config)),this.plans=this.config.plans,this.postTypes=this.config.postTypes},computed:{archivedPlans(){return this.plans.filter(e=>e.archived)}}})).component("field-key",Voxel_Backend.components.Field_Key),i.component("edit-plan",t),i.mount("#vx-membership-settings"))})});