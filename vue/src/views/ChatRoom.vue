<template>
    <div>
        <h2 class="p-4 bg-gray-300 text-lg">{{ props.user.name }}</h2>
        <div class="p-4 bg-white h-80 overflow-y-auto">
            <div v-for="message in props.user.messages" :key="message.id">
                <div v-if="message.from === 'user'" class="flex flex-row-reverse items-center mb-2">
                    <img src="user-avatar.png" alt="User Avatar" class="w-8 h-8 rounded-full mr-2" />
                    <span class="bg-blue-500 text-white p-2 rounded-lg">{{ message.content }}</span>
                </div>
                <div v-else class="flex flex-row items-center mb-2">
                    <img src="friend-avatar.png" alt="Friend Avatar" class="w-8 h-8 rounded-full ml-2" />
                    <span class="bg-gray-200 p-2 rounded-lg">{{ message.content }}</span>
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
import axios from 'axios';
import Echo from 'laravel-echo';
import { ref, defineProps, onMounted } from 'vue';

export interface User {
    id: number;
    name: string;
    status: string;
    avatar: string;
    messages: Message[];
}

export interface Message {
    chat_id: Number | null;
    content: string;
    from: 'user' | 'friend';
}

const props = defineProps<{
    user: User,
    chatId: Number | null
}>()

const message = ref('');

async function sendMessage(): Promise<void> {
    if (!message.value.trim()) {
        return;
    }

    const newMessage: Message = {
        chat_id: props.chatId,
        content: message.value,
        from: 'user',
    };

    await axios.post('/api/message/send', newMessage).then((res) => {
        props.user.messages.push(newMessage);
        message.value = '';
    }).catch((err) => {
        console.log(err)
        message.value = '';
    })
}

onMounted(() => {
    console.log('hola');
    window.Echo.join(`chat.4`)
        .here((users) => {
            console.log(users);
        })
        .listen('MessageSent', (e: any) => {
            console.log(e);
        })
})

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
</style>
  