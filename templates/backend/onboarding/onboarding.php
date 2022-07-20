<div id="voxel-onboarding" v-cloak class="ts-container ts-theme-options" data-config="<?= esc_attr( wp_json_encode( [
	'license' => \Voxel\get( 'license', [] ),
	'tab' => $_GET['tab'] ?? 'welcome',
] ) ) ?>">
	<div class="inner-tab ts-row wrap-row all-center">
		<div v-if="tab === 'welcome'" class="ts-col-1-1">
			<div class="ts-tab-heading onboard-step">
				<img src="<?php echo esc_url( \Voxel\get_image('post-types/welcome.jpg') ) ?>" alt="" >
				<div class="ts-row all-center">
					<div class="ts-col-1-2 step-content">
						<h1>Welcome to Voxel Beta</h1>
						<p></p>
						<button @click.prevent="setTab('prepare')" class="ts-button full-width btn-shadow">Start setup</button>
					</div>
				</div>
			</div>
		</div>

		<div v-if="tab === 'prepare'" class="ts-col-1-3 ts-row wrap-row">
			<div class="ts-tab-heading ts-col-1-1">
				<h1>Getting started</h1>
			</div>

			<div class="ts-form-group ts-col-1-1">
				<ul>
					<li><h3><strong>Server requirements</strong></h3></li>
					<li>PHP 7.3 or higher</li>
					<li>MySQL 8</li>
					<li>If your server does not support MySQL 8, then you can use MariaDB 10.3 or higher</li>
					<li>64MB of memory limit</li>
				</ul>
			</div>

			<div v-if="prepare.running" class="ts-form-group ts-col-1-1">
				<div class="ts-button ts-faded full-width">Preparing...</div>
			</div>
			<div v-else class="ts-form-group ts-col-1-1">
				<button @click.prevent="prepare_install" class="ts-button full-width">Continue</button>
			</div>
		</div>

		<div v-if="tab === 'license'" class="ts-col-1-3 ts-row wrap-row">
			<div class="ts-tab-heading ts-col-1-1">
				<h1>Verify license</h1>
			</div>

			<div class="ts-form-group ts-col-1-1">
				<label>License key</label>
				<input v-model="license.license_key" type="text">
			</div>

			<div class="ts-form-group ts-col-1-1">
				<label>Environment</label>
				<select v-model="license.environment">
					<option value="production">Production</option>
					<option value="staging">Staging/Development</option>
				</select>
			</div>

			<div class="ts-form-group ts-col-1-1">
				<button @click.prevent="verify_license" class="ts-button full-width" :class="{'vx-disabled':pending}">Verify</button>
			</div>
		</div>

		<div v-if="tab === 'demo-import'" class="ts-col-1-3 ts-row wrap-row">
			<div class="ts-tab-heading ts-col-1-1">
				<h1>Import demo</h1>
			</div>

			<div class="ts-form-group ts-col-1-1">
				<label>Demo</label>
				<select>
					<option value="stays" selected>Stays demo</option>
				</select>
			</div>

			<div v-if="demo_import.running" class="ts-form-group ts-col-1-1">
				<div class="ts-button ts-faded full-width">{{ demo_import.message }}</div>
			</div>
			<div v-else class="ts-form-group ts-col-1-1">
				<button @click.prevent="run_import" class="ts-button full-width">Import demo</button>
				<p class="text-right"><a href="#" @click.prevent="start_blank">Or start from a blank site</a></p>
			</div>
		</div>
		<div v-if="tab === 'done'" class="ts-col-1-3 ts-row wrap-row">
			<div class="ts-tab-heading ts-col-1-1">
				<h1>All set!</h1>
			</div>
			<div class="ts-form-group ts-col-1-1">
				<a href="<?= esc_url( home_url('/') ) ?>" class="ts-button ts-faded full-width">Homepage</a>
			</div>
			<div class="ts-form-group ts-col-1-1">
				<a href="<?= esc_url( admin_url('/') ) ?>" class="ts-button ts-faded full-width">Dashboard</a>
			</div>
		</div>
	</div>
</div>
