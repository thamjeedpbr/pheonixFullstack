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
            <a
              href="/dashboard"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/dashboard') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span v-if="open" class="font-medium">Dashboard</span>
            </a>
          </li>

          <!-- Production Section -->
          <li v-if="hasPermission('job_menu_view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Production
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>
            
            <!-- Orders -->
            <a
              href="/orders"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/orders') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span v-if="open" class="font-medium">Orders</span>
            </a>

            <!-- Forms/Jobs -->
            <a
              href="/forms"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/forms') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span v-if="open" class="font-medium">Production Jobs</span>
            </a>
          </li>

          <!-- Master Data Section -->
          <li v-if="hasPermission('machine_master_view') || hasPermission('material_master_view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Master Data
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <!-- Machines -->
            <a
              v-if="hasPermission('machine_master_view')"
              href="/machines"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/machines') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
              </svg>
              <span v-if="open" class="font-medium">Machines</span>
            </a>

            <!-- Materials -->
            <a
              v-if="hasPermission('material_master_view')"
              href="/materials"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/materials') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              <span v-if="open" class="font-medium">Materials</span>
            </a>
          </li>

          <!-- Quality Control Section -->
          <li v-if="hasPermission('quality_menu_view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Quality
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <a
              href="/quality"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/quality') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="open" class="font-medium">QC Verification</span>
            </a>
          </li>

          <!-- Users & Roles Section -->
          <li v-if="hasPermission('user_menu_view') || hasPermission('role_menu_view')">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Administration
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <!-- Users -->
            <a
              v-if="hasPermission('user_menu_view')"
              href="/users"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/users') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span v-if="open" class="font-medium">Users</span>
            </a>

            <!-- Roles -->
            <a
              v-if="hasPermission('role_menu_view')"
              href="/roles"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/roles') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
              </svg>
              <span v-if="open" class="font-medium">Roles</span>
            </a>
          </li>

          <!-- Reports Section -->
          <li>
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase" v-if="open">
              Reports
            </div>
            <div v-else class="my-2 border-t border-gray-200"></div>

            <a
              href="/reports"
              class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200"
              :class="{ 'bg-blue-50 text-blue-600': isActive('/reports') }"
            >
              <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              <span v-if="open" class="font-medium">Reports</span>
            </a>
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
import { usePage } from '@inertiajs/vue3';

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

// Check if current route is active
const isActive = (path) => {
  return window.location.pathname === path || window.location.pathname.startsWith(path + '/');
};

// Check if user has permission
const hasPermission = (permission) => {
  if (!props.user || !props.user.permission) return false;
  
  // Get permissions array
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
