import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import router from '@/router';
import App from '@/App.vue';

// createApp(Main).mount('#app');

const app = createApp(App);
const pinia = createPinia();

app.config.globalProperties.$url_api = (window.location.protocol + '//' + window.location.host + '/api/');

pinia.use(piniaPluginPersistedstate);
pinia.use(({ store }) => { store.$global = app.config.globalProperties });

app.use(pinia)
app.use(router)
app.mount('#app')