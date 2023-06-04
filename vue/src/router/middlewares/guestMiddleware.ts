import { useAuthStore } from "@/stores/AuthStore";

export default function guest(to: Object, from: Object, next: Function) {
    if (!useAuthStore().isAuthenticated) {
        next();
    } else {
        next("/");
    }
}
