<template>
    <div>
        <h1>Login</h1>
        <router-link to="/dashboard">Go to Dashboard</router-link>

        <button v-if="!auth.isAuthenticated" @click="login">Login</button>
        <button v-else @click="logout">Logout</button>
    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
export default {
    name: 'Login',
    setup() {
        const auth = useAuthStore();

        const login = () => {
            auth.login();
        };

        const logout = () => {
            auth.logout();
        };

        return { auth, login, logout };
    },
};
</script>