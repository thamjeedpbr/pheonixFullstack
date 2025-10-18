<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="w-full py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-xl">P</span>
          </div>
          <span class="text-2xl font-bold text-gray-800">Phoenix</span>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md">
        <slot />
      </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto text-center text-sm text-gray-600">
        <p>&copy; {{ currentYear }} Phoenix Manufacturing System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>

<style scoped>
/* Add custom styles if needed */
</style>
