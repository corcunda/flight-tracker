import axios from 'axios';
import { defineStore } from 'pinia';
import { getURLAPI } from '@/helpers/utils';
import { useUserStore } from '@/stores/user'; // Import user store

export const useAuthStore = defineStore('auth', {
    state: () => ({
        header: {
            'Content-Type': 'application/json;charset=UTF-8',
            'Access': 'application/json',
            'Accept': 'application/json',
            'Authorization': null
        },
        isAuthenticated: false,
    }),
    persist: true,
    getters: {
        getHeader: (state) => state.header,
        getIsAuthenticated: (state) => state.isAuthenticated,
    },
    actions: {
        updateHeader(data) {
            this.header = data;
        },
        updateIsAuthenticated(data) {
            this.isAuthenticated = data;
        },
        updateToken(data) {
            this.header.Authorization = `Bearer ${data}`;
        },
        login(credentials) {
            const url = getURLAPI() + `/login`;
            return new Promise((resolve, reject) => {
                axios.post(url, credentials, { headers: this.header })
                    .then((response) => {
                        // console.log('Login success',response.data);
                        // console.log('TOKEN',response.data.data.token);
                        const token = response.data.data.token;
                        this.updateToken(token);
                        this.updateIsAuthenticated(true);

                        const userStore = useUserStore();
                        userStore.user = response.data.data.user;

                        resolve(response.data);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        logout() {
            const url = getURLAPI() + `/logout`;
            return new Promise((resolve, reject) => {
                axios.get(url, { headers: this.header, })
                    .then( (response) => {
                            this.updateToken(null);
                            this.updateIsAuthenticated(false);
                            localStorage.removeItem('auth');

                            const userStore = useUserStore();
                            userStore.user = null;
                            resolve(response.data.data);
                        },
                    )
                    .catch(error => {
                        reject(error.response.data.data.errors);
                    })
            });
        },
    }
})
