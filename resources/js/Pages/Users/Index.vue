<template>
  <AuthenticatedLayout>
    <div class="p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">User Management</h1>
          <p class="mt-1 text-sm text-gray-600">Manage system users, roles, and permissions</p>
        </div>
        <button
          v-if="canCreate"
          @click="openCreateModal"
          type="button"
          class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add User
        </button>
      </div>

      <!-- Filters -->
      <div class="mb-6 rounded-lg bg-white p-4 shadow-md">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Search</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Name, username, or phone..."
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">User Type</label>
            <select
              v-model="filters.user_type"
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Types</option>
              <option value="ADMIN">Admin</option>
              <option value="SUPER_WISER">Supervisor</option>
              <option value="OPERATOR">Operator</option>
            </select>
          </div>

          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="filters.status"
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>

          <div class="flex items-end">
            <button
              type="button"
              @click="resetFilters"
              class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Reset Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <!-- Pagination Info and Per Page -->
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 sm:px-6">
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700">Show</label>
            <select
              v-model="perPage"
              @change="handlePerPageChange"
              class="rounded-lg border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option :value="10">10</option>
              <option :value="20">20</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
            <span class="text-sm text-gray-700">entries</span>
          </div>
          <div class="text-sm text-gray-700">
            Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} entries
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 sm:px-6">
                  Username
                </th>
                <th class="hidden px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 md:table-cell">
                  Name
                </th>
                <th class="hidden px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 lg:table-cell">
                  Phone
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 sm:px-6">
                  Type
                </th>
                <th class="hidden px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 sm:table-cell">
                  Status
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700 sm:px-6">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-if="loading">
                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                  <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <p class="mt-2">Loading users...</p>
                </td>
              </tr>
              <tr v-else-if="!users.length">
                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="mt-2">No users found</p>
                </td>
              </tr>
              <tr v-else v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                <td class="whitespace-nowrap px-4 py-4 sm:px-6">
                  <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0">
                      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-medium text-blue-600">
                        {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-gray-900">{{ user.user_name }}</div>
                      <div class="text-xs text-gray-500 md:hidden">{{ user.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-900 md:table-cell">
                  {{ user.name }}
                </td>
                <td class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-500 lg:table-cell">
                  {{ user.phone_no }}
                </td>
                <td class="whitespace-nowrap px-4 py-4 sm:px-6">
                  <span :class="userTypeBadgeClass(user.user_type)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                    {{ formatUserType(user.user_type) }}
                  </span>
                </td>
                <td class="hidden whitespace-nowrap px-6 py-4 sm:table-cell">
                  <span :class="statusBadgeClass(user.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                    {{ user.status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium sm:px-6">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      v-if="canUpdate"
                      type="button"
                      @click="openEditModal(user)"
                      class="text-blue-600 hover:text-blue-900"
                      title="Edit"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      v-if="canDelete && user.id !== currentUserId"
                      type="button"
                      @click="confirmDelete(user)"
                      class="text-red-600 hover:text-red-900"
                      title="Delete"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
          <div class="flex flex-1 justify-between sm:hidden">
            <button
              type="button"
              @click="changePage(pagination.current_page - 1)"
              :disabled="!pagination.prev_page_url"
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
            >
              Previous
            </button>
            <button
              type="button"
              @click="changePage(pagination.current_page + 1)"
              :disabled="!pagination.next_page_url"
              class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Page <span class="font-medium">{{ pagination.current_page }}</span> of
                <span class="font-medium">{{ pagination.last_page }}</span>
              </p>
            </div>
            <div>
              <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                <button
                  type="button"
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="!pagination.prev_page_url"
                  class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                  </svg>
                </button>

                <!-- Page numbers -->
                <template v-for="page in visiblePages" :key="page">
                  <span
                    v-if="page === '...'"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300"
                  >
                    ...
                  </span>
                  <button
                    v-else
                    type="button"
                    @click="changePage(page)"
                    :class="[
                      page === pagination.current_page
                        ? 'z-10 bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                        : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                      'relative inline-flex items-center px-4 py-2 text-sm font-semibold'
                    ]"
                  >
                    {{ page }}
                  </button>
                </template>

                <button
                  type="button"
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="!pagination.next_page_url"
                  class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <UserFormModal
      :show="showFormModal"
      :user="selectedUser"
      :permissions="permissions"
      :machines="machines"
      @close="closeFormModal"
      @saved="handleUserSaved"
    />

    <!-- Delete Confirmation Modal -->
    <Modal
      :show="deleteModal.show"
      type="danger"
      title="Delete User"
      :message="`Are you sure you want to delete ${deleteModal.user?.name}? This action cannot be undone.`"
      confirm-text="Delete"
      cancel-text="Cancel"
      :loading="deleteModal.loading"
      @close="deleteModal.show = false"
      @confirm="deleteUser"
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import UserFormModal from '@/Components/UserFormModal.vue';

const authStore = useAuthStore();
const currentUserId = computed(() => authStore.user?.id);

// Helper function to check permissions (handles both array and boolean formats)
const hasPermission = (permission) => {
  const userPermission = authStore.user?.permission;
  if (!userPermission) return false;
  
  // Check if permissions is an array
  if (Array.isArray(userPermission.permissions)) {
    return userPermission.permissions.includes(permission);
  }
  
  // Check if it's a boolean property (old format)
  return userPermission[permission] ?? false;
};

const canCreate = computed(() => hasPermission('user_menu_create'));
const canUpdate = computed(() => hasPermission('user_menu_update'));
const canDelete = computed(() => hasPermission('user_menu_delete'));

const loading = ref(false);
const users = ref([]);
const permissions = ref([]);
const machines = ref([]);
const perPage = ref(20);

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
  from: 0,
  to: 0,
  prev_page_url: null,
  next_page_url: null,
});

