<template>
	<div class="user-card" @click="selectChat">
		<div class="profile-img">
			<img :src="user.image" alt="" />
		</div>
		<div class="user-info">
			<span class="text-truncate">{{ user.first_name + ' ' + user.last_name }}</span>
			<span class="text-truncate">{{ user.mobile }}</span>
		</div>
		<button v-if="isContact == false" @click.stop="addContact" class="btn btn-primary add-contact-btn">Add To Contacts</button>
	</div>
</template>

<script setup>
import { inject } from 'vue';
import axios from 'axios';

const props = defineProps({
	user: Object,
	isContact: Boolean
});

const emitter = inject('emitter');

function selectChat() {
	emitter.emit('select-chat', props.user);
}

async function addContact() {
	try {
		await axios.post('/api/contacts', {
			contact_id: props.user.id
		});

		emitter.emit('refresh-contacts');
	} catch (err) {
		console.error(err);
	}


}
</script>

<style>
.user-card {
	display: flex;
	flex-direction: row;
	padding: 1rem 1rem;
	gap: 1rem;
	cursor: pointer;
}

.user-card:hover {
	background-color: rgba(255, 255, 255, 0.1);
}

.user-card .profile-img {
	width: 3rem;
	height: 3rem;
	border-radius: 1000px;
	overflow: hidden;
	display: grid;
	place-items: center;
}

.user-card .profile-img img {
	max-width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center;
}

.user-card .user-info {
	display: flex;
	flex-direction: column;
	row-gap: 0.3rem;
}

.user-card .user-info * {
	max-width: 100%;
	overflow: hidden;
}

.add-contact-btn {
	border-radius: 1000px;
	font-size: small;
	margin-left: auto;
}
</style>