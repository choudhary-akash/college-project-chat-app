<template>
	<div class="App">
		<SideNav :activeScreen="activeScreen" :user="user" />
		<div class="leftCol">
			<slot name="leftCol">
				<Chats v-if="activeScreen == 'chats'" :user="user"></Chats>
				<Contacts v-if="activeScreen == 'contacts'" :user="user"></Contacts>
				<Profile v-if="activeScreen == 'profile'" :profile="state.selectedProfile"></Profile>
				<SelfProfile v-if="activeScreen == 'selfProfile'" :profile="user"></SelfProfile>
			</slot>
		</div>
		<div class="rightCol">
			<ChatWindow v-if="ChatStore.selectedChat != null" :recipient="state.currentRecipient" :user="user"
				:onlineUsers="state.onlineUsers">
			</ChatWindow>
			<div v-else class="h-100 d-flex align-items-center justify-content-center">
				Select an existing chat or start a new one.
			</div>
		</div>
	</div>
</template>

<script setup>
import SideNav from '../Components/SideNav.vue';
import ChatWindow from '../Components/ChatWindow.vue';
import { reactive, ref, inject, computed, watch, onBeforeUnmount } from 'vue';
import { ChatStore } from '../Store/ChatStore';
import Chats from '../Components/Chats.vue';
import Contacts from '../Components/Contacts.vue';
import SelfProfile from '../Components/SelfProfile.vue';
import Profile from '../Components/Profile.vue';

// Variable Declarations
let emitter = inject('emitter');
let Echo = inject('Echo');

let state = reactive({
	selectedProfile: {},
	currentRecipient: {},
	screenHistory: ['chats'],
	onlineUsers: []
});

let props = defineProps({
	user: Object
});

let activeScreen = computed(() => {
	if (state.screenHistory.length == 0) return null;

	return state.screenHistory[state.screenHistory.length - 1];
});

// Event Listeners
Echo.private('user-' + props.user.id).listen('IncomingMessage', (e) => {
	let message = e.message;
	ChatStore.addIncomingMessage(message.chat_id, message);
});

Echo.join('presence')
	.here((users) => {
		state.onlineUsers = users;
	})
	.joining((user) => {
		state.onlineUsers.push(user);
	})
	.leaving((user) => {
		let index = state.onlineUsers.find((onlineUser) => onlineUser.id == user.id);
		if (index) {
			state.onlineUsers.splice(index);
		}
	});

onBeforeUnmount(() => {
	Echo.leave('user-' + props.user.id);
	Echo.leave('presence');
});

watch(() => state.onlineUsers, function () {
	console.log(state.onlineUsers);
});

emitter.on('select-chat', function (user) {
	if (state.currentRecipient.id == user.id) return;

	state.currentRecipient = user;

	ChatStore.selectChatByRecipient(state.currentRecipient);
});

emitter.on('open-screen', function (screen) {
	state.screenHistory.push(screen);
});

emitter.on('close-screen', function (screen) {
	state.screenHistory.pop();
});

emitter.on('select-profile', function (selectedUser) {
	state.selectedProfile = selectedUser;
});
</script>

<style>
.App {
	height: 100vh;
	width: 100vw;
	display: grid;
	grid-template-columns: auto 1fr 2fr;
}

.leftCol {
	overflow-y: auto;
	overflow-x: hidden;
	padding: 2rem 0;
	background-color: var(--bg-color-2);
}

.rightCol {
	overflow-y: auto;
	overflow-x: hidden;
	background-color: var(--bg-color-3);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s
}

.fade-enter,
.fade-leave-to {
    opacity: 0
}
</style>