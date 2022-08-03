<template v-slot:leftCol>
	<div class="chat-page d-flex flex-column">
		<h3 class="px-3">Chats</h3>
		<ChatCard v-for="chat in ChatStore.chats" :chat="chat" :user="user"></ChatCard>
		<p v-if="ChatStore.chats.length == 0" class="text-center my-auto">
			No chats found. Start a new chat.
		</p>
	</div>
</template>

<script setup>
import { computed, inject, ref } from 'vue';
import ChatCard from '../Components/ChatCard.vue';
import { ChatStore } from '../Store/ChatStore';

let emitter = inject('emitter');

ChatStore.fetchChats();

let props = defineProps({
	user: Object
});
</script>

<style>
.chat-page {
	display: flex;
	flex-direction: column;
	height: 100%;
}

.chat-message .empty-message {
	flex: 1;
}
</style>