<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header with Breadcrumb -->
      <div class="mb-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <button
                @click="router.push('/orders')"
                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600"
              >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Orders
              </button>
            </li>
            <li v-if="form.section?.order">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/orders/${form.section.order.id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  {{ form.section.order.job_card_no }}
                </button>
              </div>
            </li>
            <li v-if="form.section">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/sections/${form.section.id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  Section {{ form.section.section_id }}
                </button>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ form.form_name }}</span>
              </div>
            </li>
          </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ form.form_name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Form details and production tracking</p>
          </div>
          <div class="flex gap-2">
            <button
              @click="editForm"
              class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Form
            </button>
            <button
              @click="confirmDelete"
              class="inline-flex items-center rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 shadow-sm hover:bg-red-100"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Content -->
      <div v-else class="space-y-6">
        <!-- Status and Info Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Status</label>
              <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold" :class="getStatusClass(form.form_status)">
                {{ formatStatus(form.form_status) }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Schedule Date</label>
              <p class="text-base font-semibold text-gray-900">{{ formatDate(form.schedule_date) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Expected Quantity</label>
              <p class="text-2xl font-bold text-blue-600">{{ form.excepted_qty || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Created</label>
              <p class="text-sm text-gray-700">{{ formatDateTime(form.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Form Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Machine Assignment Card -->
          <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
              </svg>
              Machine Assignment
            </h2>
            
            <div v-if="form.machines && form.machines.length > 0" class="space-y-3">
              <div v-for="machine in form.machines" :key="machine.id" class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex-shrink-0">
                  <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">{{ machine.machine_no }}</p>
                  <p class="text-sm text-gray-600">{{ machine.machine_name }}</p>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
              <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
              </svg>
              <p class="text-sm">No machine assigned</p>
            </div>
          </div>

          <!-- Operators Card -->
          <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              Assigned Operators
              <span v-if="form.users && form.users.length > 0" class="ml-auto inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                {{ form.users.length }} operator{{ form.users.length > 1 ? 's' : '' }}
              </span>
            </h2>
            
            <div v-if="form.users && form.users.length > 0" class="space-y-3 max-h-80 overflow-y-auto">
              <div v-for="operator in form.users" :key="operator.id" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-700 font-semibold text-sm">{{ operator.name.charAt(0) }}</span>
                  </div>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">{{ operator.name }}</p>
                  <p class="text-sm text-gray-600">{{ operator.user_name }}</p>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
              <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <p class="text-sm">No operators assigned</p>
            </div>
          </div>
        </div>

        <!-- Description Card -->
        <div v-if="form.description" class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Description
          </h2>
          <p class="text-gray-700 whitespace-pre-line">{{ form.description }}</p>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Form</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete form "{{ form.form_name }}"? 
                    <span v-if="form.form_status !== 'job_pending'" class="font-medium text-red-600">
                      This form has been started and cannot be deleted.
                    </span>
                    <span v-else>This action cannot be undone.</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
              <button
                v-if="form.form_status === 'job_pending'"
                @click="deleteForm"
                :disabled="deleting"
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
              >
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
              <button
                @click="showDeleteModal = false"
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
              >
                {{ form.form_status !== 'job_pending' ? 'Close' : 'Cancel' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
  id: {
    type: String,
    required: true
  }
});

const router = useRouter();
const form = ref({});
const loading = ref(true);
const deleting = ref(false);
const showDeleteModal = ref(false);

const getStatusClass = (status) => {
  const classes = {
    'job_pending': 'bg-gray-100 text-gray-800',
    'make_ready': 'bg-yellow-100 text-yellow-800',
    'job_started': 'bg-green-100 text-green-800',
    'paused': 'bg-orange-100 text-orange-800',
    'stopped': 'bg-red-100 text-red-800',
    'job_completed': 'bg-blue-100 text-blue-800',
    'quality_verified': 'bg-purple-100 text-purple-800',
    'line_cleared': 'bg-teal-100 text-teal-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatStatus = (status) => {
  if (!status) return 'Unknown';
  return status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const formatDateTime = (dateTime) => {
  if (!dateTime) return 'N/A';
  return new Date(dateTime).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchForm = async () => {
  loading.value = true;
  
  try {
    const response = await axios.get(`/api/forms/${props.id}`);
    
    if (response.data.success) {
      form.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching form:', error);
    alert('Failed to load form');
    goBack();
  } finally {
    loading.value = false;
  }
};

const editForm = () => {
  router.push(`/forms/${props.id}/edit`);
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteForm = async () => {
  deleting.value = true;
  
  try {
    const response = await axios.delete(`/api/forms/${props.id}`);
    
    if (response.data.success) {
      // Go back to section page
      if (form.value.section?.id) {
        router.push(`/sections/${form.value.section.id}`);
      } else {
        router.push('/orders');
      }
    }
  } catch (error) {
    console.error('Error deleting form:', error);
    alert(error.response?.data?.message || 'Failed to delete form');
  } finally {
    deleting.value = false;
    showDeleteModal.value = false;
  }
};

const goBack = () => {
  if (form.value.section?.id) {
    router.push(`/sections/${form.value.section.id}`);
  } else {
    router.push('/orders');
  }
};

onMounted(() => {
  fetchForm();
});
</script>
