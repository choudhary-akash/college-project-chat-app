<template>
	<div class="profile">
		<h3>Your Profile</h3>
		<form method="POST" @submit.prevent="updateProfile">
			<div class="row">
				<!-- Image -->
				<div class="text-center">
					<label for="profile-image-input">
						<img :src="profile.image" class="profile-image" alt="" />
					</label>
					<input type="file" id="profile-image-input" accept=".jpg,.jpeg,.png" class="d-none"
						@change="handleFileInput" />
				</div>
				<!-- First Name -->
				<div class="col-12 col-sm-6 mb-3">
					<label class="form-label" for="first_name">
						First Name
					</label>
					<input class="form-control bg-dark text-white" type="text" id="first_name" placeholder="John"
						required v-model="profile.first_name" />

					<div v-if="form.errors.first_name" class="text-danger small">
						{{ form.errors.first_name }}
					</div>
				</div>
				<!-- Last Name -->
				<div class="col-12 col-sm-6 mb-3">
					<label class="form-label" for="last_name">
						Last Name
					</label>
					<input class="form-control bg-dark text-white" type="text" id="last_name" placeholder="Doe" required
						v-model="profile.last_name" />

					<div v-if="form.errors.last_name" class="text-danger small">
						{{ form.errors.last_name }}
					</div>
				</div>
			</div>
			<!-- About -->
			<div class="mb-3">
				<label class="form-label" for="about">
					About Me
				</label>
				<textarea class="form-control bg-dark text-white" type="text" id="about"
					placeholder="Enter something about you" v-model="profile.about"></textarea>

				<div v-if="form.errors.about" class="text-danger small">
					{{ form.errors.about }}
				</div>
			</div>
			<!-- Mobile -->
			<div class="mb-3">
				<label class="form-label" for="mobile">
					Mobile Number
				</label>
				<input class="form-control bg-dark text-white" type="text" id="mobile" placeholder="0123456789" required
					v-model="profile.mobile" />

				<div v-if="form.errors.mobile" class="text-danger small">
					{{ form.errors.mobile }}
				</div>
			</div>
			<!-- Password -->
			<div class="mb-3">
				<label class="form-label" for="password">Password</label>
				<input class="form-control bg-dark text-white" type="password" id="password"
					v-model="profile.password" />

				<div v-if="form.errors.password" class="text-danger small">
					{{ form.errors.password }}
				</div>
			</div>
			<div class="d-flex align-items-center mb-3">
				<button class="btn btn-primary" :disabled="form.processing">
					<span v-if="form.processing">
						Updating
					</span>
					<span v-else>
						Update
					</span>
					<div class="ms-2 spinner-border spinner-border-sm text-light" v-if="form.processing" role="status">
					</div>
				</button>
				<span class="ms-3">
					{{ updateResult }}
				</span>
			</div>
		</form>
	</div>
</template>

<script setup>
import { reactive, ref } from 'vue';

let props = defineProps({
	profile: Object
});

let updateResult = ref('');

let profile = reactive(props.profile);
let imageInput = ref(null);
let form = reactive({
	errors: {},
	processing: false
});

async function updateProfile() {
	form.processing = true;
	form.errors = {};
	try {
		let fd = new FormData();
		fd.append('first_name', profile.first_name);
		fd.append('last_name', profile.last_name);
		fd.append('about', profile.about);
		fd.append('mobile', profile.mobile);
		if (profile.password) {
			fd.append('password', profile.password);
		}
		if (imageInput.value !== null) {
			fd.append('image', imageInput.value);
		}

		let res = await axios.post('/api/updateProfile', fd, {
			headers: {
				"Content-Type": "multipart/form-data"
			},
		});
		for (let key in res.data) {
			profile[key] = res.data[key];
		}

		profile.password = '';

		updateResult.value = 'Updated';
	} catch (err) {
		if (err.response?.status == 422) {
			console.log(err.response.data);
			let errors = {};
			for (let key in err.response.data.errors) {
				errors[key] = err.response.data.errors[key][0];
			}
			form.errors = errors;
		} else {
			console.log(err);
			updateResult.value = 'Could not update.';
		}
	} finally {
		form.processing = false;

		setTimeout(() => {
			updateResult.value = '';
		}, 3000);
	}

}

function handleFileInput(event) {
	if (event.target.files.length > 0) {
		imageInput.value = event.target.files[0];

		let reader = new FileReader();
		
		reader.readAsDataURL(imageInput.value);

		reader.onload = function(e) {
			profile.image = reader.result;
		}

	}
}
</script>

<style>
.profile {
	padding: 0 1rem;
}

.profile input,
.profile textarea {
	border: 0;
}

.profile-image {
	border-radius: 100%;
	margin-top: 2rem;
	margin-bottom: 2rem;
	width: 150px;
	height: 150px;
	cursor: pointer;
	object-fit: cover;
	object-position: center;
}
</style>