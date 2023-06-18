<template>
    <main>
        <h1>This is the homepage</h1>
        <template v-if="currentUser()">
            <h2>Welcome {{ currentUser().name }}</h2>
            <RouterLink to="/register">Register</RouterLink>
      <a href="javascript:void(0)" @click.prevent="handleLogout">Logout</a>
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
import { useAuthStore } from "@/stores/AuthStore";
import axios from "axios";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

const route = useRouter();

const userStore = useAuthStore();

const getUser = async () => {
    await axios.get("api/user").then((res) => {
        console.log(res);
    });
};

const currentUser = () => userStore.user;

const handleLogout = () => {
    userStore.logout();
    route.push("/login");

}

onMounted(() => {
    getUser();
})

</script>


