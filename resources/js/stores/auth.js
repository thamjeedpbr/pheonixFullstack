import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null);
  const token = ref(localStorage.getItem('auth_token') || null);
  const isAuthenticated = computed(() => !!token.value && !!user.value);

  // Actions
  const setAuth = (authToken, userData) => {
    token.value = authToken;
    user.value = userData;
    localStorage.setItem('auth_token', authToken);
    localStorage.setItem('user', JSON.stringify(userData));
    // Set cookie for server-side access
    document.cookie = `auth_token=${authToken}; path=/; max-age=604800; SameSite=Lax`;
  };

  const clearAuth = () => {
    token.value = null;
    user.value = null;
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    // Clear cookie
    document.cookie = 'auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
  };

  const fetchUser = async () => {
    if (!token.value) return;
    
    try {
      const response = await fetch('/api/auth/me', {
        headers: {
          'Authorization': `Bearer ${token.value}`,
          'Accept': 'application/json',
        },
      });

      if (response.ok) {
        const data = await response.json();
        user.value = data.data;
        localStorage.setItem('user', JSON.stringify(data.data));
      } else {
        clearAuth();
      }
    } catch (error) {
      console.error('Failed to fetch user:', error);
      clearAuth();
    }
  };

  const logout = async () => {
    if (token.value) {
      try {
        await fetch('/api/auth/logout', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token.value}`,
            'Accept': 'application/json',
          },
        });
      } catch (error) {
        console.error('Logout error:', error);
      }
    }
    clearAuth();
  };

  // Initialize user from localStorage
  const initAuth = () => {
    const storedUser = localStorage.getItem('user');
    if (storedUser && token.value) {
      try {
        user.value = JSON.parse(storedUser);
      } catch (error) {
        clearAuth();
      }
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    setAuth,
    clearAuth,
    fetchUser,
    logout,
    initAuth,
  };
});
