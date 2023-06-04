import "./index.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import axios from "./axios"; // Importa tu instancia personalizada de Axios
import App from "./App.vue";
import router from "./router";

const app = createApp(App);

app.use(router);
app.use(createPinia());

app.config.globalProperties.$axios = axios; // Asigna la instancia personalizada de Axios a una propiedad global

app.mount("#app");
