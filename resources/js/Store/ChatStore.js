import { reactive } from "vue";
import axios from "axios";
import { random } from "lodash";

export const ChatStore = reactive({
	chats: reactive([]),
	selectedChat: null,
	async fetchChats() {
		try {
			let res = await axios.get('/api/chats');
			if (Array.isArray(res.data) && res.data.length > 0) {
				this.chats = res.data.map(chat => {
					chat.unreadCount = 0;
					return chat;
				});
			}
		} catch (err) {
			console.error(err);
		}
	},
	// async fetchChatById(chatId) {
	// 	try {
	// 		let res = await axios.get('/api/chats/'+chatId);

	// 		if (!this.findChat(chatId)) {
	// 			this.chats.push(chat);
	// 			return this.chats[this.chats.length - 1];
	// 		}

	// 		this.findChat(chatId);
	// 	} catch (err) {
	// 		console.error(err);
	// 	}
	// },
	async fetchMessages(chatId) {
		let chat = this.findChat(chatId);
		
		if (chat.temp == true) return;

		chat.fetching = true;

		let res = await axios.get(`/api/chats/${chatId}/messages`);
		chat.messages = res.data;

		chat.fetching = false;
	},
	findChat(chatId) {
		return this.chats.find(chat => {
			return chat.id == chatId;
		});
	},
	findChatByRecipient(recipientId) {
		return this.chats.find(chat => {
			return chat.recipient.id == recipientId;
		});
	},
	addMessage(recipientId, message) {
		let chat = this.findChatByRecipient(recipientId);

		if (!chat) {
			return null;
		}

		chat.messages.push(message);
		this.emitter.emit('message-added', message);
	},
	addMessageByChat(chatId, message) {
		let chat = this.findChat(chatId);

		if (!chat) {
			return null;
		}

		chat.messages.push(message);
	},
	async addIncomingMessage(chatId, message) {
		if (this.selectedChat?.id == chatId) {
			this.selectedChat.messages.push(message);
		} else {
			let chat = this.findChat(chatId);

			if (!chat) {
				let res = await axios.get('/api/chats/' + chatId);

				res.data.messages.push(message);
				res.data.unreadCount = 1;
				this.chats.push(res.data);
			}

			chat.messages.push(message);
			chat.unreadCount += 1;
		}
	},
	async markChatAsRead(chatId) {
		let chat = this.fetchChatById(chatId);

		if (!chat) return null;

		chat.unreadCount = 0;
	},
	getTempChat(recipientId, recipientName, recipientImage) {
		let tempChat = {
			id: random(5000, false),
			recipient: {
				id: recipientId,
				name: recipientName,
				image: recipientImage,
			},
			messages: [],
			temp: true,
			unreadCount: 0
		};

		return tempChat;
	},
	async selectChat(chatId) {
		let chat = this.findChat(chatId);

		if (!chat) return;

		await this.fetchMessages(chatId);

		this.selectedChat = chat;
		this.selectedChat.unreadCount = 0;
	},
	async selectChatByRecipient(recipient) {
		let chat = this.findChatByRecipient(recipient.id);

		if (!chat) {
			let tempChat = this.getTempChat(recipient.id, recipient.name, recipient.image);
			this.chats.push(tempChat);
			this.selectedChat = tempChat;
		} else {
			await this.fetchMessages(chat.id);
			this.selectedChat = chat;
		}
		
		this.selectedChat.unreadCount = 0;
	}
});