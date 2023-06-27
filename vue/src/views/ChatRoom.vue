<template>
    <div>
        <div>
            <h2 class="p-4 bg-gray-300 text-lg flex">
                <span class="flex align-items-center items-center gap-x-4">{{ props.user.name }} <div v-if="props.isConnected" class="ml-auto h-2 w-2 bg-green-500 rounded-full"></div></span>
            </h2>
        </div>
        <div class="p-4 bg-white overflow-y-auto" id="chat-msg-container">
            <div v-for="message in chatMessages" :key="message.id">
                <div v-if="message.user_id === userId" class="flex flex-row-reverse items-center mb-2">
                    <img :src="currentUser.avatar" alt="User Avatar" class="w-8 h-8 rounded-full mr-2" />
                    <span class="bg-blue-500 text-white p-2 rounded-lg">{{
                        message.content
                    }}</span>
                </div>
                <div v-else class="flex flex-row items-center mb-2">
                    <img :src="props.user.avatar" alt="Friend Avatar" class="w-8 h-8 rounded-full ml-2" />
                    <span class="bg-gray-200 p-2 rounded-lg">{{
                        message.content
                    }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <input v-model="message" @keyup.enter="sendMessage" class="p-4 bg-gray-100 w-full rounded-lg"
                placeholder="Escribe un mensaje..." />
            <button @click="sendMessage" class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                Enviar
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, defineProps, onMounted, reactive, watch } from "vue";
import { useAuthStore } from "@/stores/AuthStore";

export interface ChatUser {
    id: number;
    name: string;
    status: string;
    avatar: string;
    messages: Message[];
}

export interface Message {
    chat_id: number | null;
    content: string;
    user_id: number;
}

const userStore = useAuthStore();
const currentUser = () => userStore.user;
const userId = currentUser().id;

const props = defineProps<{
    user: ChatUser;
    chatId: number | null;
    isConnected: Boolean;
}>();

const message = ref("");
const chatMessages = ref<Message[]>([]);

function scrollToBottom() {
    const chatContainer = document.getElementById("chat-msg-container");
    if (chatContainer) {
        const containerHeight = chatContainer.clientHeight;
        const contentHeight =
            chatContainer.scrollHeight -
            parseInt(getComputedStyle(chatContainer).paddingTop) -
            parseInt(getComputedStyle(chatContainer).paddingBottom);
        chatContainer.scrollTop = contentHeight - containerHeight;
    }
}

async function sendMessage(): Promise<void> {
    if (!message.value.trim()) {
        return;
    }

    const newMessage: Message = {
        chat_id: props.chatId,
        content: message.value,
        user_id: userId,
    };

    message.value = "";

    chatMessages.value.push(newMessage);

    scrollToBottom();

    try {
        await axios.post("/api/message/send", newMessage);
    } catch (err) {
        console.log(err);
    }
}

watch(
    () => props.chatId,
    async (newChatId) => {
        if (newChatId) {
            const chatChannel = `chat.${newChatId}`;
            window.Echo.join(chatChannel)
                .listen("MessageSent", (e: any) => {
                    if (e.message.user_id !== userId) {
                        chatMessages.value.push(e.message);
                    }
                });

            await axios
                .get(`api/chat/${newChatId}/get-messages`)
                .then((res) => {
                    chatMessages.value = res.data.messages;
                });
        }
    }
);
</script>

<style scoped>
/* ... */

input {
    border: none;
}

button {
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #2563eb;
}

#chat-msg-container {
    height: 82vh;
}
</style>
