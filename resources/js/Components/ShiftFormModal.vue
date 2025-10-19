<template>
  <Modal :show="show" @close="close" maxWidth="2xl">
    <div class="p-6">
      <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">
          {{ isEdit ? 'Edit Shift' : 'Create New Shift' }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          {{ isEdit ? 'Update shift information' : 'Add a new shift to the system' }}
        </p>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="max-h-[70vh] overflow-y-auto space-y-4 pr-2">
          <!-- Shift ID & Shift Name -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Shift ID *</label>
              <input
                v-model="form.shift_id"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.shift_id }"
              />
              <p v-if="errors.shift_id" class="mt-1 text-xs text-red-600">{{ errors.shift_id[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Shift Name *</label>
              <input
                v-model="form.shift_name"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.shift_name }"
              />
              <p v-if="errors.shift_name" class="mt-1 text-xs text-red-600">{{ errors.shift_name[0] }}</p>
            </div>
          </div>

          <!-- Start Time & End Time -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
              <input
                v-model="form.start_time"
                type="time"
                step="1"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.start_time }"
              />
              <p v-if="errors.start_time" class="mt-1 text-xs text-red-600">{{ errors.start_time[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">End Time *</label>
              <input
                v-model="form.end_time"
                type="time"
                step="1"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.end_time || timeValidationError }"
              />
              <p v-if="errors.end_time" class="mt-1 text-xs text-red-600">{{ errors.end_time[0] }}</p>
              <p v-else-if="timeValidationError" class="mt-1 text-xs text-red-600">{{ timeValidationError }}</p>
            </div>
          </div>

          <!-- Duration Display -->
          <div v-if="form.start_time && form.end_time && !timeValidationError" class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-blue-900">Shift Duration:</span>
              <span class="text-sm font-bold text-blue-600">{{ calculateDuration() }}</span>
            </div>
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
            :disabled="loading || !!timeValidationError"
            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading">Saving...</span>
            <span v-else>{{ isEdit ? 'Update Shift' : 'Create Shift' }}</span>
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
  shift: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['close', 'saved']);
const authStore = useAuthStore();

const loading = ref(false);
const errors = ref({});
const timeValidationError = ref('');

const isEdit = computed(() => !!props.shift);

const form = reactive({
  shift_id: '',
  shift_name: '',
  start_time: '',
  end_time: '',
  status: true,
});

watch(() => props.shift, (newShift) => {
  if (newShift) {
    Object.keys(form).forEach(key => {
      form[key] = newShift[key] ?? form[key];
    });
  } else {
    resetForm();
  }
}, { immediate: true });

// Watch for time changes to validate
watch([() => form.start_time, () => form.end_time], () => {
  validateTimes();
});

const validateTimes = () => {
  timeValidationError.value = '';
  
  if (!form.start_time || !form.end_time) return;
  
  // Convert times to minutes for comparison
  const [startHours, startMinutes, startSeconds = 0] = form.start_time.split(':').map(Number);
  const [endHours, endMinutes, endSeconds = 0] = form.end_time.split(':').map(Number);
  
  const startTotalMinutes = startHours * 60 + startMinutes;
  const endTotalMinutes = endHours * 60 + endMinutes;
  
  // Allow overnight shifts (end time can be before start time)
  // Only validate if they're the same time
  if (startTotalMinutes === endTotalMinutes && startSeconds === endSeconds) {
    timeValidationError.value = 'Start time and end time cannot be the same';
  }
};

const calculateDuration = () => {
  if (!form.start_time || !form.end_time) return '';
  
  const [startHours, startMinutes] = form.start_time.split(':').map(Number);
  const [endHours, endMinutes] = form.end_time.split(':').map(Number);
  
  let totalMinutes = (endHours * 60 + endMinutes) - (startHours * 60 + startMinutes);
  
  // Handle overnight shifts
  if (totalMinutes < 0) {
    totalMinutes += 24 * 60;
  }
  
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;
  
  return `${hours}h ${minutes}m`;
};

const resetForm = () => {
  form.shift_id = '';
  form.shift_name = '';
  form.start_time = '';
  form.end_time = '';
  form.status = true;
  errors.value = {};
  timeValidationError.value = '';
};

const handleSubmit = async () => {
  if (timeValidationError.value) return;
  
  loading.value = true;
  errors.value = {};

  try {
    const url = isEdit.value 
      ? `/api/shifts/${props.shift.id}`
      : '/api/shifts';
    
    const method = isEdit.value ? 'PUT' : 'POST';

    // Ensure times include seconds in H:i:s format
    const formData = {
      ...form,
      start_time: form.start_time.length === 5 ? `${form.start_time}:00` : form.start_time,
      end_time: form.end_time.length === 5 ? `${form.end_time}:00` : form.end_time,
    };

    const response = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(formData),
    });

    const data = await response.json();

    if (response.ok) {
      emit('saved');
      close();
    } else {
      if (data.errors) {
        errors.value = data.errors;
      } else {
        alert(data.message || 'Failed to save shift');
      }
    }
  } catch (error) {
    console.error('Error saving shift:', error);
    alert('An error occurred while saving the shift');
  } finally {
    loading.value = false;
  }
};

const close = () => {
  resetForm();
  emit('close');
};
</script>
