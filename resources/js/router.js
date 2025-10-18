import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './stores/auth';

// Import Pages
import Login from './Pages/Auth/Login.vue';
import Dashboard from './Pages/Dashboard.vue';
import UsersIndex from './Pages/Users/Index.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresGuest: true },
  },
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/users',
    name: 'users.index',
    component: UsersIndex,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});

export default router;
