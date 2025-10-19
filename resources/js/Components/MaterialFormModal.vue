<template>
  <Modal :show="show" @close="handleClose" max-width="2xl">
    <div class="p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">
        {{ isEditMode ? 'Edit Material' : 'Create New Material' }}
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Material ID -->
        <div>
          <label for="material_id" class="block text-sm font-medium text-gray-700 mb-1">
            Material ID <span class="text-red-500">*</span>
          </label>
          <input
            id="material_id"
            v-model="form.material_id"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
            :class="{ 'border-red-500': errors.material_id }"
          />
          <p v-if="errors.material_id" class="mt-1 text-sm text-red-500">{{ errors.material_id }}</p>
        </div>

        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Name <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
            :class="{ 'border-red-500': errors.name }"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name }}</p>
        </div>

        <!-- Department -->
        <div>
          <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
            Department <span class="text-red-500">*</span>
          </label>
          <select
            id="department_id"
            v-model="form.department_id"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
            :class="{ 'border-red-500': errors.department_id }"
          >
            <option value="">Select Department</option>
            <option v-for="dept in departments" :key="dept.id" :value="dept.id">
              {{ dept.name }}
            </option>
          </select>
          <p v-if="errors.department_id" class="mt-1 text-sm text-red-500">{{ errors.department_id }}</p>
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- GSM -->
          <div>
            <label for="gsm" class="block text-sm font-medium text-gray-700 mb-1">
              GSM
            </label>
            <input
              id="gsm"
              v-model="form.gsm"
              type="number"
              step="0.01"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
              :class="{ 'border-red-500': errors.gsm }"
            />
            <p v-if="errors.gsm" class="mt-1 text-sm text-red-500">{{ errors.gsm }}</p>
          </div>

          <!-- Unit -->
          <div>
            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">
              Unit
            </label>
            <input
              id="unit"
              v-model="form.unit"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
              :class="{ 'border-red-500': errors.unit }"
            />
            <p v-if="errors.unit" class="mt-1 text-sm text-red-500">{{ errors.unit }}</p>
          </div>
        </div>

        <!-- Utilization -->
        <div>
          <label for="utilization" class="block text-sm font-medium text-gray-700 mb-1">
            Utilization
          </label>
          <input
            id="utilization"
            v-model="form.utilization"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
            :class="{ 'border-red-500': errors.utilization }"
          />
          <p v-if="errors.utilization" class="mt-1 text-sm text-red-500">{{ errors.utilization }}</p>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description
          </label>
          <textarea
            id="description"
            v-model="form.description"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"
            :class="{ 'border-red-500': errors.description }"
          ></textarea>
          <p v-if="errors.description" class="mt-1 text-sm text-red-500">{{ errors.description }}</p>
        </div>

        <!-- Checkboxes -->
        <div class="flex gap-6">
          <!-- Coating -->
          <div class="flex items-center">
            <input
              id="coating"
              v-model="form.coating"
              type="checkbox"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="coating" class="ml-2 block text-sm text-gray-700">
              Coating
            </label>
          </div>

          <!-- Status -->
          <div class="flex items-center">
            <input
              id="status"
              v-model="form.status"
              type="checkbox"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="status" class="ml-2 block text-sm text-gray-700">
              Active
            </label>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="handleClose"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loading ? 'Saving...' : (isEditMode ? 'Update Material' : 'Create Material') }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Modal from '@/Components/Modal.vue';

const authStore = useAuthStore();

const props = defineProps({
  show: Boolean,
  material: Object,
  departments: Array,
});

const emit = defineEmits(['close', 'saved']);

// State
const loading = ref(false);
const errors = ref({});

const form = ref({
  material_id: '',
  name: '',
  department_id: '',
  gsm: '',
  unit: '',
  utilization: '',
  description: '',
  coating: false,
  status: true,
});

// Computed
const isEditMode = computed(() => !!props.material);

// Watch for material changes
watch(() => props.material, (newMaterial) => {
  if (newMaterial) {
    form.value = {
      material_id: newMaterial.material_id || '',
      name: newMaterial.name || '',
      department_id: newMaterial.department_id || '',
      gsm: newMaterial.gsm || '',
      unit: newMaterial.unit || '',
      utilization: newMaterial.utilization || '',
      description: newMaterial.description || '',
      coating: newMaterial.coating || false,
      status: newMaterial.status ?? true,
    };
  } else {
    resetForm();
  }
  errors.value = {};
}, { immediate: true });

// Methods
const resetForm = () => {
  form.value = {
    material_id: '',
    name: '',
    department_id: '',
    gsm: '',
    unit: '',
    utilization: '',
    description: '',
    coating: false,
    status: true,
  };
  errors.value = {};
};

const handleClose = () => {
  resetForm();
  emit('close');
};

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};

  try {
    const url = isEditMode.value 
      ? `/api/materials/${props.material.id}`
      : '/api/materials';
    
    const method = isEditMode.value ? 'PUT' : 'POST';

    const response = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form.value),
    });

    const result = await response.json();

    if (response.ok) {
      emit('saved');
      resetForm();
    } else if (response.status === 422) {
      // Validation errors
      errors.value = result.errors || {};
    } else {
      alert(result.message || 'Failed to save material');
    }
  } catch (error) {
    console.error('Failed to save material:', error);
    alert('Failed to save material');
  } finally {
    loading.value = false;
  }
};
</script>
