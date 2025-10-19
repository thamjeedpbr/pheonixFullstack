<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar - Fixed at top -->
    <Navbar 
      :user="user" 
      @toggle-sidebar="sidebarOpen = !sidebarOpen"
      @logout="handleLogout"
    />

    <div class="flex pt-16">
      <!-- Sidebar -->
      <Sidebar 
        :open="sidebarOpen"
        :user="user"
        @close="sidebarOpen = false"
        @toggle-sidebar="sidebarOpen = !sidebarOpen"
      />

      <!-- Overlay for mobile when sidebar is open -->
      <div 
        v-if="sidebarOpen" 
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
      ></div>

      <!-- Main Content -->
      <main 
        class="flex-1 min-h-screen transition-all duration-300"
        :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'"
      >
        <div class="p-3 sm:p-4 md:p-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Navbar from '@/Components/Navbar.vue';
import Sidebar from '@/Components/Sidebar.vue';

const router = useRouter();
const authStore = useAuthStore();
const user = computed(() => authStore.user);

// State
const sidebarOpen = ref(true);

// Handle logout
const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

// Handle window resize
const handleResize = () => {
  if (window.innerWidth < 1024) {
    sidebarOpen.value = false;
  } else {
    sidebarOpen.value = true;
  }
};

// Responsive sidebar behavior
onMounted(() => {
  // Set initial state based on screen size
  handleResize();
  
  // Add resize listener
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
/* Ensure smooth scrolling */
main {
  scroll-behavior: smooth;
}

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
