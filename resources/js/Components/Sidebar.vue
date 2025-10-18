<template>
  <aside
    class="fixed top-16 left-0 bottom-0 bg-white border-r border-gray-200 transition-all duration-300 z-30"
    :class="[
      open ? 'w-64' : 'w-16',
      'lg:block',
      open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
  >
    <div class="flex flex-col h-full">
      <!-- Navigation Menu -->
      <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-2">
          <!-- Dashboard -->
          <li>
            <router-link
              to="/dashboard"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path === '/dashboard' }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span v-if="open" class="font-medium">Dashboard</span>
            </router-link>
          </li>

          <!-- Users Section (always show for admins) -->
          <li v-if="hasPermission('user_menu_view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Administration
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <router-link
              to="/users"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/users') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span v-if="open" class="font-medium">Users</span>
            </router-link>
          </li>
        </ul>
      </nav>

      <!-- Collapse Button -->
      <div class="border-t border-gray-200 p-4 hidden lg:block">
        <button
          @click="$emit('toggle-sidebar')"
          class="w-full flex items-center justify-center px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200"
        >
          <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': !open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
          </svg>
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

// Props
const props = defineProps({
  open: {
    type: Boolean,
    default: true,
  },
  user: {
    type: Object,
    default: null,
  },
});

// Emits
defineEmits(['close', 'toggle-sidebar']);

// Check if user has permission
const hasPermission = (permission) => {
  if (!props.user || !props.user.permission) return false;
  
  const permissions = props.user.permission.permissions || [];
  return permissions.includes(permission);
};
</script>

<style scoped>
/* Custom scrollbar */
nav::-webkit-scrollbar {
  width: 6px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background: #e5e7eb;
  border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #d1d5db;
}
</style>
