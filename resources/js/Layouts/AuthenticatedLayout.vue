<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar 
      :user="user" 
      @toggle-sidebar="sidebarOpen = !sidebarOpen"
      @logout="handleLogout"
    />

    <div class="flex">
      <!-- Sidebar -->
      <Sidebar 
        :open="sidebarOpen"
        :user="user"
        @close="sidebarOpen = false"
      />

      <!-- Main Content -->
      <main 
        class="flex-1 p-6 transition-all duration-300"
        :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'"
      >
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
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
