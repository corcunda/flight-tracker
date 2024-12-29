<template>
    <div>
        <h1>Login</h1>
        <!-- <router-link to="/dashboard">Go to Dashboard</router-link> -->

        
        


        <!-- Email and Password input fields -->
        <div>
            <label for="email">Email:</label>
            <input 
                type="email" 
                id="email" 
                v-model="email" 
                placeholder="Enter your email" 
                required 
            />
        </div>
        <div>
            <label for="password">Password:</label>
            <input 
                type="password" 
                id="password" 
                v-model="password" 
                placeholder="Enter your password" 
                required 
            />
        </div>

        <button @click="login">Login</button>



    </div>
</template>

<script>
import { useAuthStore } from '@/stores/auth';
export default {
    name: 'Login',
    setup() {
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            email: 'joao@idwebstudio.com',
            password: 'admin',
        };
    },
    mounted() {
        
    },
    methods: {
        async login() {
            try {
                await this.authStore.login({ email: this.email, password: this.password });
                this.$router.push('/dashboard');
            } catch (error) {
                console.error('Login failed:', error.response.data);
            }
        },
    },
};
</script>