<template>
	<nav class='nav'>
		<button class='nav-link profile-link mb-auto' @click="selectScreen('selfProfile')"
			:class="{ 'active': activeScreen == 'selfProfile' }">
			<img :src="user.image" alt=""/>
		</button>

		<button class='nav-link' @click="selectScreen('chats')" :class="{ 'active': activeScreen == 'chats' }">
			<i class='fa-solid fa-message'></i>
		</button>

		<button class='nav-link' @click="selectScreen('contacts')" :class="{ 'active': activeScreen == 'contacts' }">
			<i class='fa-solid fa-user-group'></i>
		</button>

		<Link href="/logout" class="nav-link mt-auto text-danger" method="post" as="button" type="button">
			<i class='fa-solid fa-arrow-right-from-bracket'></i>
		</Link>
	</nav>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { inject } from 'vue';

let props = defineProps({
	activeScreen: String,
	user: Object
});

let emitter = inject('emitter');

function selectScreen(screen) {
	emitter.emit('open-screen', screen);
}
</script>

<style>
.nav {
	background-color: #313442;
	min-width: 75px;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	color: white;
	padding: 2rem 0;
}

.nav .nav-link {
	color: white;
	background-color: transparent;
	border: 0;
	width: 100%;
	text-align: center;
	position: relative;
	padding: 1.5rem 0;
	font-size: 1.25rem;
	transition: all 0.3s;
}

.nav .nav-link.active {
	color: #0088D5;
}

.nav .nav-link:hover {
	background-color: rgba(255, 255, 255, 0.1);
}

.nav .nav-link.active::before,
.nav .nav-link:hover::before {
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	bottom: 0;
	width: 4px;
	background-color: #0088D5;
	transition: all 0.3s;
}

.nav .profile-link {
	padding: 1rem 0;
}

.profile-link img {
	width: 3rem;
	height: 3rem;
	border-radius: 100%;
	object-fit: cover;
	object-position: center;
}
</style>