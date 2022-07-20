!function(e){"function"==typeof define&&define.amd?define("auth",e):e()}(function(){"use strict";window.render_auth=()=>{Array.from(document.querySelectorAll(".ts-auth")).forEach(e=>{var r;e.__vue_app__||(r=e,Vue.createApp({el:r,mixins:[Voxel.mixins.base],data(){return{pending:!1,resendCodePending:!1,screen:null,config:null,login:{username:null,password:null,remember:!1},recovery:{email:null,code:null,password:null,confirm_password:null},register:{username:null,email:null,password:null},update:{password:{current:null,new:null,confirm_new:null,successful:!1},email:{new:null,code:null,state:"send_code"}},confirmation_code:null}},created(){this.config=JSON.parse(this.$options.el.dataset.config),this.screen=this.config.screen,r.classList.remove("hidden")},methods:{submitLogin(){this.recaptcha("vx_login",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.login",data:{username:this.login.username,password:this.login.password,remember:this.login.remember,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?e.confirmed?window.location.replace(this.config.redirectUrl):this.screen="login_confirm_account":Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitRecover(){this.recaptcha("vx_recover",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.recover",data:{email:this.recovery.email,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?this.screen="recover_confirm":Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitRecoverConfirm(){this.recaptcha("vx_recover_confirm",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.recover_confirm",data:{email:this.recovery.email,code:this.recovery.code,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?this.screen="recover_set_password":Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitNewPassword(){this.recaptcha("vx_recover_set_password",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.recover_set_password",data:{email:this.recovery.email,code:this.recovery.code,password:this.recovery.password,confirm_password:this.recovery.confirm_password,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?(this.screen="login",this.login.username=this.recovery.email,this.recovery.email=null,this.recovery.code=null,this.recovery.password=null,this.recovery.confirm_password=null,this.$refs.loginPassword?.focus()):Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitRegister(){this.recaptcha("vx_register",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.register",data:{username:this.register.username,email:this.register.email,password:this.register.password,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?this.screen="confirm_account":Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitConfirmAccount(n){this.recaptcha("vx_confirm_account",e=>{var r=("login"===n?this.login:this.register).username,s=("login"===n?this.login:this.register).password,a="login"===n&&this.login.remember;this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.confirm_account",data:{username:r,password:s,remember:a,code:this.confirmation_code,redirect_to:this.config.redirectUrl,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?"{REDIRECT_URL}"===e.redirect_to?window.location.replace(this.config.redirectUrl):window.location.replace(e.redirect_to.replace("{REDIRECT_URL}",encodeURIComponent(this.config.redirectUrl))):Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},resendConfirmationCode(s){this.recaptcha("vx_resend_confirmation_code",e=>{var r=("login"===s?this.login:this.register).username;this.resendCodePending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.resend_confirmation_code",data:{username:r,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.resendCodePending=!1,e.success?Voxel.alert(e.message,"info"):Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitUpdatePassword(){this.recaptcha("vx_update_password",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.update_password",data:{current:this.update.password.current,new:this.update.password.new,confirm_new:this.update.password.confirm_new,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?this.update.password.successful=!0:Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},submitUpdateEmail(){this.recaptcha("vx_update_email",e=>{this.pending=!0,jQuery.post({url:Voxel_Config.ajax_url+"&action=auth.update_email",data:{new:this.update.email.new,code:this.update.email.code,state:this.update.email.state,_wpnonce:this.config.nonce,_recaptcha:e}}).always(e=>{this.pending=!1,e.success?this.update.email.state=e.state:Voxel.alert(e.message||Voxel_Config.l10n.ajaxError,"error")})})},recaptcha(e,r){this.config.recaptcha.enabled?grecaptcha.ready(()=>{grecaptcha.execute(this.config.recaptcha.key,{action:e}).then(e=>r(e))}):r(null)}}}).mount(e))})},window.render_auth()});
