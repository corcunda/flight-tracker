import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/components/Login.vue';
import Dashboard from '@/components/Dashboard.vue';
import Profile from '@/components/Profile.vue';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
        meta: { title: 'Login' },
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { title: 'Dashboard', requiresAuth: true },
    },
    {
        path: '/profile',
        name: 'Profile',
        component: Profile,
        meta: { title: 'Profile', requiresAuth: true },
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});


router.beforeEach((to, from, next) => {
    const auth = useAuthStore();

    // Set the document title from route meta or fallback to a default title
    if (to.meta && to.meta.title) {
        document.title = to.meta.title;
    } else {
        document.title = 'Default Title'; // Fallback title
    }


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
