<template>
  <div class="flex h-screen">
    <!-- Barra lateral -->
    <div class="w-1/4 bg-gray-200">
      <ul>
        <li v-for="user in fetchedUsers" :key="user.id" @click="createChat(user)"
          :class="{ 'bg-blue-500 text-white': user === selectedUser }"
          class="p-4 cursor-pointer hover:bg-gray-300 flex items-center space-x-2">
          <img :src="user.avatar" :alt="user.name" class="w-8 h-8 rounded-full" />
          <div>
            <h4 class="text-lg font-semibold">{{ user.name }}</h4>
          </div>
          <div v-if="isConnected(user.id)" class="ml-auto h-2 w-2 bg-green-500 rounded-full"></div>
        </li>
      </ul>
      <div>
        <RouterLink to="/">Return Home</RouterLink>
      </div>
    </div>

    <!-- Sala de chat -->
    <div class="w-3/4">
      <ChatRoom v-if="selectedUser" :user="selectedUser" :chatId="chatId" />
      <div v-else class="h-full flex items-center justify-center">
        <p>Selecciona un usuario para comenzar el chat.</p>
      </div>
    </div>
  </div>
</template>
  
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import ChatRoom from './ChatRoom.vue';
import axios from 'axios';

interface ChatUser {
  id: number;
  name: string;
  status: string;
  avatar: string;
  messages: Message[];
}

interface Message {
  chat_id: number;
  content: string;
  user_id: number;
}

const fetchedUsers = ref<ChatUser[]>([]);

const selectedUser = ref<ChatUser | null>(null);

const chatId = ref<Number | null>(null);

const connectedUsers = ref([]);

async function getUsers(): Promise<void> {
  try {
    const response = await axios.get('/api/users');
    console.log(response);
    fetchedUsers.value = response.data.users.data;
  } catch (error) {
    console.error('There was a problem retrieving the users', error);
  }
}

async function createChat(user: ChatUser): Promise<void> {
  selectedUser.value = user;
  try {
    const response = await axios.post('/api/chat/create', {
      friend_user_id: user.id,
    });

    chatId.value = response.data.chatId;
  } catch (error) {
    console.error('There was a problem creating the chatroom', error);
  }
}

async function getConnectedUsers() {
  await axios.get('api/users/online').then((res) => {
    console.log(res);
    connectedUsers.value = res.data.users;
  }).catch((err) => {
    console.log(err);
  })
}

function isConnected(userId) {
  return connectedUsers.value.includes(userId.toString());
}

onMounted(() => {
  getUsers();
  getConnectedUsers();

  window.Echo.join('connected-users').listen('LoggedIn', () => {
    getConnectedUsers();
  })

  window.Echo.join('connected-users').listen('LogOut', () => {
    getConnectedUsers();
  })
});
</script>
  