const filters = reactive({
  search: '',
  user_type: '',
  status: '',
});

const showFormModal = ref(false);
const selectedUser = ref(null);

const deleteModal = reactive({
  show: false,
  user: null,
  loading: false,
});

// Computed property for visible page numbers
const visiblePages = computed(() => {
  const pages = [];
  const current = pagination.current_page;
  const last = pagination.last_page;
  const delta = 2;

  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    pages.push(i);
  }

  if (current - delta > 2) {
    pages.unshift('...');
  }
  if (current + delta < last - 1) {
    pages.push('...');
  }

  pages.unshift(1);
  if (last > 1) {
    pages.push(last);
  }

  return pages.filter((v, i, a) => a.indexOf(v) === i);
});

// Watch filters with debounce
let timeout = null;
watch(filters, () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    pagination.current_page = 1;
    fetchUsers();
  }, 300);
}, { deep: true });

const fetchUsers = async (page = pagination.current_page) => {
  loading.value = true;
  try {
    const params = new URLSearchParams();
    params.append('page', page);
    params.append('per_page', perPage.value);
    if (filters.search) params.append('search', filters.search);
    if (filters.user_type) params.append('user_type', filters.user_type);
    if (filters.status !== '') params.append('status', filters.status);

    const response = await fetch(`/api/users?${params}`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      const data = await response.json();
      users.value = data.data || [];
      
      // Update pagination
      Object.assign(pagination, {
        current_page: data.current_page || 1,
        last_page: data.last_page || 1,
        per_page: data.per_page || 20,
        total: data.total || 0,
        from: data.from || 0,
        to: data.to || 0,
        prev_page_url: data.prev_page_url,
        next_page_url: data.next_page_url,
      });
    }
  } catch (error) {
    console.error('Failed to fetch users:', error);
  } finally {
    loading.value = false;
  }
};

const handlePerPageChange = () => {
  pagination.current_page = 1;
  fetchUsers();
};

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page && page !== '...') {
    fetchUsers(page);
  }
};

const fetchPermissions = async () => {
  try {
    const response = await fetch('/api/permissions', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });
    if (response.ok) {
      const data = await response.json();
      permissions.value = data.data || [];
    }
  } catch (error) {
    console.error('Failed to fetch permissions:', error);
  }
};

const fetchMachines = async () => {
  try {
    const response = await fetch('/api/machines?status=1', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });
    if (response.ok) {
      const data = await response.json();
      machines.value = data.data || [];
    }
  } catch (error) {
    console.error('Failed to fetch machines:', error);
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.user_type = '';
  filters.status = '';
};

const openCreateModal = () => {
  console.log('Opening create modal');
  selectedUser.value = null;
  showFormModal.value = true;
};

const openEditModal = (user) => {
  console.log('Opening edit modal for user:', user);
  selectedUser.value = { ...user };
  showFormModal.value = true;
};

const closeFormModal = () => {
  console.log('Closing form modal');
  showFormModal.value = false;
  selectedUser.value = null;
};

const handleUserSaved = () => {
  closeFormModal();
  fetchUsers(pagination.current_page);
};

const confirmDelete = (user) => {
  deleteModal.user = user;
  deleteModal.show = true;
};

const deleteUser = async () => {
  deleteModal.loading = true;
  try {
    const response = await fetch(`/api/users/${deleteModal.user.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      deleteModal.show = false;
      deleteModal.user = null;
      fetchUsers(pagination.current_page);
    } else {
      alert('Failed to delete user');
    }
  } catch (error) {
    console.error('Delete failed:', error);
    alert('Failed to delete user');
  } finally {
    deleteModal.loading = false;
  }
};

const userTypeBadgeClass = (type) => {
  const classes = {
    ADMIN: 'bg-purple-100 text-purple-800',
    SUPER_WISER: 'bg-blue-100 text-blue-800',
    OPERATOR: 'bg-green-100 text-green-800',
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

const statusBadgeClass = (status) => {
  return status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const formatUserType = (type) => {
  const formats = {
    ADMIN: 'Admin',
    SUPER_WISER: 'Supervisor',
    OPERATOR: 'Operator',
  };
  return formats[type] || type;
};

onMounted(() => {
  console.log('Component mounted');
  console.log('Can create:', canCreate.value);
  fetchUsers();
  fetchPermissions();
  fetchMachines();
});
</script>
