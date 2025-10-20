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

          <!-- Orders -->
          <li>
            <router-link
              to="/orders"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/orders') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span v-if="open" class="font-medium">Orders</span>
            </router-link>
          </li>

          <!-- Administration Section -->
          <li v-if="hasPermission('user-menu.view') || hasPermission('machine-master.view') || hasPermission('material-master.view') || hasPermission('machine-type.view') || hasPermission('process.view') || hasPermission('department.view') || hasPermission('shift.view') || hasPermission('section.view') || hasPermission('status-menu.view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Administration
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <!-- Users -->
            <router-link
              v-if="hasPermission('user-menu.view')"
              to="/users"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/users') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span v-if="open" class="font-medium">Users</span>
            </router-link>

            <!-- Machines -->
            <router-link
              v-if="hasPermission('machine-master.view')"
              to="/machines"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/machines') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span v-if="open" class="font-medium">Machines</span>
            </router-link>

            <!-- Materials -->
            <router-link
              v-if="hasPermission('material-master.view')"
              to="/materials"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/materials') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <span v-if="open" class="font-medium">Materials</span>
            </router-link>

            <!-- Machine Types -->
            <router-link
              v-if="hasPermission('machine-type.view')"
              to="/machine-types"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/machine-types') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
              </svg>
              <span v-if="open" class="font-medium">Machine Types</span>
            </router-link>

            <!-- Processes -->
            <router-link
              v-if="hasPermission('process.view')"
              to="/processes"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/processes') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span v-if="open" class="font-medium">Processes</span>
            </router-link>

            <!-- Departments -->
            <router-link
              v-if="hasPermission('department.view')"
              to="/departments"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/departments') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span v-if="open" class="font-medium">Departments</span>
            </router-link>

            <!-- Shifts -->
            <router-link
              v-if="hasPermission('shift.view')"
              to="/shifts"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/shifts') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="open" class="font-medium">Shifts</span>
            </router-link>

            <!-- Sections -->
            <router-link
              v-if="hasPermission('section.view')"
              to="/sections"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/sections') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
              </svg>
              <span v-if="open" class="font-medium">Sections</span>
            </router-link>

            <!-- Statuses -->
            <router-link
              v-if="hasPermission('status-menu.view')"
              to="/statuses"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': $route.path.startsWith('/statuses') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="open" class="font-medium">Statuses</span>
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

// Check if user has permission using new Spatie format
const hasPermission = (permission) => {
  if (!props.user) return false;
  
  // Check in permissions array (Spatie format)
  const permissions = props.user.permissions || props.user.permission_list || [];
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
