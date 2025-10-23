<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
            <li v-if="sectionInfo.order">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/orders/${sectionInfo.order.id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  {{ sectionInfo.order.job_card_no }}
                </button>
              </div>
            </li>
            <li v-if="sectionInfo.section_id">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/sections/${sectionInfo.id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  Section {{ sectionInfo.section_id }}
                </button>
              </div>
            </li>
            <li v-if="formData.form_name">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <button
                  @click="router.push(`/forms/${id}`)"
                  class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2"
                >
                  {{ formData.form_name }}
                </button>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
              </div>
            </li>
          </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Form</h1>
            <p class="mt-1 text-sm text-gray-600">Update form details for Section {{ sectionInfo.section_id }}</p>
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

      <!-- Form -->
      <form v-else @submit.prevent="submitForm" class="space-y-6">
        <!-- Section Info Card (Read-only) -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100">
          <div class="flex items-center gap-3">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
              <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-blue-900">Editing form for:</h3>
              <p class="text-sm text-blue-700">Section {{ sectionInfo.section_id }} - Order {{ sectionInfo.order?.job_card_no }}</p>
            </div>
          </div>
        </div>

        <!-- Basic Information Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Basic Information
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Name -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Form Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.form_name"
                type="text"
                required
                placeholder="e.g., Printing - Front Side"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all"
                :class="{ 'border-red-500 ring-2 ring-red-200': errors.form_name }"
              />
              <p v-if="errors.form_name" class="mt-1.5 text-sm text-red-600">{{ errors.form_name[0] }}</p>
            </div>

            <!-- Schedule Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Schedule Date <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.schedule_date"
                type="date"
                required
                :min="today"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all"
                :class="{ 'border-red-500 ring-2 ring-red-200': errors.schedule_date }"
              />
              <p v-if="errors.schedule_date" class="mt-1.5 text-sm text-red-600">{{ errors.schedule_date[0] }}</p>
            </div>

            <!-- Expected Quantity -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Expected Quantity
              </label>
              <input
                v-model.number="formData.excepted_qty"
                type="number"
                min="0"
                placeholder="e.g., 1000"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all"
              />
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                v-model="formData.description"
                rows="3"
                placeholder="Additional details about this form..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all resize-none"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Machine Assignment Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
            Machine Assignment (Multiple)
            <span v-if="formData.machine_ids.length > 0" class="ml-auto inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
              {{ formData.machine_ids.length }} selected
            </span>
          </h2>
          
          <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
            <label 
              v-for="machine in machines" 
              :key="machine.id" 
              class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 cursor-pointer transition-all"
              :class="{ 'bg-green-50 border-green-400': formData.machine_ids.includes(machine.id) }"
            >
              <input
                type="checkbox"
                :value="machine.id"
                v-model="formData.machine_ids"
                class="h-5 w-5 text-green-600 rounded focus:ring-green-500 focus:ring-2"
              />
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ machine.machine_name }}</div>
                <div class="text-sm text-gray-500">Machine No: {{ machine.machine_no }}</div>
              </div>
              <svg v-if="formData.machine_ids.includes(machine.id)" class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </label>
          </div>
          
          <div v-if="machines.length === 0" class="text-center py-8 text-gray-500 text-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
            No machines available
          </div>
        </div>

        <!-- Material Assignment Card (Single Selection) -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Material Assignment (Single)
          </h2>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Material (Optional)</label>
            <select
              v-model="formData.material_id"
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all"
            >
              <option value="">No Material Assigned</option>
              <option v-for="material in materials" :key="material.id" :value="material.id">
                {{ material.material_code }} - {{ material.material_name }}
              </option>
            </select>
            <p class="mt-1.5 text-xs text-gray-500">You can assign a material later if needed</p>
          </div>
        </div>

        <!-- Operators Assignment Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Operators Assignment
            <span v-if="formData.operator_ids.length > 0" class="ml-auto inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
              {{ formData.operator_ids.length }} selected
            </span>
          </h2>
          
          <div class="space-y-2 max-h-80 overflow-y-auto pr-2">
            <label 
              v-for="operator in operators" 
              :key="operator.id" 
              class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all"
              :class="{ 'bg-blue-50 border-blue-400': formData.operator_ids.includes(operator.id) }"
            >
              <input
                type="checkbox"
                :value="operator.id"
                v-model="formData.operator_ids"
                class="h-5 w-5 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
              />
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ operator.name }}</div>
                <div class="text-sm text-gray-500">{{ operator.user_name }} • {{ operator.user_type }}</div>
              </div>
              <svg v-if="formData.operator_ids.includes(operator.id)" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </label>
          </div>
          
          <div v-if="operators.length === 0" class="text-center py-8 text-gray-500 text-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            No operators available
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-4">
          <button
            type="submit"
            :disabled="submitting"
            class="w-full sm:w-auto sm:flex-1 sm:max-w-xs px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all"
          >
            <span v-if="submitting" class="flex items-center justify-center">
              <svg class="animate-spin h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Updating Form...
            </span>
            <span v-else class="flex items-center justify-center">
              <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Update Form
            </span>
          </button>
          <button
            type="button"
            @click="goBack"
            class="w-full sm:w-auto px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-all"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
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
const loading = ref(true);
const submitting = ref(false);
const sectionInfo = ref({});
const machines = ref([]);
const operators = ref([]);
const materials = ref([]);
const errors = ref({});

const formData = ref({
  form_name: '',
  schedule_date: '',
  excepted_qty: null,
  description: '',
  machine_ids: [], // Changed to array for multiple machines
  operator_ids: [],
  material_id: '' // Single material
});

const today = new Date().toISOString().split('T')[0];

const fetchFormData = async () => {
  try {
    const response = await axios.get(`/api/forms/${props.id}`);
    if (response.data.success) {
      const form = response.data.data;
      
      formData.value = {
        form_name: form.form_name,
        schedule_date: form.schedule_date,
        excepted_qty: form.excepted_qty,
        description: form.description || '',
        machine_ids: form.machines?.map(m => m.id) || [], // Changed to array
        operator_ids: form.users?.map(u => u.id) || [],
        material_id: form.material_id || '' // Single material
      };
      
      // Store section info
      sectionInfo.value = form.section || {};
    }
  } catch (error) {
    console.error('Error fetching form:', error);
    alert('Failed to load form');
    router.push('/orders');
  }
};

const fetchDropdownData = async () => {
  try {
    const [machinesRes, operatorsRes, materialsRes] = await Promise.all([
      axios.get('/api/machines/dropdown'),
      axios.get('/api/users/dropdown?type=operator'),
      axios.get('/api/materials/dropdown')
    ]);
    
    machines.value = machinesRes.data.data || [];
    operators.value = operatorsRes.data.data || [];
    materials.value = materialsRes.data.data || [];
  } catch (error) {
    console.error('Error fetching dropdown data:', error);
    alert('Failed to load form data');
  }
};

const submitForm = async () => {
  errors.value = {};
  submitting.value = true;

  try {
    const response = await axios.put(`/api/forms/${props.id}`, formData.value);
    
    if (response.data.success) {
      alert('Form updated successfully!');
      // Go back to form show page
      router.push(`/forms/${props.id}`);
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else {
      alert(error.response?.data?.message || 'Failed to update form');
    }
  } finally {
    submitting.value = false;
  }
};

const goBack = () => {
  router.push(`/forms/${props.id}`);
};

onMounted(async () => {
  loading.value = true;
  await Promise.all([
    fetchFormData(),
    fetchDropdownData()
  ]);
  loading.value = false;
});
</script>
