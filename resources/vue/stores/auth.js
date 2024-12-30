import axios from 'axios';
import { defineStore } from 'pinia';
import { getURLAPI } from '@/helpers/utils';
import { useCoreStore } from '@/stores/core';

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

                        const coreStore = useCoreStore();
                        coreStore.user = response.data.data.user;
                        localStorage.setItem('user', JSON.stringify(coreStore.user));

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
                            localStorage.removeItem('user');

                            const coreStore = useCoreStore();
                            coreStore.user = null;
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
