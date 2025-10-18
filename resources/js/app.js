import '../css/app.css';
import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import { useAuthStore } from './stores/auth';
import App from './App.vue';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

// Initialize auth
const authStore = useAuthStore();
authStore.initAuth();

// If authenticated, fetch latest user data
if (authStore.isAuthenticated) {
  authStore.fetchUser();
}

app.mount('#app');
