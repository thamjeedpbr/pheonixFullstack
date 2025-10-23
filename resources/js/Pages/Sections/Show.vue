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
            <li v-if="section.order">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/orders/${section.order.id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  {{ section.order.job_card_no }}
                </button>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Section {{ section.section_id }}</span>
              </div>
            </li>
          </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Section {{ section.section_id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Manage forms for this section</p>
          </div>
          <button
            @click="showAddFormModal = true"
            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Form
          </button>
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
        <!-- Section Info Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Section ID</label>
              <p class="text-2xl font-bold text-blue-600">{{ section.section_id }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Status</label>
              <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold"
                :class="section.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                {{ section.status ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Total Forms</label>
              <p class="text-2xl font-bold text-blue-600">{{ section.forms_count || 0 }}</p>
            </div>
          </div>
        </div>

        <!-- Forms Cards Grid -->
        <div>
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Forms</h2>
          
          <!-- Forms Grid -->
          <div v-if="section.forms && section.forms.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="form in section.forms"
              :key="form.id"
              @click="viewForm(form.id)"
              class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer overflow-hidden border border-gray-200 hover:border-blue-300 group"
            >
              <!-- Card Header with Status Badge -->
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                    {{ form.form_name || `Form #${form.id}` }}
                  </h3>
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" :class="getStatusClass(form.form_status)">
                    {{ formatStatus(form.form_status) }}
                  </span>
                </div>
              </div>

              <!-- Card Body -->
              <div class="px-6 py-4 space-y-3">
                <!-- Machine -->
                <div v-if="form.machines && form.machines.length > 0" class="flex items-center text-sm">
                  <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                  </svg>
                  <span class="text-gray-700 font-medium">{{ form.machines[0].machine_no }}</span>
                </div>

                <!-- Operators -->
                <div v-if="form.users && form.users.length > 0" class="flex items-center text-sm">
                  <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <span class="text-gray-700">{{ form.users.length }} Operator{{ form.users.length > 1 ? 's' : '' }}</span>
                </div>

                <!-- Schedule Date -->
                <div v-if="form.schedule_date" class="flex items-center text-sm">
                  <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-gray-700">{{ formatDate(form.schedule_date) }}</span>
                </div>

                <!-- Description -->
                <div v-if="form.description" class="text-sm text-gray-600 line-clamp-2 pt-2 border-t border-gray-100">
                  {{ form.description }}
                </div>

                <!-- Quantities -->
                <div v-if="form.good_quantity || form.total_quantity" class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                  <div v-if="form.good_quantity">
                    <span class="text-gray-500">Good Qty:</span>
                    <span class="ml-1 font-semibold text-green-600">{{ form.good_quantity }}</span>
                  </div>
                  <div v-if="form.total_quantity">
                    <span class="text-gray-500">Total Qty:</span>
                    <span class="ml-1 font-semibold text-blue-600">{{ form.total_quantity }}</span>
                  </div>
                </div>
              </div>

              <!-- Card Footer -->
              <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>{{ formatDateTime(form.created_at) }}</span>
                  <svg class="w-5 h-5 text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No forms yet</h3>
            <p class="mt-2 text-sm text-gray-500">Get started by creating a new form for this section.</p>
            <button
              @click="showAddFormModal = true"
              class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create First Form
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Form Modal -->
    <teleport to="body">
      <div v-if="showAddFormModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAddFormModal = false"></div>
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Form</h3>
                <p class="mt-2 text-sm text-gray-500">
                  You'll be redirected to the form creation page for this section.
                </p>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
              <button
                @click="goToCreateForm"
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm"
              >
                Continue
              </button>
              <button
                @click="showAddFormModal = false"
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm"
              >
                Cancel
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
const section = ref({});
const loading = ref(true);
const showAddFormModal = ref(false);

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
    year: 'numeric'
  });
};

const fetchSection = async () => {
  loading.value = true;
  
  try {
    const response = await axios.get(`/api/sections/${props.id}`);
    
    if (response.data.success) {
      section.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching section:', error);
    alert('Failed to load section');
    router.push('/orders');
  } finally {
    loading.value = false;
  }
};

const viewForm = (formId) => {
  router.push(`/forms/${formId}`);
};

const goToCreateForm = () => {
  router.push({
    path: '/forms/create',
    query: { section_id: props.id }
  });
};

onMounted(() => {
  fetchSection();
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
