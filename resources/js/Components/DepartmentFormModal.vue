<template>
  <Modal :show="show" @close="close" maxWidth="2xl">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">
          {{ isEdit ? 'Edit Department' : 'Create New Department' }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          {{ isEdit ? 'Update department information' : 'Add a new department to the system' }}
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit">
        <div class="max-h-[70vh] overflow-y-auto space-y-4 pr-2">
          <!-- Department Code & Name -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Department Code *</label>
              <input
                v-model="form.department_code"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.department_code }"
              />
              <p v-if="errors.department_code" class="mt-1 text-xs text-red-600">{{ errors.department_code[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name[0] }}</p>
            </div>
          </div>

          <!-- Remark -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remark</label>
            <textarea
              v-model="form.remark"
              rows="3"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="{ 'border-red-500': errors.remark }"
            ></textarea>
            <p v-if="errors.remark" class="mt-1 text-xs text-red-600">{{ errors.remark[0] }}</p>
          </div>

          <!-- Status -->
          <div>
            <label class="flex items-center space-x-2 cursor-pointer">
              <input
                v-model="form.status"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
          <button
            type="button"
            @click="close"
            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading">Saving...</span>
            <span v-else>{{ isEdit ? 'Update Department' : 'Create Department' }}</span>
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  department: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['close', 'saved']);
const authStore = useAuthStore();

const loading = ref(false);
const errors = ref({});

const isEdit = computed(() => !!props.department);

const form = reactive({
  department_code: '',
  name: '',
  remark: '',
  status: true,
});

watch(() => props.department, (newDepartment) => {
  if (newDepartment) {
    Object.keys(form).forEach(key => {
      form[key] = newDepartment[key] ?? form[key];
    });
  } else {
    resetForm();
  }
}, { immediate: true });

const resetForm = () => {
  form.department_code = '';
  form.name = '';
  form.remark = '';
  form.status = true;
  errors.value = {};
};

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};

  try {
    const url = isEdit.value 
      ? `/api/departments/${props.department.id}`
      : '/api/departments';
    
    const method = isEdit.value ? 'PUT' : 'POST';

    const response = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form),
    });

    const data = await response.json();

    if (response.ok) {
      emit('saved');
      close();
    } else {
      if (data.errors) {
        errors.value = data.errors;
      } else {
        alert(data.message || 'Failed to save department');
      }
    }
  } catch (error) {
    console.error('Error saving department:', error);
    alert('An error occurred while saving the department');
  } finally {
    loading.value = false;
  }
};

const close = () => {
  resetForm();
  emit('close');
};
</script>
