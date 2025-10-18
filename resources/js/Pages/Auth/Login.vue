<template>
  <GuestLayout>
    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
        <p class="text-gray-600">Sign in to your account to continue</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-600">{{ errorMessage }}</p>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Username Field -->
        <div>
          <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
            Username
          </label>
          <input
            id="user_name"
            v-model="form.user_name"
            type="text"
            required
            autocomplete="username"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            :class="{ 'border-red-500': errors.user_name }"
            placeholder="Enter your username"
          />
          <p v-if="errors.user_name" class="mt-1 text-sm text-red-600">
            {{ errors.user_name }}
          </p>
        </div>

        <!-- Password Field -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
          </label>
          <div class="relative">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              required
              autocomplete="current-password"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
              :class="{ 'border-red-500': errors.password }"
              placeholder="Enter your password"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
              <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
            </button>
          </div>
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">
            {{ errors.password }}
          </p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
          <input
            id="remember"
            v-model="form.remember"
            type="checkbox"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
          />
          <label for="remember" class="ml-2 block text-sm text-gray-700">
            Remember me
          </label>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
        >
          <svg
            v-if="loading"
            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

// Form state
const form = reactive({
  user_name: '',
  password: '',
  remember: false,
});

// UI state
const loading = ref(false);
const showPassword = ref(false);
const errorMessage = ref('');
const errors = reactive({
  user_name: '',
  password: '',
});

// Handle login
const handleLogin = async () => {
  // Clear previous errors
  errorMessage.value = '';
  errors.user_name = '';
  errors.password = '';
  
  // Validate form
  if (!form.user_name) {
    errors.user_name = 'Username is required';
    return;
  }
  
  if (!form.password) {
    errors.password = 'Password is required';
    return;
  }
  
  if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters';
    return;
  }

  loading.value = true;

  try {
    const response = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      // Store authentication using Pinia store
      authStore.setAuth(data.data.token, data.data.user);
      
      // Redirect to dashboard using Vue Router
      router.push('/dashboard');
    } else {
      // Handle validation errors
      if (data.errors) {
        if (data.errors.user_name) {
          errors.user_name = Array.isArray(data.errors.user_name) 
            ? data.errors.user_name[0] 
            : data.errors.user_name;
        }
        if (data.errors.password) {
          errors.password = Array.isArray(data.errors.password) 
            ? data.errors.password[0] 
            : data.errors.password;
        }
      } else {
        errorMessage.value = data.message || 'Login failed. Please try again.';
      }
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.value = 'Network error. Please check your connection and try again.';
  } finally {
    loading.value = false;
  }
};
</script>
