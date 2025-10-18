<template>
  <nav class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 z-30">
    <div class="h-full px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-full">
        <!-- Left Side: Hamburger + Logo -->
        <div class="flex items-center space-x-4">
          <!-- Hamburger Menu -->
          <button
            @click="$emit('toggle-sidebar')"
            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Logo and Title -->
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold">P</span>
            </div>
            <span class="hidden sm:block text-xl font-bold text-gray-800">Phoenix</span>
          </div>
        </div>

        <!-- Right Side: Notifications + User Menu -->
        <div class="flex items-center space-x-3 sm:space-x-4">
          <!-- Notifications -->
          <button
            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 relative"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <!-- Notification Badge -->
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>

          <!-- User Menu -->
          <div class="relative">
            <button
              @click="userMenuOpen = !userMenuOpen"
              class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
            >
              <!-- User Avatar -->
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">
                  {{ userInitials }}
                </span>
              </div>
              <!-- User Name (hidden on mobile) -->
              <span class="hidden md:block text-sm font-medium text-gray-700">
                {{ user?.name || 'User' }}
              </span>
              <!-- Dropdown Arrow -->
              <svg
                class="w-4 h-4 text-gray-500 transition-transform duration-200"
                :class="{ 'rotate-180': userMenuOpen }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-150"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="userMenuOpen"
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
              >
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-200">
                  <p class="text-sm font-medium text-gray-900">{{ user?.name }}</p>
                  <p class="text-xs text-gray-500">{{ user?.phone_no || user?.user_name }}</p>
                  <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full"
                    :class="{
                      'bg-blue-100 text-blue-800': user?.user_type === 'ADMIN',
                      'bg-green-100 text-green-800': user?.user_type === 'SUPER_WISER',
                      'bg-gray-100 text-gray-800': user?.user_type === 'OPERATOR'
                    }"
                  >
                    {{ user?.user_type }}
                  </span>
                </div>

                <!-- Menu Items -->
                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                  </div>
                </a>

                <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                  </div>
                </a>

                <hr class="my-2" />

                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-150"
                >
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                  </div>
                </button>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// Props
const props = defineProps({
  user: {
    type: Object,
    default: null,
  },
});

// Emits
const emit = defineEmits(['toggle-sidebar', 'logout']);

// State
const userMenuOpen = ref(false);

// Computed
const userInitials = computed(() => {
  if (!props.user?.name) return 'U';
  return props.user.name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
});

// Methods
const handleLogout = () => {
  userMenuOpen.value = false;
  emit('logout');
};

// Click outside to close menu
const closeMenuOnClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    userMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeMenuOnClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', closeMenuOnClickOutside);
});
</script>

<style scoped>
/* Custom styles if needed */
</style>
