import axios from 'axios';
import {defineStore} from 'pinia';

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
        login() {
            this.isAuthenticated = true;
        },
        logout() {
            this.isAuthenticated = false;
        },
        // logout() {
        //     return new Promise((resolve, reject) => {
        //         axios.get( `/logout`,{headers: this.header})
        //             .then
        //             (
        //                 (response) => {
        //                     this.updateLogged(false);
        //                     this.updateToken(null);
        //                     resolve(response.data.data);
        //                 },
        //             )
        //             .catch(error => {
        //                 reject(error.response.data.data.errors);
        //             })
        //     });
        // },
    }
})
