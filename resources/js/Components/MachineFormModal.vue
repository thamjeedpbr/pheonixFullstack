<template>
  <Modal :show="show" @close="close" maxWidth="3xl">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">
          {{ isEdit ? 'Edit Machine' : 'Create New Machine' }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          {{ isEdit ? 'Update machine information' : 'Add a new machine to the system' }}
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit">
        <div class="max-h-[70vh] overflow-y-auto space-y-4 pr-2">
          <!-- Machine ID & Name -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Machine ID *</label>
              <input
                v-model="form.machine_id"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.machine_id }"
              />
              <p v-if="errors.machine_id" class="mt-1 text-xs text-red-600">{{ errors.machine_id[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Machine Name *</label>
              <input
                v-model="form.machine_name"
                type="text"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.machine_name }"
              />
              <p v-if="errors.machine_name" class="mt-1 text-xs text-red-600">{{ errors.machine_name[0] }}</p>
            </div>
          </div>

          <!-- Machine Type & Process -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Machine Type *</label>
              <select
                v-model="form.machine_type_id"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.machine_type_id }"
              >
                <option value="">Select Type</option>
                <option v-for="type in machineTypes" :key="type.id" :value="type.id">
                  {{ type.name }}
                </option>
              </select>
              <p v-if="errors.machine_type_id" class="mt-1 text-xs text-red-600">{{ errors.machine_type_id[0] }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Process *</label>
              <select
                v-model="form.process_id"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.process_id }"
              >
                <option value="">Select Process</option>
                <option v-for="process in processes" :key="process.id" :value="process.id">
                  {{ process.name }}
                </option>
              </select>
              <p v-if="errors.process_id" class="mt-1 text-xs text-red-600">{{ errors.process_id[0] }}</p>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              :class="{ 'border-red-500': errors.description }"
            ></textarea>
            <p v-if="errors.description" class="mt-1 text-xs text-red-600">{{ errors.description[0] }}</p>
          </div>

          <!-- Dimensions -->
          <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Dimensions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div>
                <label class="block text-xs text-gray-600 mb-1">Min Width</label>
                <input
                  v-model.number="form.min_width"
                  type="number"
                  step="0.01"
                  class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm"
                />
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Max Width</label>
                <input
                  v-model.number="form.max_width"
                  type="number"
                  step="0.01"
                  class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm"
                />
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Min Height</label>
                <input
                  v-model.number="form.min_height"
                  type="number"
                  step="0.01"
                  class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm"
                />
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Max Height</label>
                <input
                  v-model.number="form.max_height"
                  type="number"
                  step="0.01"
                  class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm"
                />
              </div>
            </div>
          </div>

          <!-- GSM -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Min GSM</label>
              <input
                v-model.number="form.min_gsm"
                type="number"
                step="0.01"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Max GSM</label>
              <input
                v-model.number="form.max_gsm"
                type="number"
                step="0.01"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
          </div>

          <!-- Impressions -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Per Hour Impression</label>
              <input
                v-model.number="form.per_hour_impression"
                type="number"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Per Day Impression</label>
              <input
                v-model.number="form.per_day_impression"
                type="number"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
          </div>

          <!-- Costs -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Cost</label>
              <input
                v-model.number="form.minimum_cost"
                type="number"
                step="0.01"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Per Hour Cost</label>
              <input
                v-model.number="form.per_hour_cost"
                type="number"
                step="0.01"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Make Ready Time (min)</label>
              <input
                v-model.number="form.make_ready_time"
                type="number"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
          </div>

          <!-- Other Fields -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Meter Per Impression</label>
              <input
                v-model.number="form.meter_per_impression"
                type="number"
                step="0.01"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Device URL</label>
              <input
                v-model="form.devise_url"
                type="text"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
          </div>

          <!-- Remarks -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea
              v-model="form.remarks"
              rows="2"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
            ></textarea>
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
            <span v-else>{{ isEdit ? 'Update Machine' : 'Create Machine' }}</span>
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
  machine: {
    type: Object,
    default: null,
  },
  machineTypes: {
    type: Array,
    required: true,
  },
  processes: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(['close', 'saved']);
const authStore = useAuthStore();

const loading = ref(false);
const errors = ref({});

const isEdit = computed(() => !!props.machine);

const form = reactive({
  machine_id: '',
  machine_name: '',
  description: '',
  min_width: null,
  min_height: null,
  max_height: null,
  max_width: null,
  min_gsm: null,
  max_gsm: null,
  status: true,
  per_day_impression: null,
  per_hour_impression: null,
  remarks: '',
  make_ready_time: null,
  minimum_cost: null,
  per_hour_cost: null,
  meter_per_impression: null,
  devise_url: '',
  machine_type_id: '',
  process_id: '',
});

// Watch for machine prop changes to populate form
watch(() => props.machine, (newMachine) => {
  if (newMachine) {
    Object.keys(form).forEach(key => {
      form[key] = newMachine[key] ?? form[key];
    });
  } else {
    resetForm();
  }
}, { immediate: true });

const resetForm = () => {
  form.machine_id = '';
  form.machine_name = '';
  form.description = '';
  form.min_width = null;
  form.min_height = null;
  form.max_height = null;
  form.max_width = null;
  form.min_gsm = null;
  form.max_gsm = null;
  form.status = true;
  form.per_day_impression = null;
  form.per_hour_impression = null;
  form.remarks = '';
  form.make_ready_time = null;
  form.minimum_cost = null;
  form.per_hour_cost = null;
  form.meter_per_impression = null;
  form.devise_url = '';
  form.machine_type_id = '';
  form.process_id = '';
  errors.value = {};
};

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};

  try {
    const url = isEdit.value 
      ? `/api/machines/${props.machine.id}`
      : '/api/machines';
    
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
        alert(data.message || 'Failed to save machine');
      }
    }
  } catch (error) {
    console.error('Error saving machine:', error);
    alert('An error occurred while saving the machine');
  } finally {
    loading.value = false;
  }
};

const close = () => {
  resetForm();
  emit('close');
};
</script>
