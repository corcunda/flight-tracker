<template>
    <div>
        <div v-if="coreStore.isLoading" id="loader"><div class="load"></div></div>
        <header>
            <h1><router-link :to="{ name: 'Dashboard' }" class="btn-dashboard">Flight Tracker</router-link></h1>
            <div v-if="coreStore.user" ref="menu" class="user-info" :class="{'menu-open': isMenuOpen}" @click="toggleMenu">
                <div class="user-info-wrapper">
                    <span class="user-initials">{{ createInitials }}</span>
                    <span  class="ellipsis" :title="coreStore.user.email">{{ coreStore.user.email }}</span>
                </div>
                <div class="menu-wrapper">
                    <ul>
                        <li><router-link :to="{ name: 'Dashboard' }" class="btn-dashboard">Dashboard</router-link></li>
                        <li><router-link :to="{ name: 'Profile' }" class="btn-dashboard">Profile</router-link></li>
                        <li v-if="authStore.isAuthenticated"><a @click="logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>
    </div>
</template>
  
<script>
import { useCoreStore } from '@/stores/core';
import { useAuthStore } from '@/stores/auth';

export default {
    name: 'Header',
    setup() {
        const coreStore = useCoreStore();
        const authStore = useAuthStore();

        return { coreStore, authStore };
    },
    components: {
        
    },
    data() {
        return {
            isMenuOpen: false,
        };
    },
    computed: {
        createInitials() {
            // Get the full name from coreStore
            const fullName = this.coreStore.user.name;

            // Split the name into an array of words
            const nameParts = fullName.trim().split(' ');

            // If there's only one name, return the first two letters
            if (nameParts.length === 1) {
                return nameParts[0].slice(0, 2).toUpperCase();
            }

            // If there are two names, return the first letter of each name
            if (nameParts.length === 2) {
                return (nameParts[0][0] + nameParts[1][0]).toUpperCase();
            }

            // If there are more than two names, return the first letter of the first and last name
            return (nameParts[0][0] + nameParts[nameParts.length - 1][0]).toUpperCase();
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        toggleMenu() {
            this.isMenuOpen = !this.isMenuOpen; // Toggle menu state
        },
        handleClickOutside(event) {
            const menu = this.$refs.menu;
            if (menu && !menu.contains(event.target)) {
                this.isMenuOpen = false;
            }
        },
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
header h1,
header h1 a{
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
    position: relative;
    flex-direction: column;
    display: flex;
    align-items: end;
    padding: 10px;
    border: 1px solid transparent;
    border-radius: 40px;
    cursor: pointer;
    font-size: 14px;
    transition: all .3s ease;
}
.user-info:hover{
    border: 1px solid #ddd;
}
.user-info-wrapper{
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 2;
}
.user-info-wrapper span{
    position: relative;
}
.user-info-wrapper span.user-initials{
    width: 30px;
    height: 30px;
    background: #00b5ad;
    border-radius: 100%;
    font-weight: 300;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
    position: relative;
    right: 0;
    transition: all .3s ease;
}
.user-info-wrapper span.ellipsis{
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

.menu-wrapper{
    position: absolute;
    background: #fff;
    top: calc(100% - 30px);
    width: calc(100% + 2px);
    right: -1px;
    border: 1px solid #ddd;
    border-top: none;
    z-index: 1;
    padding: 30px 10px 10px 10px;
    border-radius: 0 0 10px 10px;
    box-shadow: 2px 2px 6px -3px rgba(0, 0, 0, .4);
    opacity: 0;
    visibility: hidden;
    transition: all .3s ease;
}
.menu-wrapper ul{
    position: relative;
    list-style: none;
    padding: 0;
    margin: 0;
}
.menu-wrapper ul li{
    position: relative;
}
.menu-wrapper ul li a{
    color: #333;
    padding: 3px;
    display: block;
    border-radius: 3px;
    transition: all .3s ease;
}
.menu-wrapper ul li a:hover{
    color: #000;
    background: #f1f1f1;
}


.user-info.menu-open{
    border: 1px solid #ddd;
}
.user-info.menu-open .menu-wrapper{
    opacity: 1;
    visibility: visible;
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


@media only screen and (max-width: 768px) {
    .user-info-wrapper span.ellipsis{
        display: none;
    }
    .menu-wrapper{
        width: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 50px 10px 10px 10px;
        top: 0;
    }
    .user-info.menu-open .user-info-wrapper span.user-initials{
        right: calc(50% + 6px);
    }
}
</style>