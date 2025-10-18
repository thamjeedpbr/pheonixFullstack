<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar 
      :user="user" 
      @toggle-sidebar="sidebarOpen = !sidebarOpen"
      @logout="handleLogout"
    />

    <!-- Sidebar and Main Content -->
    <div class="flex pt-16">
      <!-- Sidebar -->
      <Sidebar 
        :open="sidebarOpen" 
        :user="user"
        @close="sidebarOpen = false"
      />

      <!-- Main Content Area -->
      <main 
        class="flex-1 transition-all duration-300"
        :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'"
      >
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <slot />
        </div>
      </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden"
      @click="sidebarOpen = false"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Sidebar from '@/Components/Sidebar.vue';

// Props
const props = defineProps({
  user: {
    type: Object,
    default: null,
  },
});

// State
const sidebarOpen = ref(true);

// Handle logout
const handleLogout = async () => {
  try {
    const token = localStorage.getItem('auth_token');
    
    const response = await fetch('/api/auth/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    });

    // Clear local storage
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');

    // Redirect to login
    router.visit('/login', {
      replace: true,
    });
  } catch (error) {
    console.error('Logout error:', error);
    // Force redirect even on error
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    router.visit('/login', {
      replace: true,
    });
  }
};

// Responsive sidebar behavior
onMounted(() => {
  // Close sidebar on mobile by default
  if (window.innerWidth < 1024) {
    sidebarOpen.value = false;
  }

  // Handle window resize
  window.addEventListener('resize', () => {
    if (window.innerWidth < 1024) {
      sidebarOpen.value = false;
    }
  });
});
</script>

<style scoped>
/* Custom scrollbar for content area */
main::-webkit-scrollbar {
  width: 8px;
}

main::-webkit-scrollbar-track {
  background: #f1f1f1;
}

main::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

main::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
