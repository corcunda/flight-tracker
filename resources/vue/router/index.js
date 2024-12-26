import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/components/Login.vue';
import Dashboard from '@/components/Dashboard.vue';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});


router.beforeEach((to, from, next) => {
    const auth = useAuthStore();
    // Redirect logged-in users away from Login page
    if (to.name === 'Login' && auth.isAuthenticated) {
        next({ name: 'Dashboard' });
    }
    // Redirect non-authenticated users away from Dashboard
    else if (to.meta.requiresAuth && !auth.isAuthenticated) {
        next({ name: 'Login' });
    }
    // Allow the navigation
    else {
        next();
    }
});


export default router;
