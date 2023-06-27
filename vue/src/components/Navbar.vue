<template>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <RouterLink to="/" class="flex items-center">
                <img src="/logo.png" class="h-14 mr-3" alt="" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">ChatterBox</span>
            </RouterLink>
            <div class="flex items-center md:order-2 flex-col">
                <button type="button" class="flex mr-3 hidden md:block text-sm bg-gray-800 rounded-full md:mr-0 focus:ring focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" @click="isUserDropdownOpen = !isUserDropdownOpen">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-12 h-12 rounded-full" src="https://www.batiburrillo.net/wp-content/uploads/2019/07/Ampliacio%CC%81n-de-imagen-en-li%CC%81nea-sin-perder-calidad.jpg" alt="">
                </button>
                <button type="button" class="flex md:hidden mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" @click="isMobileMenuOpen = !isMobileMenuOpen">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-16 h-16 rounded-full" src="https://www.batiburrillo.net/wp-content/uploads/2019/07/Ampliacio%CC%81n-de-imagen-en-li%CC%81nea-sin-perder-calidad.jpg" alt="">
                </button>
                <div class="absolute top-14 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" :class="{'hidden': !isUserDropdownOpen}" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">User User</span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400">user@example.com</span>
                    </div>
                    <ul>
                        <li>
                            <RouterLink to="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"><i class="fa-solid fa-user"></i> My Profile</RouterLink>
                        </li>
                        <li>
                           <a href="#" @click="handleLogout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
                        </li>
                    </ul>
                </div>
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" arial-controls="mobile-menu-2" :aria-expanded="isMobileMenuOpen.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="items-center justify-between w-full md:flex md:w-auto md:order-1" :class="{'hidden': !isMobileMenuOpen}" id="mobile-menu-2">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <RouterLink to="/" class="block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500">Home</RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/messages" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"><i class="fa-regular fa-comment"></i> Chat</RouterLink>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script setup lang="ts">
import { RouterLink, useRouter } from 'vue-router';
import axios from "axios";
import { useAuthStore } from "@/stores/AuthStore";
import { ref } from "vue";

const userStore = useAuthStore();
const currentUser = () => userStore.user;

const route = useRouter();

const handleLogout = async () => {
    axios.post('/api/logout', { userId: currentUser().id }).then(() => {
        userStore.logout();
        route.push("/login");
    });

}

const isUserDropdownOpen = ref(false);
const isMobileMenuOpen = ref(false);

</script>

<style></style>