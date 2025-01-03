<template>
    <div class="main-wrapper">
        <Header></Header>
        <div class="component-wrapper">
            <RouterView />
        </div>
    </div>
</template>

<script>
import { useCoreStore } from '@/stores/core';
import { useAuthStore } from '@/stores/auth';
import Header from "@/components/Header.vue";
import {RouterView} from "vue-router";
export default {
    name: 'App',
    setup() {
        const authStore = useAuthStore();
        const coreStore = useCoreStore();

        return { authStore, coreStore };
        
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
            await this.coreStore.fetchUser();
        } else {
            this.$router.push('/');
        }
    },
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
body, html{
    margin: 0;
    padding: 0;
    /*height: 100%;*/
    height: auto;
}
body{
    background: #f4f4f4;
    margin: 0;
    padding: 0;
    font-family: "Roboto", serif;
    font-weight: 400;
    font-style: normal;
    font-size: 16px;
    color: #333;
}

.component-wrapper{
    margin: 100px 20px 0 20px;
    min-height: calc(100vh - 100px);
    position: relative;
    z-index: 5;
}
.field-wrapper{
    padding: 0 0 10px 0;
}
label{
    margin-bottom: 5px;
    display: inline-block;
}
input{
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    border: 1px solid #eee;
    border-radius: 6px;
}
button{
    background: #00b4ff;
    background: #c932ff;
    background: #00b5ad;
    border: 1px solid transparent;
    border-radius: 4px;
    padding: 10px 20px;
    width: 100%;
    box-sizing: border-box;
    color: #fff;
    font-weight: 600;
    font-family: "Roboto", serif;
    cursor: pointer;
}
h1 {
    color: #00b4ff;
    color: #c932ff;
    color: #00b5ad;
}
.ui.toast-container.top.right,
.ui.toast-container.top.left{
    top: 85px !important;
}
</style>
<style scoped>

</style>