import { useAuthStore } from "@/stores/AuthStore";

export default function auth(to: object, from: object, next: Function) {
    if (useAuthStore().isAuthenticated) {
        next();
    } else {
        next("/login");
    }
}
