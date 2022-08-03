<template>
	<Auth>
		<h1 class="mb-4 text-center">Register</h1>
		<form method="POST" @submit.prevent="handleSubmit">
			<div class="row">
				<div class="col-12 col-sm-6 mb-3">
					<label class="form-label" for="first_name">
						First Name
					</label>
					<input class="form-control bg-dark text-white" type="text" id="first_name" placeholder="John"
						required v-model="form.first_name" />

					<div v-if="form.errors.first_name" class="text-danger small">
						{{ form.errors.first_name }}
					</div>
				</div>
				<div class="col-12 col-sm-6 mb-3">
					<label class="form-label" for="last_name">
						Last Name
					</label>
					<input class="form-control bg-dark text-white" type="text" id="last_name" placeholder="Doe" required
						v-model="form.last_name" />

					<div v-if="form.errors.last_name" class="text-danger small">
						{{ form.errors.last_name }}
					</div>
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label" for="mobile">
					Mobile Number
				</label>
				<input class="form-control bg-dark text-white" type="text" id="mobile" placeholder="0123456789" required
					v-model="form.mobile" />

				<div v-if="form.errors.mobile" class="text-danger small">
					{{ form.errors.mobile }}
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label" for="password">Password</label>
				<input class="form-control bg-dark text-white" type="password" id="password" required
					v-model="form.password" />

				<div v-if="form.errors.password" class="text-danger small">
					{{ form.errors.password }}
				</div>
			</div>
			<button class="btn btn-primary mb-3" :disabled="form.processing">
				<span v-if="form.processing">
					Submitting
				</span>
				<span v-else>
					Submit
				</span>
				<div class="ms-2 spinner-border spinner-border-sm text-light" v-if="form.processing" role="status">
				</div>
			</button>
		</form>
		<p class="">
			Already have an account?
			<Link class="link link-primary" href="/login"> Sign In </Link>
		</p>
	</Auth>
</template>

<script setup>
import Auth from '../../Layouts/Auth.vue';
import { useForm, Link } from '@inertiajs/inertia-vue3';


const form = useForm({
	first_name: '',
	last_name: '',
	mobile: '',
	password: '',
});

function handleSubmit() {
	form.post('/register');
}
</script>

<style>
</style>