<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto">
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
            <p class="mt-1 text-sm text-gray-600">View and manage order information</p>
          </div>
          <div class="flex gap-2">
            <button
              @click="editOrder"
              class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
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
        <div class="bg-white shadow-md rounded-lg p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Job Card Number -->
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Job Card Number</label>
              <p class="text-lg font-bold text-gray-900">{{ order.job_card_no }}</p>
            </div>

            <!-- Status & Priority -->
            <div class="flex gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" :class="getStatusClass(order.status)">
                  {{ order.status_label }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" :class="getPriorityClass(order.priority)">
                  {{ order.priority_label }}
                </span>
              </div>
            </div>

            <!-- Client Name -->
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Client Name</label>
              <p class="text-base text-gray-900">{{ order.client_name }}</p>
            </div>

            <!-- Delivery Date -->
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Delivery Date</label>
              <p class="text-base text-gray-900">{{ order.delivery_date_formatted }}</p>
              <p v-if="order.days_until_delivery !== null" class="text-sm mt-1" :class="order.days_until_delivery < 0 ? 'text-red-600 font-medium' : 'text-gray-500'">
                {{ order.days_until_delivery < 0 ? `Overdue by ${Math.abs(order.days_until_delivery)} days` : `${order.days_until_delivery} days remaining` }}
              </p>
            </div>

            <!-- Title -->
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
              <p class="text-base text-gray-900">{{ order.title }}</p>
            </div>

            <!-- Description -->
            <div v-if="order.description" class="sm:col-span-2">
              <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
              <p class="text-base text-gray-700 whitespace-pre-line">{{ order.description }}</p>
            </div>

            <!-- Created By -->
            <div v-if="order.created_by">
              <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
              <p class="text-base text-gray-900">{{ order.created_by.name }}</p>
            </div>

            <!-- Created At -->
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Created On</label>
              <p class="text-base text-gray-900">{{ order.created_at_formatted }}</p>
            </div>
          </div>
        </div>

        <!-- Sections Card -->
        <div class="bg-white shadow-md rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Sections</h2>
            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
              {{ order.sections_count || 0 }} sections
            </span>
          </div>

          <div v-if="order.sections && order.sections.length > 0" class="space-y-3">
            <div
              v-for="section in order.sections"
              :key="section.id"
              class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div>
                <p class="font-medium text-gray-900">{{ section.section_name }}</p>
                <p class="text-sm text-gray-500">Section No: {{ section.section_no }}</p>
              </div>
              <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                  {{ section.forms_count }} forms
                </span>
                <button
                  @click="viewSection(section.id)"
                  class="text-blue-600 hover:text-blue-900 transition-colors"
                  title="View Section"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No sections</h3>
            <p class="mt-1 text-sm text-gray-500">This order doesn't have any sections yet.</p>
          </div>
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Order</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete order "{{ order.job_card_no }}"? 
                    <span v-if="order.sections_count > 0" class="font-medium text-red-600">
                      This order has {{ order.sections_count }} section(s) with forms and cannot be deleted.
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
