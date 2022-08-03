<template>
	<div class="chat-window">
		<div class="status-bar" @click="showProfile">
			<img :src="recipient.image" alt="" class="display-image" />
			<div>
				<span>{{ recipient.first_name + ' ' + recipient.last_name }}</span>
				<transition name="fade">
					<div v-if="isOnline" class="online-status">Online</div>
				</transition>
			</div>
		</div>
		<div class="message-list" ref="messageListEl">
			<div v-if="ChatStore.selectedChat.fetching == true" class="spinner-border text-light"></div>
			<div v-for="message in ChatStore.selectedChat.messages" class="message" :class="{'sent-message': message.sender_id == user.id}">
				<div>
					{{ message.content }}
				</div>
				<div class="msg-timestamp">
					{{ moment(message.sent_at).format('LT') }}
				</div>
			</div>
			<div ref="chatBodyBottom"></div>
		</div>
		<div class="input-bar">
			<form @submit.prevent="sendMessage">
				<input type="text" class="text-light" v-model="messageInput" autocomplete="off" placeholder="Enter your message" />
				<button type="submit" class="send-btn text" :class="{'disabled': messageInput.trim().length == 0}">
					<i class="fas fa-paper-plane"></i>
				</button>
			</form>
		</div>
	</div>
</template>

<script setup>
import axios from 'axios';
import moment from 'moment';
import { ref, inject, onMounted, watch, nextTick, computed } from 'vue';
import { ChatStore } from '../Store/ChatStore';

let chatBodyBottom = ref(null);
let messageInput = ref('');
let messageListEl = ref(null);
const emitter = inject('emitter');

let props = defineProps({
	recipient: Object,
	user: Object,
	onlineUsers: Array,
});

let isOnline = computed(() => {
	return props.onlineUsers.find(onlineUser => {
		return onlineUser.id == props.recipient.id;
	})
})

function scrollToBottom() {
	messageListEl.value.scrollTop = messageListEl.value.scrollHeight;
}

async function sendMessage(e) {
	if (messageInput.value.trim().length == 0) return;
	let currentChatId = ChatStore.selectedChat.id;
	let isChatTemp = ChatStore.selectedChat.temp;
	let message = {
		content: messageInput.value,
		sender_id: props.user.id,
		sent_at: new Date(),
	};

	axios.post(`/api/personal_messages/${props.recipient.id}`, message)
		.then(res => {
			let createdMessage = res.data;

			if (isChatTemp) {
				let chat = ChatStore.findChat(currentChatId);
				chat.id = createdMessage.chat_id;
				chat.temp = false;
			}
		})
		.catch(err => {
			console.log('Message sending failed.', err);
		});

	messageInput.value = '';

	ChatStore.addMessage(props.recipient.id, message);
}

onMounted(() => {
	scrollToBottom();
})

watch(() => ChatStore.selectedChat.id, async function (chatId) {
	await ChatStore.fetchMessages(chatId);
	scrollToBottom();
});

emitter.on('message-added', function () {
	nextTick(() => {
		chatBodyBottom.value.scrollIntoView({ behavior: 'smooth' });
	});
})

function showProfile() {
	emitter.emit('select-profile', props.recipient);
	emitter.emit('open-screen', 'profile');
}
</script>

<style>
.chat-window {
	width: 100%;
	height: 100%;
	max-height: 100%;
	display: flex;
	flex-direction: column;
}

.status-bar {
	height: 10%;
	background-color: var(--bg-color-2);
	padding: 1rem 2rem;
	display: flex;
	gap: 1rem;
	align-items: center;
	cursor: pointer;
}

.status-bar .display-image {
	height: 100%;
	border-radius: 100%;
	object-fit: cover;
	object-position: center;
}

.status-bar .online-status {
	font-size: 14px;
	color: var(--primary-color);
}

.message-list {
	flex: 1;
}

.input-bar {
	height: 10%;
	background-color: var(--bg-color-2);
	padding: 0.75rem 1rem;
}

.input-bar form {
	display: flex;
	height: 100%;
}

.input-bar input {
	flex: 1;
	height: 100%;
	padding-left: 1rem;
	padding-right: 1rem;
	background-color: var(--bg-color-3);
	outline: none;
	border: none;
	border-radius: 5px;
}

.input-bar input::placeholder {
	color: rgba(255, 255, 255, 0.6);
}

.input-bar .send-btn {
	padding-left: 1rem;
	padding-right: 1rem;

	background-color: transparent;
	color: white;
	border: 0;
	outline: 0;
}

.message-list {
	display: flex;
	overflow: auto;
	flex-direction: column;
	padding: 1rem;
	gap: 1rem;
}

.message:first-of-type {
	margin-top: auto;
}

.message {
	padding: 1rem 2rem;
	background-color: var(--bg-color-2);
	width: fit-content;
}

.sent-message {
	margin-left: auto;
}

.msg-timestamp {
	font-size: 12px;
	color: rgba(255, 255, 255, 0.8);
}

.spinner-border {
	margin: 0 auto auto auto;
}
</style>