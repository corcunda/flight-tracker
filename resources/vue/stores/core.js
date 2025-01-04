import axios from 'axios';
import { defineStore } from 'pinia';
import { useAuthStore } from '@/stores/auth';
import { getURLAPI } from '@/helpers/utils';
import { useRouter } from 'vue-router';

export const useCoreStore = defineStore('core', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        isLoading: false,
        config: {},
        simulationStarted: false,
    }),
    persist: true,
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

        async updateUser(updatedUser) {
            const url = getURLAPI() + `/user`;
            const authStore = useAuthStore();

            try {
                const response = await axios.put(url, updatedUser, {
                    headers: authStore.getHeader,
                });

                // Assuming the response contains the updated user data
                this.user = response.data.data.user;
                localStorage.setItem('user', JSON.stringify(this.user));

                return response.data;
            } catch (error) {
                console.error('Error updating user info:', error);
                throw error;
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

        // Config information
        async fetchConfig() {
            const url = getURLAPI() + `/config`;
            const authStore = useAuthStore();
            return new Promise((resolve, reject) => {
                axios.get(url, {
                    headers: authStore.getHeader,
                })
                .then((response) => {
                    this.config = response.data.data;
                    localStorage.setItem('config', JSON.stringify(this.config));
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error);
                });
            });
        },

        // Start the simulation
        simulateStart() {
            const url = getURLAPI() + `/flight/simulate/start`;
            const authStore = useAuthStore();
            return new Promise((resolve, reject) => {
                axios.post(url, {}, { headers: authStore.getHeader })
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error);
                });
            });
        }

    },
});
