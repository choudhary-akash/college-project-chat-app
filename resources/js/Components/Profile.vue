<template>
	<div class="profile">
		<h3>Profile</h3>
		<div class="text-center">
			<img :src="profile.image" class="profile-image" alt="" />
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 mb-3">
				<label class="form-label" for="first_name">
					First Name
				</label>
				<input class="form-control bg-transparent text-white" type="text" id="first_name"
					required :value="profile.first_name" />
			</div>
			<div class="col-12 col-sm-6 mb-3">
				<label class="form-label" for="last_name">
					Last Name
				</label>
				<input class="form-control bg-transparent text-white" type="text" id="last_name"
					required :value="profile.last_name" />
			</div>
		</div>
		<div v-if="profile.about" class="mb-3">
			<label class="form-label" for="about">
				About
			</label>
			<input class="form-control bg-transparent text-white" type="text" id="about"
				required :value="profile.about" />
		</div>
		<div class="mb-3">
			<label class="form-label" for="mobile">
				Mobile Number
			</label>
			<input class="form-control bg-transparent text-white" type="text" id="mobile"
				required :value="profile.mobile" />
		</div>
	</div>
</template>

<script setup>
import { reactive } from 'vue';

let props = defineProps({
	profile: Object
});
</script>

<style>
.profile {
	padding: 0 1rem;
}

.profile input {
	border: 0;
}

.profile-image {
	border-radius: 100%;
	margin-bottom: 2rem;
}

.profile label {
	color: rgba(255, 255, 255, 0.6);
}
</style>