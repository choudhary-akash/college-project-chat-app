<template>
	<Auth>
		<h1 class="mb-4 text-center">Login</h1>
		<form @submit.prevent="handleSubmit">
			<div class="mb-3">
				<label class="form-label" for="mobile">Mobile</label>
				<input class="form-control bg-dark text-white" type="text" id="mobile" placeholder="0123456789" v-model="form.mobile" required />

				<div v-if="form.errors.mobile" class="text-danger small">
					{{ form.errors.mobile }}
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label" for="password">Password</label>
				<input class="form-control bg-dark text-white" type="password" id="password" v-model="form.password" required />

				<div v-if="form.errors.password" class="text-danger small">
					{{ form.errors.password }}
				</div>
			</div>
			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" id="remember" v-model="form.remember" />
				<label class="form-check-label" for="remember">
					Remember Me
				</label>
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
		<p>
			Not registered?
			<Link class="link link-primary" href="/register">
			Create account
			</Link>
		</p>
	</Auth>
</template>

<script setup>
import Auth from '../../Layouts/Auth.vue';
import { useForm, Link } from '@inertiajs/inertia-vue3';

const form = useForm({
	mobile: '',
	password: '',
	remember: false
});

function handleSubmit(event) {
	event.preventDefault();
	
	form.post('/login');
}

</script>

<style>
</style>