<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto">
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
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Order</h1>
        <p class="mt-1 text-sm text-gray-600">Update order details</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Form Card -->
      <div v-else class="bg-white shadow-md rounded-lg p-6">
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Job Card Number -->
          <div>
            <label for="job_card_no" class="block text-sm font-medium text-gray-700">
              Job Card Number <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.job_card_no"
              type="text"
              id="job_card_no"
              required
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.job_card_no 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
              placeholder="Enter job card number"
            />
            <p v-if="errors.job_card_no" class="mt-1 text-sm text-red-600">{{ errors.job_card_no }}</p>
          </div>

          <!-- Client Name -->
          <div>
            <label for="client_name" class="block text-sm font-medium text-gray-700">
              Client Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.client_name"
              type="text"
              id="client_name"
              required
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.client_name 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
              placeholder="Enter client name"
            />
            <p v-if="errors.client_name" class="mt-1 text-sm text-red-600">{{ errors.client_name }}</p>
          </div>

          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
              Order Title <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.title"
              type="text"
              id="title"
              required
              maxlength="500"
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.title 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
              placeholder="Brief description of the order"
            />
            <div class="mt-1 flex justify-between">
              <p v-if="errors.title" class="text-sm text-red-600">{{ errors.title }}</p>
              <p class="text-xs text-gray-500 ml-auto">{{ form.title.length }}/500</p>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">
              Description
            </label>
            <textarea
              v-model="form.description"
              id="description"
              rows="4"
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.description 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
              placeholder="Detailed description of the order (optional)"
            ></textarea>
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
          </div>

          <!-- Delivery Date -->
          <div>
            <label for="delivery_date" class="block text-sm font-medium text-gray-700">
              Delivery Date <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.delivery_date"
              type="date"
              id="delivery_date"
              required
              :min="today"
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.delivery_date 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
            />
            <p v-if="errors.delivery_date" class="mt-1 text-sm text-red-600">{{ errors.delivery_date }}</p>
          </div>

          <!-- Priority -->
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">
              Priority
            </label>
            <select
              v-model="form.priority"
              id="priority"
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.priority 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
            >
              <option value="low">Low</option>
              <option value="normal">Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
            <p v-if="errors.priority" class="mt-1 text-sm text-red-600">{{ errors.priority }}</p>
          </div>

          <!-- Status -->
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">
              Status
            </label>
            <select
              v-model="form.status"
              id="status"
              :class="[
                'mt-1 w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2',
                errors.status 
                  ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                  : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
              ]"
            >
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
            <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
          </div>

          <!-- Form Actions -->
          <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button
              type="submit"
              :disabled="submitting"
              class="flex-1 sm:flex-none inline-flex justify-center items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ submitting ? 'Updating...' : 'Update Order' }}
            </button>
            <button
              type="button"
              @click="goBack"
              :disabled="submitting"
              class="flex-1 sm:flex-none inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
  id: {
    type: String,
    required: true
  }
});

const form = ref({
  job_card_no: '',
  client_name: '',
  title: '',
  description: '',
  delivery_date: '',
  priority: 'normal',
  status: 'pending'
});

const errors = ref({});
const loading = ref(true);
const submitting = ref(false);

const today = computed(() => {
  const date = new Date();
  return date.toISOString().split('T')[0];
});

const fetchOrder = async () => {
  loading.value = true;
  
  try {
    const response = await axios.get(`/api/orders/${props.id}`);
    
    if (response.data.success) {
      const order = response.data.data;
      form.value = {
        job_card_no: order.job_card_no,
        client_name: order.client_name,
        title: order.title,
        description: order.description || '',
        delivery_date: order.delivery_date,
        priority: order.priority,
        status: order.status
      };
    }
  } catch (error) {
    console.error('Error fetching order:', error);
    alert('Failed to load order');
    goBack();
  } finally {
    loading.value = false;
  }
};

const submitForm = async () => {
  errors.value = {};
  submitting.value = true;

  try {
    const response = await axios.put(`/api/orders/${props.id}`, form.value);
    
    if (response.data.success) {
      router.push('/orders');
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else {
      alert(error.response?.data?.message || 'Failed to update order. Please try again.');
    }
  } finally {
    submitting.value = false;
  }
};

const router = useRouter();

const goBack = () => {
  router.push('/orders');
};

onMounted(() => {
  fetchOrder();
});
</script>
