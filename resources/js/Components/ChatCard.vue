<template>
	<div class="chat-card" @click="selectChat" :class="{ 'selected': ChatStore.selectedChat?.id == chat.id }">
		<div class="profile-img">
			<img :src="chat.recipient.image" alt="" />
		</div>
		<div class="user-info">
			<span>{{ recipientFullName }}</span>
			<div class="latest-msg">
				<span v-if="latestMessage.sender_id == user.id">You: </span>
				{{ latestMessage.content }}
			</div>
		</div>
		<div class="meta-data ms-auto">
			<div class="latest-msg-date">
				<span v-if="moment().isSame(latestMessage.send_at, 'day')">
					{{ moment(latestMessage.sent_at).format('LT') }}
				</span>
				<span v-else-if="moment(0, 'HH').diff(date, 'days') < 7">
					{{ moment(latestMessage.sent_at).format('DD/MM/YY') }}
				</span>
			</div>
			<div v-if="chat.unreadCount > 0" class="mx-auto unread-count bg-primary">
				{{ chat.unreadCount }}
			</div>
		</div>
	</div>
</template>

<script setup>
import { inject, ref, computed } from 'vue';
import moment from 'moment';
import { ChatStore } from '../Store/ChatStore';

let props = defineProps({
	chat: Object,
	user: Object
});

let emitter = inject('emitter');

const latestMessage = computed(() => {
	return props.chat.messages[props.chat.messages.length - 1];
});

let recipientFullName = computed(() => {
	return props.chat.recipient.first_name + ' ' + props.chat.recipient.last_name;
});

function selectChat() {
	emitter.emit('select-chat', props.chat.recipient);
}
</script>

<style>
.chat-card {
	display: flex;
	flex-direction: row;
	padding: 1rem 1rem;
	gap: 1rem;
	cursor: pointer;
}

.chat-card:hover,
.chat-card.selected {
	background-color: rgba(255, 255, 255, 0.1);
}

.chat-card .profile-img {
	min-width: 3rem;
	height: 3rem;
	border-radius: 1000px;
	overflow: hidden;
}

.chat-card .profile-img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center;
}

.chat-card .user-info {
	display: flex;
	max-width: 100%;
	flex-direction: column;
	row-gap: 0.3rem;
	overflow: hidden;
}

.latest-msg {
	overflow: hidden;
	max-width: 100%;
	white-space: nowrap;
	text-overflow: ellipsis;
	font-size: 14px;
	color: rgba(255, 255, 255, 0.8);
}

.latest-msg-date {
	color: rgba(255, 255, 255, 0.8);
}

.unread-count {
	font-size: 1rem;
	display: grid;
	place-items: center;
	width: 1.75rem;
	height: 1.75rem;
	border-radius: 100%;
}
</style>