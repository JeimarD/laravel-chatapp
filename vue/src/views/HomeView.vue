<template>
    <main>
        <h1>This is the homepage</h1>
        <template v-if="currentUser()">
            <h2>Welcome {{ currentUser().name }}</h2>
            <RouterLink to="/register">Register</RouterLink>
            <p>go to <RouterLink to="/messages">Messages</RouterLink>
            </p>
        </template>
        <template v-else>
            <h2>Your are not logged in</h2>
            <p>go to <RouterLink to="/login">Login</RouterLink> or <RouterLink to="/register">Register</RouterLink>
            </p>
        </template>
    </main>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";
import axios from "axios";
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/AuthStore";

const userStore = useAuthStore();
const currentUser = () => userStore.user;


const getUser = async () => {
    await axios.get("api/user").then((res) => {
        console.log(res);
    });
};

onMounted(() => {
    getUser();
})

</script>


