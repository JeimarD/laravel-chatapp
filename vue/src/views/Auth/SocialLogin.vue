<template>
    <div>
        <h1>Authenticating, please wait...</h1>
    </div>
</template>
  
<script setup lang="ts">
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/AuthStore';
import { onMounted } from 'vue';

const userStore = useAuthStore();

const route = useRouter();

const handleSocialAuth = async () => {
    try {
        const provider = route.currentRoute.value.params.provider;
        const response = await axios.get(`/api/authorize/${provider}/callback`, {
            params: {
                code: route.currentRoute.value.query.code
            }
        })

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

onMounted(() => {
    handleSocialAuth();
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
    font-size: 1.05rem;
    font-weight: 500;
}
</style>
  