<template>
    <div>
        <header>
            <button v-if="authStore.isAuthenticated" @click="logout">Logout</button>
        </header>
    </div>
</template>
  
<script>
import { useAuthStore } from '@/stores/auth'; // Adjust the path to your store
export default {
    name: 'Header',
    setup() {
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            
        };
    },
    methods: {
        logout() {
            let vm = this;
            vm.authStore.logout()
                .then(response => {
                    // console.log('OK', response);
                    vm.$router.push('/');
                })
                .catch(error => {
                    console.log('NOT OK => ', error.response);
                })
                .finally(() => {
                });
        },
    },

};
</script>