<script type="text/html" id="orders-display-note">
	<template v-if="note.type === 'comment'">
		<div class="ts-status-head flexify">
			<a :href="note.author.link" v-html="note.author.avatar"></a>
			<div>
				<a href="#">{{ note.author.name }}</a>
				<span>posted a comment</span>
				<span class="ts-status-time">{{ note.time }}</span>
			</div>
		</div>
		<div class="ts-status-body">
			<p v-if="note.message">{{ note.message }}</p>
			<div v-if="note.files" class="ts-status-attachments">
				<ul class="simplify-ul">
					<li v-for="file in note.files">
						<a :href="file.url" target="_blank">
							<i class="las la-cloud-upload-alt"></i>
							<span>{{ file.name }}</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</template>
	<template v-else>
		<div class="ts-status-head flexify system-note">
			<div class="ts-system-ico" v-html="note.icon"></div>
			<div>
				<p>{{ note.message }}</p>
				<span class="ts-status-time">{{ note.time }}</span>
			</div>
		</div>
	</template>
</script>
