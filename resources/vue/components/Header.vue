<template>
    <div>
        <div v-if="coreStore.isLoading" id="loader"><div class="load"></div></div>
        <header>
            <h1>Flight Tracker</h1>
            <div v-if="coreStore.user" class="user-info">
                <p>{{ coreStore.user.email }}</p>
                <button v-if="authStore.isAuthenticated" @click="logout">Logout</button>
            </div>
        </header>
        <WeatherWidget v-if="coreStore.user" />
    </div>
</template>
  
<script>
import { useCoreStore } from '@/stores/core';
import { useAuthStore } from '@/stores/auth';
import WeatherWidget from "@/components/WeatherWidget.vue";
export default {
    name: 'Header',
    setup() {
        const coreStore = useCoreStore();
        const authStore = useAuthStore();

        return { coreStore, authStore };
    },
    components: {
        WeatherWidget,
    },
    data() {
        return {
            
        };
    },
    methods: {
        logout() {
            let vm = this;
            this.coreStore.isLoading = true;
            vm.authStore.logout()
                .then(response => {
                    // console.log('OK', response);
                    vm.$router.push('/');
                })
                .catch(error => {
                    console.log('NOT OK => ', error.response);
                })
                .finally(() => {
                    this.coreStore.isLoading = false;
                });
        },
    },

};
</script>
<style scoped>
header{
    background: #fff;
    width: 100%;
    height: 80px;
    position: fixed;
    top: 0;
    left: 0;
    box-shadow: 0 0 6px 0 rgba(0,0,0,.4);
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 10;
}
header h1{
    color: #000;
    margin: 0;
}
header button{
    background: none;
    width: auto;
    color: #c932ff;
    color: #00b5ad;
    padding: 0;
    font-weight: 400;
    font-size: 12px;
}
header p{
    padding: 0;
    margin: 0;
    font-style: italic;
}
.user-info{
    flex-direction: column;
    display: flex;
    align-items: end;
}
#loader{
    position: fixed;
    width: 100vw;
    height: 100vw;
    top: 0;
    left: 0;
    z-index: 9999;
}
#loader .load {
    position: fixed;
    top: 0;
    left: 0;
    height: 4px;
    width: 100%;
    background-color: #eee;
    overflow: hidden;
}
#loader .load::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: linear-gradient(to right, #00b4ff, #ff9900, #00b4ff);
    animation: loaderAnimation 1.5s infinite;
}
@keyframes loaderAnimation {
    0% {
      transform: translateX(-100%);
    }
    50% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(100%);
    }
}
</style>