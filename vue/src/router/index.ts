import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import LoginView from "../views/Auth/Login.vue";
import auth from "./middlewares/authMiddleware";
import guest from "./middlewares/guestMiddleware";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: HomeView,
            beforeEnter: auth,
        },
        {
            path: "/register",
            name: "register",
            component: () => import("../views/Auth/Register.vue"),
            beforeEnter: guest,
        },
        {
            path: "/login",
            name: "login",
            component: LoginView,
            beforeEnter: guest,
        },
        {
            path: "/authorize/:provider/callback",
            name: "social-login",
            component: () => import("../views/Auth/SocialLogin.vue"),
            beforeEnter: guest,
        },
    ],
});

export default router;
