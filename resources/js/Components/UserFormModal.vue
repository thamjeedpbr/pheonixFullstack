<template>
  <transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" @click="close">
      <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>

        <!-- Modal panel -->
        <div
          class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle"
          @click.stop
        >
          <!-- Header -->
          <div class="bg-white px-6 pb-4 pt-5">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ isEdit ? 'Edit User' : 'Create New User' }}
              </h3>
              <button
                @click="close"
                class="rounded-md text-gray-400 hover:text-gray-500"
              >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="handleSubmit" class="px-6 pb-6">
            <div class="space-y-4">
              <!-- Username -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Username *</label>
                <input
                  v-model="form.user_name"
                  type="text"
                  required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.user_name }"
                />
                <p v-if="errors.user_name" class="mt-1 text-sm text-red-600">{{ errors.user_name }}</p>
              </div>

              <!-- Full Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.name }"
                />
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
              </div>

              <!-- Phone Number -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Phone Number *</label>
                <input
                  v-model="form.phone_no"
                  type="text"
                  required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.phone_no }"
                />
                <p v-if="errors.phone_no" class="mt-1 text-sm text-red-600">{{ errors.phone_no }}</p>
              </div>

              <!-- User Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700">User Type *</label>
                <select
                  v-model="form.user_type"
                  required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.user_type }"
                >
                  <option value="">Select Type</option>
                  <option value="ADMIN">Admin</option>
                  <option value="SUPER_WISER">Supervisor</option>
                  <option value="OPERATOR">Operator</option>
                </select>
                <p v-if="errors.user_type" class="mt-1 text-sm text-red-600">{{ errors.user_type }}</p>
              </div>

              <!-- Permission Role -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Permission Role *</label>
                <select
                  v-model="form.permission_id"
                  required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.permission_id }"
                >
                  <option value="">Select Role</option>
                  <option v-for="permission in permissions" :key="permission.id" :value="permission.id">
                    {{ permission.role_name }}
                  </option>
                </select>
                <p v-if="errors.permission_id" class="mt-1 text-sm text-red-600">{{ errors.permission_id }}</p>
              </div>

              <!-- Assigned Machines -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Assigned Machines</label>
                <select
                  v-model="form.machine_ids"
                  multiple
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  size="5"
                >
                  <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                    {{ machine.machine_name }}
                  </option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple</p>
              </div>

              <!-- Password (only if creating or changing) -->
              <div v-if="!isEdit || showPasswordFields">
                <label class="block text-sm font-medium text-gray-700">
                  Password {{ isEdit ? '' : '*' }}
                </label>
                <div class="relative mt-1">
                  <input
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    :required="!isEdit"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 pr-10 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.password }"
                  />
                  <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"
                  >
                    <svg v-if="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                  </button>
                </div>
                <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
              </div>

              <!-- Confirm Password -->
              <div v-if="!isEdit || showPasswordFields">
                <label class="block text-sm font-medium text-gray-700">
                  Confirm Password {{ isEdit ? '' : '*' }}
                </label>
                <input
                  v-model="form.password_confirmation"
                  :type="showPassword ? 'text' : 'password'"
                  :required="!isEdit"
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.password_confirmation }"
                />
                <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
              </div>

              <!-- Change Password Toggle (Edit mode) -->
              <div v-if="isEdit && !showPasswordFields">
                <button
                  type="button"
                  @click="showPasswordFields = true"
                  class="text-sm text-blue-600 hover:text-blue-800"
                >
                  Change Password
                </button>
              </div>

              <!-- Status -->
              <div class="flex items-center">
                <input
                  v-model="form.status"
                  type="checkbox"
                  class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <label class="ml-2 block text-sm text-gray-700">Active</label>
              </div>

              <!-- Error Message -->
              <div v-if="errorMessage" class="rounded-lg bg-red-50 p-4">
                <p class="text-sm text-red-600">{{ errorMessage }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end gap-3">
              <button
                type="button"
                @click="close"
                :disabled="loading"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
              >
                <span v-if="loading">Saving...</span>
                <span v-else>{{ isEdit ? 'Update' : 'Create' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { useAuthStore } from '@/stores/auth';

const props = defineProps({
  show: Boolean,
  user: Object,
  permissions: Array,
  machines: Array,
});

const emit = defineEmits(['close', 'saved']);

const authStore = useAuthStore();
const isEdit = computed(() => !!props.user);

const form = reactive({
  user_name: '',
  name: '',
  phone_no: '',
  user_type: '',
  permission_id: '',
  machine_ids: [],
  password: '',
  password_confirmation: '',
  status: true,
});

const loading = ref(false);
const showPassword = ref(false);
const showPasswordFields = ref(false);
const errorMessage = ref('');
const errors = reactive({
  user_name: '',
  name: '',
  phone_no: '',
  user_type: '',
  permission_id: '',
  password: '',
  password_confirmation: '',
});

watch(() => props.user, (user) => {
  if (user) {
    form.user_name = user.user_name;
    form.name = user.name;
    form.phone_no = user.phone_no;
    form.user_type = user.user_type;
    form.permission_id = user.permission?.id || '';
    form.machine_ids = user.machines?.map(m => m.id) || [];
    form.status = user.status;
    form.password = '';
    form.password_confirmation = '';
    showPasswordFields.value = false;
  } else {
    resetForm();
  }
});

const resetForm = () => {
  form.user_name = '';
  form.name = '';
  form.phone_no = '';
  form.user_type = '';
  form.permission_id = '';
  form.machine_ids = [];
  form.password = '';
  form.password_confirmation = '';
  form.status = true;
  clearErrors();
};

const clearErrors = () => {
  errorMessage.value = '';
  Object.keys(errors).forEach(key => errors[key] = '');
};

const handleSubmit = async () => {
  clearErrors();
  loading.value = true;

  try {
    const url = isEdit.value ? `/api/users/${props.user.id}` : '/api/users';
    const method = isEdit.value ? 'PUT' : 'POST';

    const payload = {
      user_name: form.user_name,
      name: form.name,
      phone_no: form.phone_no,
      user_type: form.user_type,
      permission_id: form.permission_id,
      machine_ids: form.machine_ids,
      status: form.status,
    };

    // Only include password if provided
    if (form.password) {
      payload.password = form.password;
      payload.password_confirmation = form.password_confirmation;
    }

    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      emit('saved');
      resetForm();
    } else {
      if (data.errors) {
        Object.keys(data.errors).forEach(key => {
          if (errors.hasOwnProperty(key)) {
            errors[key] = Array.isArray(data.errors[key]) 
              ? data.errors[key][0] 
              : data.errors[key];
          }
        });
      } else {
        errorMessage.value = data.message || 'Failed to save user';
      }
    }
  } catch (error) {
    console.error('Save error:', error);
    errorMessage.value = 'Network error. Please try again.';
  } finally {
    loading.value = false;
  }
};

const close = () => {
  if (!loading.value) {
    resetForm();
    emit('close');
  }
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
