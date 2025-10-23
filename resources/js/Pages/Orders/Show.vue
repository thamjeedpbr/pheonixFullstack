<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Header -->
      <div class="mb-6">
        <button
          @click="goBack"
          class="mb-4 inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
        >
          <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Orders
        </button>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Order Details</h1>
            <p class="mt-1 text-sm text-gray-600">Manage order sections and forms</p>
          </div>
          <div class="flex gap-2">
            <button
              @click="editOrder"
              class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Order
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
        <!-- Order Information Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100">
          <h2 class="text-lg font-semibold text-blue-900 mb-4">Order Information</h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Job Card Number -->
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Job Card Number</label>
              <p class="text-2xl font-bold text-blue-600">{{ order.job_card_no }}</p>
            </div>

            <!-- Status & Priority -->
            <div class="flex gap-4">
              <div>
                <label class="block text-sm font-medium text-blue-900 mb-1">Status</label>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold" :class="getStatusClass(order.status)">
                  {{ order.status_label }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-blue-900 mb-1">Priority</label>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold" :class="getPriorityClass(order.priority)">
                  {{ order.priority_label }}
                </span>
              </div>
            </div>

            <!-- Client Name -->
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Client Name</label>
              <p class="text-base font-medium text-gray-900">{{ order.client_name }}</p>
            </div>

            <!-- Delivery Date -->
            <div>
              <label class="block text-sm font-medium text-blue-900 mb-1">Delivery Date</label>
              <p class="text-base font-medium text-gray-900">{{ order.delivery_date_formatted }}</p>
              <p v-if="order.days_until_delivery !== null" class="text-sm mt-1 font-medium" :class="order.days_until_delivery < 0 ? 'text-red-600' : 'text-green-600'">
                {{ order.days_until_delivery < 0 ? `⚠️ Overdue by ${Math.abs(order.days_until_delivery)} days` : `✓ ${order.days_until_delivery} days remaining` }}
              </p>
            </div>

            <!-- Title -->
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-blue-900 mb-1">Title</label>
              <p class="text-base font-medium text-gray-900">{{ order.title }}</p>
            </div>

            <!-- Description -->
            <div v-if="order.description" class="sm:col-span-2 lg:col-span-3">
              <label class="block text-sm font-medium text-blue-900 mb-1">Description</label>
              <p class="text-base text-gray-700 whitespace-pre-line">{{ order.description }}</p>
            </div>
          </div>
        </div>

        <!-- Sections Cards -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Sections</h2>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
                {{ order.sections_count || 0 }} section{{ order.sections_count !== 1 ? 's' : '' }}
              </span>
              <button
                @click="showAddSectionModal = true"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
              >
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Section
              </button>
            </div>
          </div>

          <!-- Sections Grid -->
          <div v-if="order.sections && order.sections.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="section in order.sections"
              :key="section.id"
              class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 hover:border-blue-300 group"
            >
              <!-- Card Header -->
              <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h3 class="text-xl font-bold text-indigo-900">
                    Section {{ section.section_id }}
                  </h3>
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                    :class="section.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                    {{ section.status ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </div>

              <!-- Card Body -->
              <div class="px-6 py-5">
                <div class="text-center">
                  <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <p class="text-3xl font-bold text-blue-600">{{ section.forms_count || 0 }}</p>
                  <p class="text-sm text-gray-600 mt-1">Form{{ section.forms_count !== 1 ? 's' : '' }}</p>
                </div>
              </div>

              <!-- Card Footer with Actions -->
              <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex items-center justify-between gap-2">
                <button
                  @click="viewSection(section.id)"
                  class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                >
                  <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  View Forms
                </button>
                <button
                  @click="deleteSection(section.id)"
                  class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition-colors"
                  title="Delete Section"
                >
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No sections yet</h3>
            <p class="mt-2 text-sm text-gray-500">Get started by creating sections for this order.</p>
            <button
              @click="showAddSectionModal = true"
              class="mt-6 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create First Section
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Section Modal -->
    <teleport to="body">
      <div v-if="showAddSectionModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAddSectionModal = false"></div>
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Section</h3>
                <div class="mt-4">
                  <label for="section-id" class="block text-sm font-medium text-gray-700 text-left mb-2">
                    Section ID <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="text"
                    id="section-id"
                    v-model="newSection.section_id"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="Enter section ID (e.g., SEC-001)"
                  />
                  <p v-if="sectionError" class="mt-2 text-sm text-red-600">{{ sectionError }}</p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
              <button
                @click="createSection"
                :disabled="creatingSection || !newSection.section_id"
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ creatingSection ? 'Creating...' : 'Create Section' }}
              </button>
              <button
                @click="showAddSectionModal = false"
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

    <!-- Delete Order Confirmation Modal -->
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Order</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete order "{{ order.job_card_no }}"? 
                    <span v-if="order.sections_count > 0" class="font-medium text-red-600">
                      This order has {{ order.sections_count }} section(s) and cannot be deleted.
                    </span>
                    <span v-else>This action cannot be undone.</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
              <button
                v-if="order.sections_count === 0"
                @click="deleteOrder"
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
                {{ order.sections_count > 0 ? 'Close' : 'Cancel' }}
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

