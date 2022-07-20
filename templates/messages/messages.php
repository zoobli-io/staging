<li class="ts-popup-component elementor-repeater-item-<?= $component['_id'] ?>">
	<a ref="target" href="#">
		<div class="ts-comp-icon flexify">
			<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</div>
		<p class="ts_comp_label" ><?= $component['messages_title'] ?></p>
	</a>
	<popup v-cloak>
		<template v-if="screen !== 'conversation'">
			<div class="ts-popup-head flexify">
				<div class="ts-popup-name flexify">
					<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<p><?= $component['messages_title'] ?></p>
				</div>

				<ul class="flexify simplify-ul">
					<li class="flexify">
						<a href="#" class="ts-icon-btn">
							<i  aria-hidden="true" class="lar la-trash-alt"></i>
						</a>
					</li>
					<li class="flexify ts-popup-close">
						<a  @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
							<i  aria-hidden="true" class="las la-times"></i>	
						</a>
					</li>
				</ul>
			</div>
			<!-- <div class="ts-form-group">
			   <div class="ts-input-icon flexify">
			   		<i aria-hidden="true" class="las la-search"></i>
			   		<input type="text" placeholder="Search messages" class="autofocus">
			   </div>
			</div> -->
			<div class="ts-form-group min-scroll ts-list-container">
				<div class="ts-empty-user-tab">
					<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<p>Not available on this build</p>
				</div>
				<!-- <ul class="ts-notification-list simplify-ul ts-message-notifications">
					<li class="ts-new-notification">
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="#" @click.prevent="screen = 'conversation'">
							<div class="convo-avatar">
								<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
							</div>
							<div class="notification-details">
								<p>Albion Selimaj</p>
								<div>
									<span>What's going on</span>
									
									<span>2 hours ago</span>
								</div>
							</div>
						</a>
					</li>
				</ul> -->
			</div>
		</template>
		<template v-else>
			<div class="ts-popup-head flexify">
				<div class="ts-popup-name flexify">
					<img src="http://2.gravatar.com/avatar/865e321fb511a51a276724da75a29e3a?s=192&d=https%3A%2F%2Fui-avatars.com%2Fapi%2Fadminadmin%2F96%2Ff5cd79%2Ffff%2F1%2F0.4%2F%2F1%2F1&r=g" alt="Avatar">
					<p>Arian</p>
				</div>

				<ul class="flexify simplify-ul">
					<li class="flexify">
						<a href="#" @click.prevent="screen = 'inbox'" class="ts-icon-btn">
							<i  aria-hidden="true" class="las la-angle-left"></i>
						</a>
					</li>
					<li class="flexify">
						<a href="#" class="ts-icon-btn">
							<i  aria-hidden="true" class="lar la-trash-alt"></i>
						</a>
					</li>
					
					<li class="flexify ts-popup-close">
						<a  @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
							<i  aria-hidden="true" class="las la-times"></i>
						</a>
					</li>
				</ul>
			</div>
			<div class="ts-view-conversation user-bar-content ts-tab-content" id="test2">
				<div class="ts-conversation-body min-scroll">
					<ul class="ts-message-list simplify-ul">
						<li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						</li><li class="ts-responder-1">

							<p>What's up dog?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						<li class="ts-responder-2">
							<p>Nothing much, what's up with you?</p>
							<span>November 22, 2020, 07:57</span>
						</li>
						
					</ul>
				</div>
				<div class="ts-conversation-footer ts-form">
					<form>
					 	<div class="flexify ts-convo-form">
					 		<input placeholder="Write message"></input>
					 		<ul class="flexify simplify-ul ts-compose-buttons">
					 			<li class="flexify">
					 				<a href="#" class="ts-icon-btn"><?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_attach_icon'), [ 'aria-hidden' => 'true' ] ); ?></a>
					 			</li>
 								<li class="flexify">
 									<a href="#" class="ts-icon-btn ts-send-btn"><?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_send_icon'), [ 'aria-hidden' => 'true' ] ); ?></a>
 								</li>
 							</ul>
						</div>
					</form>
				</div>
			</div>
		</template>
	</popup>
</li>