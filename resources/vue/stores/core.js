import axios from 'axios';
import { defineStore } from 'pinia';
import { useAuthStore } from '@/stores/auth';
import { getURLAPI } from '@/helpers/utils';
import { useRouter } from 'vue-router';

export const useCoreStore = defineStore('core', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        isLoading: false,
    }),
    actions: {
        async fetchUser() {
            const url = getURLAPI() + `/user/me`;
            const authStore = useAuthStore();
            const routerw = useRouter();
            if (authStore.getHeader.Authorization) {
                try {
                    const response = await axios.get(url, {
                        headers: authStore.getHeader,
                    });
                    this.user = response.data.data.user;
                    localStorage.setItem('user', JSON.stringify(this.user));
                } catch (error) {
                    console.error('Error fetching user info:', error);
                    this.user = null;
                    authStore.updateIsAuthenticated(false);
                    authStore.updateToken(null);
                    routerw.push({ name: 'Login' });
                }
            } else {
                this.user = null;
                authStore.updateIsAuthenticated(false);
                authStore.updateToken(null);
                routerw.push({ name: 'Login' });
            }
        },

        // Weather functionality
        async fetchWeather(city, country = null) {
            const url = getURLAPI() + `/weather`;
            const authStore = useAuthStore();
            return new Promise((resolve, reject) => {
                axios.get(url, {
                    headers: authStore.getHeader,
                    params: { city, country },
                })
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error);
                });
            });
        },

    },
});
