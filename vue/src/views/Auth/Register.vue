<template>
    <div class="flex h-screen">
        <div class="w-2/6 bg-teal-500 flex flex-col justify-center items-center">
            <h2 class="text-white text-4xl mb-4">New Here?</h2>
            <p class="text-white">Sign up and discover a great chat waiting for you!</p>
            <RouterLink to="/login" class="btn-pill bg-white text-black rounded-lg mt-6 py-4 px-24 font-bold">Sign In
            </RouterLink>
        </div>
        <div class="w-4/6 bg-white p-8">
            <div class="flex flex-col justify-center login-container">
                <div class="flex justify-center items-center">
                    <h1 class="text-4xl mt-4">Create an Account</h1>
                </div>
                <div class="flex justify-center items-center">
                    <p class="text-lg mt-4">Sign up using social networks</p>
                </div>
                <div class="flex mt-2 justify-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center"><i
                            class="fa-brands fa-facebook-f" style="color: #ffffff;"></i></div>
                    <div class="w-12 h-12 bg-red-500 rounded-full mx-2 flex items-center justify-center"><i
                            class="fa-brands fa-google" style="color: #ffffff;"></i></div>
                    <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center"><i
                            class="fa-brands fa-github" style="color: #ffffff;"></i></div>
                </div>
                <div class="px-32">
                    <div class="flex items-center mt-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <p class="text-gray-500 mx-4">OR</p>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>
                    <form @submit.prevent="handleSignUp">
                        <div class="flex">
                            <div class="w-1/2 pr-2">
                                <input type="text" placeholder="Name"
                                    class="input-rounded w-full mt-6 bg-gray-200 px-4 py-3" v-model="form.name">
                                <div class="relative mt-4">
                                    <input type="email" placeholder="Email"
                                        class="input-rounded w-full bg-gray-200 px-4 py-3 pr-10" v-model="form.email">
                                </div>
                            </div>
                            <div class="w-1/2 pl-2">
                                <input type="password" placeholder="Password"
                                    class="input-rounded w-full mt-6 bg-gray-200 px-4 py-3" v-model="form.password">
                                <div class="relative mt-4">
                                    <input type="password" placeholder="Confirm password"
                                        class="input-rounded w-full bg-gray-200 px-4 py-3 pr-10"
                                        v-model="form.password_confirmation">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn-pill bg-teal-500 text-white mt-6 py-4 px-24 font-bold">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>
  
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import axios from "axios";

const route = useRouter();

const getUser = async () => {
    await axios.get('/api/user').then((res) => {
        console.log(res);
    });
}

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const handleSignUp = async () => {
    await axios.post('http://localhost:8000/api/register', {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation,
    })

    route.push('/login');


};

onMounted(() => {
    getUser();
})
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
    font-size: 0.92rem;
    font-weight: 500;
}
</style>
  