const order = ref({});
const loading = ref(true);
const deleting = ref(false);
const showDeleteModal = ref(false);
const showAddSectionModal = ref(false);
const creatingSection = ref(false);
const sectionError = ref('');

const newSection = ref({
  section_id: '',
  status: true
});

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-gray-100 text-gray-800',
    'in_progress': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getPriorityClass = (priority) => {
  const classes = {
    'low': 'bg-gray-100 text-gray-700',
    'normal': 'bg-blue-100 text-blue-700',
    'high': 'bg-orange-100 text-orange-700',
    'urgent': 'bg-red-100 text-red-700'
  };
  return classes[priority] || 'bg-gray-100 text-gray-700';
};

const fetchOrder = async () => {
  loading.value = true;
  
  try {
    const response = await axios.get(`/api/orders/${props.id}`);
    
    if (response.data.success) {
      order.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching order:', error);
    alert('Failed to load order');
    goBack();
  } finally {
    loading.value = false;
  }
};

const createSection = async () => {
  if (!newSection.value.section_id) {
    sectionError.value = 'Section ID is required';
    return;
  }

  creatingSection.value = true;
  sectionError.value = '';
  
  try {
    const response = await axios.post('/api/sections', {
      section_id: newSection.value.section_id,
      order_id: props.id,
      status: true
    });
    
    if (response.data.success) {
      showAddSectionModal.value = false;
      newSection.value.section_id = '';
      // Refresh order to show new section
      await fetchOrder();
    }
  } catch (error) {
    console.error('Error creating section:', error);
    sectionError.value = error.response?.data?.message || 'Failed to create section';
  } finally {
    creatingSection.value = false;
  }
};

const deleteSection = async (sectionId) => {
  if (!confirm('Are you sure you want to delete this section? This cannot be undone if the section has forms.')) {
    return;
  }

  try {
    const response = await axios.delete(`/api/sections/${sectionId}`);
    
    if (response.data.success) {
      // Refresh order to update sections list
      await fetchOrder();
    }
  } catch (error) {
    console.error('Error deleting section:', error);
    alert(error.response?.data?.message || 'Failed to delete section');
  }
};

const router = useRouter();

const editOrder = () => {
  router.push(`/orders/${props.id}/edit`);
};

const viewSection = (sectionId) => {
  router.push(`/sections/${sectionId}`);
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteOrder = async () => {
  deleting.value = true;
  
  try {
    const response = await axios.delete(`/api/orders/${props.id}`);
    
    if (response.data.success) {
      router.push('/orders');
    }
  } catch (error) {
    console.error('Error deleting order:', error);
    alert(error.response?.data?.message || 'Failed to delete order');
  } finally {
    deleting.value = false;
    showDeleteModal.value = false;
  }
};

const goBack = () => {
  router.push('/orders');
};

onMounted(() => {
  fetchOrder();
});
</script>
