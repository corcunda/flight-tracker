<template>
    <div class="main-wrapper">
        <Header></Header>
        <div class="component-wrapper">
            <RouterView />
        </div>
    </div>
</template>

<script>
import { useUserStore } from '@/stores/user';
import { useAuthStore } from '@/stores/auth';
import Header from "@/components/Header.vue";
import {RouterView} from "vue-router";
export default {
    name: 'App',
    setup() {
        const authStore = useAuthStore();
        const userStore = useUserStore();

        return { authStore, userStore };
        
    },
    components: {
        Header,
        RouterView,
    },
    watch: {
        propSelected(newValue, oldValue) {
            this.handleSelectionDropdown();
        },
    },
    async mounted() {
        if (this.authStore.getIsAuthenticated) {
            // If authenticated, fetch user info
            await this.userStore.fetchUser();
        } else {
            // If not authenticated, redirect to the login page
            this.$router.push('/');
        }
    },
};
</script>

<style>
body{
    background: #333;
}
header{
    background: #fff;
    width: 100%;
    height: 100px;
    position: fixed;
    top: 0;
    left: 0;
}
.component-wrapper{
    margin-top: 100px;
}
</style>
<style scoped>
h1 {
    color: #42b983;
}
</style>