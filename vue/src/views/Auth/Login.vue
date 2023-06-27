<template>
    <div class="flex h-screen">
        <div class="w-4/6 bg-white p-8">
            <img src="/logo.png" alt="Logo" class="h-8">
            <div class="flex flex-col justify-center login-container">
                <div class="flex justify-center items-center">
                    <h1 class="text-4xl mt-4">Login to Your Account</h1>
                </div>
                <div class="flex justify-center items-center">
                    <p class="text-lg mt-4">Login using social networks</p>
                </div>
                <div class="flex mt-2 justify-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center cursor-pointer"><i
                            class="fa-brands fa-facebook-f" style="color: #ffffff;"
                            @click="handleSocialAuth('facebook')"></i></div>
                    <div class="w-12 h-12 bg-red-500 rounded-full mx-2 flex items-center justify-center cursor-pointer"><i
                            class="fa-brands fa-google" style="color: #ffffff;" @click="handleSocialAuth('google')"></i>
                    </div>
                    <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center cursor-pointer"
                        @click="handleSocialAuth('github')"><i class="fa-brands fa-github" style="color: #ffffff;"></i>
                    </div>
                </div>
                <div class="px-32">
                    <div class="flex items-center mt-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <p class="text-gray-500 mx-4">OR</p>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>
                    <form @submit.prevent="handleLogin">
                        <input type="email" placeholder="Email" class="input-rounded w-full mt-6 bg-gray-200 px-4 py-4"
                            v-model="form.email">
                        <div class="relative mt-4">
                            <input type="password" placeholder="Password"
                                class="input-rounded w-full bg-gray-200 px-4 py-4 pr-10" v-model="form.password">
                        </div>
                        <div class="text-center">
                            <button class="btn-pill bg-teal-500 text-white mt-6 py-4 px-24 font-bold">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-2/6 bg-teal-500 flex flex-col justify-center items-center">
            <h2 class="text-white text-4xl mb-4">New Here?</h2>
            <p class="text-white">Sign up and discover a great chat waiting for you!</p>
            <RouterLink to="/register" class="btn-pill bg-white text-black rounded-lg mt-6 py-4 px-24 font-bold">Sign Up
            </RouterLink>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/AuthStore';

const userStore = useAuthStore();

const route = useRouter();

const form = ref({
    email: '',
    password: ''
})

const handleLogin = async () => {
    try {
        const response = await axios.post('/api/login', {
            email: form.value.email,
            password: form.value.password,
        });

        console.log(response.data);
        const token = response.data.access_token;
        localStorage.setItem('token', token);

        const userResponse = await axios.get('/api/user', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        userStore.login(userResponse.data);
        route.push('/');

        // Aquí puedes redirigir a la página principal u otra acción necesaria
    } catch (err) {
        console.log(err);
    }
};

const handleSocialAuth = async (provider: string) => {
    try {
        await axios.get(`/api/authorize/${provider}/redirect`).then((res) => {
            if (res.data.url) {
                window.location.href = res.data.url
            }
        })
    } catch (err) {
        console.log(err);
    }
}
</script>
  
<style scoped>
.login-container {
    height: 80vh;
}

.btn-pill {
    border-radius: 2rem;
}

.fa-brands {
    font-size: 1.4rem;
}

.input-rounded::placeholder {
    font-size: 1.05rem;
    font-weight: 500;
}
</style>
  