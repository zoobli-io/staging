<?php
/**
 * Auth widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="ts-auth hidden" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<div v-if="screen === 'login'" class="ts-form ts-login">
		<form @submit.prevent="submitLogin">
			<div class="ts-login-head">
				<h1><?php echo $this->get_settings_for_display( 'auth_title' ); ?></h1>
			</div>

			<?php if ( \Voxel\get( 'settings.auth.google.enabled' ) ): ?>
				<div class="login-section">
					<div class="ts-form-group">
						<label>Connect with social media</label>
					</div>
					<div class="ts-form-group ts-social-connect">
						<a href="<?= esc_url( \Voxel\get_google_auth_link() ) ?>" class="ts-btn ts-google-btn ts-btn-large ts-google-btn">
							<?php \Voxel\render_icon( $this->get_settings('auth_google_ico') ); ?>
							Sign in with Google
						</a>
					</div>
				</div>
			<?php endif ?>

			<div class="login-section">
				<div class="ts-form-group">
					<label>Enter your details</label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_user_ico') ); ?>
						<input type="text" v-model="login.username" placeholder="Username" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group ts-password-field">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
						<input type="password" v-model="login.password" ref="loginPassword" placeholder="Password" class="autofocus">
					</div>
					
				</div>
				<!-- <div class="ts-form-group">
					<div class="ts-checkbox-container">
						<label class="container-checkbox">
							<input type="checkbox" v-model="login.remember">
							<span class="checkmark"></span>
							<p>Remember me</p>
						</label>
					</div>
				</div> -->
				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?php \Voxel\render_icon( $this->get_settings('auth_user_ico') ); ?>
						Login
					</button>
				</div>
				
			</div>
			<div class="login-section">
				<div v-if="config.register_enabled" class="ts-form-group">
					<label>Don't have an account? <a href="#" @click.prevent="screen = 'register'">Sign up</a></label>
				</div>
				<div class="ts-form-group">
					<label>Forgot password? <a href="#" @click.prevent="screen = 'recover'">Recover account</a></label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover'" class="ts-form ts-login">
		<form @submit.prevent="submitRecover">
			<div class="ts-form-group">
				<label>Reset your password</label>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
					<input type="email" v-model="recovery.email" placeholder="Your account email" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					Reset password
				</button>
			</div>
			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'login'" class="ts-btn ts-btn-1 ts-btn-large">
					Go back
				</a>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover_confirm'" class="ts-form ts-login">
		<form @submit.prevent="submitRecoverConfirm">
			<div class="login-section">
				<div class="ts-form-group">
					<label>Password recovery</label>
					<small>Please type the recovery code which was sent to your email</small>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
						<input type="text" v-model="recovery.code" placeholder="Confirmation code" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						Submit
					</button>
				</div>
			
				<div class="ts-form-group">
					<label>Didn't receive anything? <a href="#" @click.prevent="recovery.code = null; screen = 'recover';">Send again</a></label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'recover_set_password'" class="ts-form ts-login">
		<form @submit.prevent="submitNewPassword">
			<div class="ts-form-group">
				<label>Choose your new password</label>
				<small>Password must contain at least 8 characters.</small>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
					<input type="password" v-model="recovery.password" placeholder="Your new password" class="autofocus">
				</div>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
					<input type="password" v-model="recovery.confirm_password" placeholder="Confirm password" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					Save changes
				</button>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'register'" class="ts-form ts-login">
		<form @submit.prevent="submitRegister">
			<div class="ts-login-head">
				<h1>Create an account</h1>
			</div>

			<?php if ( \Voxel\get( 'settings.auth.google.enabled' ) ): ?>
				<div class="login-section">
					<div class="ts-form-group">
						<label>Connect with social media</label>
					</div>
					<div class="ts-form-group ts-social-connect">
						<a href="<?= esc_url( \Voxel\get_google_auth_link() ) ?>" class="ts-btn  ts-google-btn ts-btn-large">
							<?php \Voxel\render_icon( $this->get_settings('auth_google_ico') ); ?>
							Sign in with Google
						</a>
					</div>
				</div>
			<?php endif ?>

			<div class="login-section">
				<div class="ts-form-group login-form-heading">
					<label>Enter your details</label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_user_ico') ); ?>
						<input type="text" v-model="register.username" placeholder="Username" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
						<input type="email" v-model="register.email" placeholder="Email address" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
						<input type="password" v-model="register.password" placeholder="Password" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-checkbox-container terms-checkbox">
						<label class="container-checkbox">
							<input type="checkbox" v-model="login.remember">
							<span class="checkmark"></span>
							<p>I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
						</label>
					</div>
				</div>
				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						<?php \Voxel\render_icon( $this->get_settings('auth_user_ico') ); ?>
						Sign up
					</button>
				</div>
			</div>
			<div class="login-section">
				<div class="ts-form-group">
					<label>Have an account already? <a href="#" @click.prevent="screen = 'login'">
						Login instead
					</a></label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'confirm_account' || screen === 'login_confirm_account'" class="ts-form ts-login">
		<form @submit.prevent="submitConfirmAccount( screen === 'login_confirm_account' ? 'login' : 'register' )">
			<div class="ts-form-group">
				<label>
					Confirm your email
					<small>Please type the confirmation code which was sent to your email</small>
				</label>
			</div>
			<div class="ts-form-group">
				<div class="ts-input-icon flexify">
					<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
					<input type="text" v-model="confirmation_code" placeholder="Confirmation code" class="autofocus">
				</div>
			</div>

			<div class="ts-form-group">
				<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
					Submit
				</button>
			</div>

			<div class="login-section">
				<div class="ts-form-group">
					<label>
						Didn't receive code?
						<a
							href="#"
							@click.prevent="resendConfirmationCode( screen === 'login_confirm_account' ? 'login' : 'register' )"
							:class="{'vx-pending': resendCodePending}"
						>Resend email</a>
					</label>
				</div>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'security'" class="ts-form ts-login">
		<div class="ts-login-head">
			<h1>Account security</h1>
		</div>

		<div class="login-section">
			<div class="ts-form-group">
				<a href="<?= esc_url( home_url('/') ) ?>" @click.prevent="screen = 'security_update_email'" class="ts-btn ts-btn-1 ts-btn-large">
					<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
					Update email address
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( home_url('/') ) ?>" @click.prevent="screen = 'security_update_password'" class="ts-btn ts-btn-1 ts-btn-large">
					<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
					Update password
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( \Voxel\get_logout_url() ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
					<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>Logout
				</a>
			</div>
		</div>
	</div>

	<div v-else-if="screen === 'security_update_password'" class="ts-form ts-login">
		<form @submit.prevent="submitUpdatePassword">
			<template v-if="update.password.successful">
				<div class="ts-form-group">
					<label><?= _x( 'Your password has been updated.', 'account security', 'voxel' ) ?></label>
				</div>
			</template>
			<template v-else>
				<div class="ts-form-group">
					<label>Enter your current password</label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
						<input type="password" v-model="update.password.current" placeholder="Current password" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<label>Choose new password</label>
					<small>Password must contain at least 8 characters.</small>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
						<input type="password" v-model="update.password.new" placeholder="Your new password" class="autofocus">
					</div>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_pass_ico') ); ?>
						<input type="password" v-model="update.password.confirm_new" placeholder="Confirm password" class="autofocus">
					</div>
				</div>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						Update password
					</button>
				</div>
			</template>

			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'security'" class="ts-btn ts-btn-1 ts-btn-large">
					Go back
				</a>
			</div>
		</form>
	</div>

	<div v-else-if="screen === 'security_update_email'" class="ts-form ts-login">
		<form @submit.prevent="submitUpdateEmail">
			<template v-if="update.email.state === 'confirmed'">
				<div class="ts-form-group">
					<label><?= _x( 'Your email address has been updated.', 'account security', 'voxel' ) ?></label>
				</div>
			</template>
			<template v-else>
				<div class="ts-form-group">
					<label>Your current email address</label>
				</div>
				<?php if ( is_user_logged_in() ): ?>
					<div class="ts-form-group">
						<div class="ts-input-icon flexify">
							<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
							<input type="email" disabled value="<?= esc_attr( \Voxel\current_user()->get_email() ) ?>" class="autofocus">
						</div>
					</div>
				<?php endif ?>
				<div class="ts-form-group">
					<label>Enter new email address</label>
				</div>
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
						<input type="email" v-model="update.email.new" placeholder="Enter email address" class="autofocus" :disabled="update.email.state !== 'send_code'">
					</div>
				</div>

				<template v-if="update.email.state === 'verify_code'">
					<div class="ts-form-group">
						<label>Confirmation code</label>
						<small>Please type the confirmation code which sent to your new email</small>
					</div>
					<div class="ts-form-group">
						<div class="ts-input-icon flexify">
							<?php \Voxel\render_icon( $this->get_settings('auth_email_ico') ); ?>
							<input type="text" v-model="update.email.code" placeholder="Confirmation code" class="autofocus">
						</div>
					</div>
				</template>

				<div class="ts-form-group">
					<button type="submit" class="ts-btn ts-btn-2 ts-btn-large" :class="{'vx-pending': pending}">
						{{ update.email.state === 'send_code' ? 'Send confirmation code' : 'Update email address' }}
					</button>
				</div>
			</template>

			<div class="ts-form-group">
				<a href="#" @click.prevent="screen = 'security'" class="ts-btn ts-btn-1 ts-btn-large">
					Go back
				</a>
			</div>
		</form>
	</div>


	<div v-else-if="screen === 'welcome'" class="ts-form ts-login ts-welcome">
		<div class="login-section">
			<div class="ts-welcome-message ts-form-group">
				<?php \Voxel\render_icon( $this->get_settings('auth_welcome_ico') ); ?>
				<h2>Welcome {{ config.userDisplayName }}!</h2>
				<label>Complete your profile or skip for now</label>
			</div>
		</div>
		<div class="login-section">
			<div class="ts-form-group">
				<a :href="config.editProfileUrl" class="ts-btn ts-btn-2 ts-btn-large">
					<?php \Voxel\render_icon( $this->get_settings('auth_user_ico') ); ?>
					Complete profile 
				</a>
			</div>
			<div class="ts-form-group">
				<a href="<?= esc_url( $config['redirectUrl'] ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
					Do it later
				</a>
			</div>
		</div>
	</div>
</div>
