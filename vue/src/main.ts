import "./index.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import axios from "./axios"; // Importa tu instancia personalizada de Axios
import App from "./App.vue";
import router from "./router";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

const app = createApp(App);

app.use(router);
app.use(createPinia());

app.config.globalProperties.$axios = axios; // Asigna la instancia personalizada de Axios a una propiedad global

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'MLT6fP26efdpVQggeLuRRhR0TmNdGRQB',
    wsHost: window.location.hostname,
    wsPort: 6001,
    cluster: "mt1",
    forceTLS: false,
    disableStats: true,
    authEndpoint: 'http://localhost:8000/api/broadcasting/auth',
    auth: {
        headers: {
            Authorization: 'Bearer '+localStorage.getItem("token")
        }
    }
});

app.mount("#app");
