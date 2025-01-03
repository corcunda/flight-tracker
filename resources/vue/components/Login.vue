<template>
    <div>

        <div class="login-wrapper">
            <div class="field-wrapper">
                <label for="email">Email:</label>
                <input 
                    type="email" 
                    id="email" 
                    v-model="email" 
                    placeholder="Enter your email" 
                    required 
                />
            </div>
            <div class="field-wrapper">
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

    </div>
</template>

<script>
import { useCoreStore } from '@/stores/core';
import { useAuthStore } from '@/stores/auth';
export default {
    name: 'Login',
    setup() {
        const coreStore = useCoreStore();
        const authStore = useAuthStore();

        return { coreStore, authStore };
    },
    data() {
        return {
            email: 'admin@admin.com',
            password: 'admin',
        };
    },
    mounted() {
        
    },
    methods: {
        async login() {
            this.coreStore.isLoading = true;
            try {
                await this.authStore.login({ email: this.email, password: this.password });
                this.$router.push('/dashboard');
            } catch (error) {
                console.error('Login failed:', error.response.data);
                this.$showToast(null, 'E-mail or password incorrect', 'error', null);
            } finally {
                this.coreStore.isLoading = false;
            }
        },
    },
};
</script>
<style scoped>
.login-wrapper{
    background: #fff;
    border-radius: 6px;
    width: 340px;
    padding: 40px;
    box-shadow: 1px 1px 2px 0px rgba(0, 0, 0, .1);
    top: calc(50% - 80px);
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>