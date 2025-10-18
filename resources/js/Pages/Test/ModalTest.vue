<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">Modal Test Page</h1>
    
    <div class="space-y-4">
      <button
        @click="showTestModal = true"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
      >
        Test Simple Modal
      </button>

      <button
        @click="showUserFormModal = true"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
      >
        Test User Form Modal
      </button>

      <div class="mt-4 p-4 bg-gray-100 rounded">
        <p><strong>showTestModal:</strong> {{ showTestModal }}</p>
        <p><strong>showUserFormModal:</strong> {{ showUserFormModal }}</p>
      </div>
    </div>

    <!-- Simple Test Modal -->
    <div v-if="showTestModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 max-w-md w-full" @click.stop>
        <h2 class="text-xl font-bold mb-4">Test Modal</h2>
        <p class="mb-4">If you can see this, modals are working!</p>
        <button
          @click="showTestModal = false"
          class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
        >
          Close
        </button>
      </div>
    </div>

    <!-- User Form Modal -->
    <UserFormModal
      :show="showUserFormModal"
      :user="null"
      :permissions="testPermissions"
      :machines="testMachines"
      @close="showUserFormModal = false"
      @saved="handleSaved"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UserFormModal from '@/Components/UserFormModal.vue';

const showTestModal = ref(false);
const showUserFormModal = ref(false);

const testPermissions = ref([
  { id: 1, role_name: 'Admin' },
  { id: 2, role_name: 'Manager' },
]);

const testMachines = ref([
  { id: 1, machine_name: 'Machine 1' },
  { id: 2, machine_name: 'Machine 2' },
]);

const handleSaved = () => {
  console.log('User saved!');
  showUserFormModal.value = false;
  alert('User saved successfully!');
};
</script>
