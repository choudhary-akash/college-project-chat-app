<template v-slot:leftCol>
	<div>
		<h3 class="px-3">Contacts</h3>
		<div class="search-field">
			<i class="fas fa-magnifying-glass ps-4 pe-3"></i>
			<input class="text-white" type="text" v-model="search" @change="handleChange"
				placeholder="Type a name or number to search" />
			<i class="fas fa-times text-white clear-search" :class="{'invisible': search.length == 0}"
			@click="clearSearch"></i>
		</div>

		<div class="contact-list mt-4">
			<h6 class="px-3">Contacts</h6>
			<UserCard v-for="contact in contacts" :key="contact.id" :user="contact" :isContact="true"></UserCard>
			<p class="text-center" v-if="contacts.length == 0">
				No contacts found.
			</p>
		</div>

		<div class="all-user-list mt-4">
			<h6 class="px-3">All Users</h6>
			<UserCard v-for="searchUser in users" :key="searchUser.id" :user="searchUser" :isContact="false"></UserCard>
			<p class="text-center" v-if="users.length == 0">
				No user found.
			</p>
		</div>
	</div>
</template>

<script setup>
import { reactive, ref, watch, inject } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';
import UserCard from './UserCard.vue';

let contacts = reactive([]);
let users = reactive([]);
let search = ref('');

let emitter = inject('emitter');

let props = defineProps({
	user: Object
});

// Filter Contacts for the first time
fetchContacts();

// Watch for search input change and upadte contact list accordingly
watch(search, debounce(function (value) {
	// Store current search input in localStorage
	localStorage.setItem('contactSearch', value);

	fetchContacts(value);
	if (value !== '') {
		fetchUsers(value);
	}
}, 300));

async function addContact(userId) {
	let res = await axios.post('/api/contacts', {
		contact_id: userId,
	});
}

async function fetchContacts(searchVal) {
	let res = await axios.get('/api/contacts', {
		params: {
			'search': searchVal
		}
	});

	// Replace entire contents of the array with the fetched data
	contacts.splice(0, contacts.length, ...res.data);
}

async function fetchUsers(searchVal) {
	let res = await axios.get('/api/users', {
		params: {
			'search': searchVal
		}
	});

	users.splice(0, users.length, ...res.data);
}

function clearSearch() {
	search.value = '';
	users.splice(0, users.length);
}

emitter.on('refresh-contacts', () => {
	fetchUsers();
	fetchContacts();
})

</script>

<script>
export default {

};
</script>

<style>
.search-field {
	display: flex;
	align-items: center;
	max-width: 100%;
	margin: 2rem 1rem;
	border-radius: 1000px;
	background: var(--bg-color-3);
}

.search-field > * {
	padding-top: 1rem;
	padding-bottom: 1rem;
}

.search-field input {
	flex: 1;
	background-color: transparent;
	overflow-x: hidden;
	border: 0;
}

.search-field input::placeholder {
	color: #ccc;
}

.search-field input:focus {
	border: 0;
	outline: 0;
}

.clear-search {
	padding: 1rem;
	height: 3rem;
	width: 3rem;
	margin-right: 0.5rem;
	cursor: pointer;
	display: grid;
	place-items: center;
	text-align: center;
}

.clear-search:hover {
	background-color: rgba(255, 255, 255, 0.1);
	border-radius: 100%;
}
</style>