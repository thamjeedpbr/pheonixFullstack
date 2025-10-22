<template>
  <AuthenticatedLayout>
    <!-- Header -->
    <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Department Management</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage departments and organizational structure</p>
      </div>
      <button
        v-if="canCreate"
        @click="openCreateModal"
        type="button"
        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Department
      </button>
    </div>

    <!-- Mobile Floating Filter Bar -->
    <div class="md:hidden mb-4 sticky top-16 z-10 bg-gray-50 -mx-3 px-3 py-3 border-b border-gray-200 shadow-sm">
      <div class="flex items-center gap-2">
        <!-- Search Bar -->
        <div class="flex-1">
          <div class="relative">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search departments..."
              class="w-full rounded-lg border border-gray-300 pl-10 pr-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Desktop Filters -->
    <div class="hidden md:block mb-4 sm:mb-6 rounded-lg bg-white p-3 sm:p-4 shadow-md">
      <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Code or Name..."
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Status</label>
          <select
            v-model="filters.status"
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
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
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-hidden rounded-lg bg-white shadow-md">
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
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Department Code
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Status
              </th>
              <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="loading">
              <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2">Loading departments...</p>
              </td>
            </tr>
            <tr v-else-if="!departments.length">
              <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="mt-2">No departments found</p>
              </td>
            </tr>
            <tr v-else v-for="department in departments" :key="department.id" class="hover:bg-gray-50">
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ department.department_code }}</div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                {{ department.name }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span :class="statusBadgeClass(department.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                  {{ department.status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button
                    v-if="canUpdate"
                    type="button"
                    @click="openEditModal(department)"
                    class="text-blue-600 hover:text-blue-900"
                    title="Edit"
                  >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button
                    v-if="canDelete"
                    type="button"
                    @click="confirmDelete(department)"
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

      <!-- Desktop Pagination -->
      <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
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

    <!-- Mobile Card View -->
    <div class="md:hidden">
      <!-- Results Info -->
      <div class="mb-3 text-xs sm:text-sm text-gray-600 text-center">
        Showing {{ departments.length }} of {{ pagination.total || 0 }} departments
      </div>

      <!-- Department Cards with Infinite Scroll -->
      <div ref="scrollContainer" class="space-y-3">
        <!-- Loading State -->
        <div v-if="loading && !departments.length" class="text-center py-12">
          <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-sm text-gray-500">Loading departments...</p>
        </div>

        <!-- No Departments State -->
        <div v-else-if="!departments.length" class="text-center py-12 bg-white rounded-lg shadow-md">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <p class="mt-2 text-sm text-gray-500">No departments found</p>
        </div>

        <!-- Department Cards -->
        <div
          v-for="department in departments"
          :key="department.id"
          class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-200"
        >
          <!-- Department Header -->
          <div class="flex items-start justify-between mb-3">
            <div class="flex items-center space-x-3">
              <div class="h-12 w-12 flex-shrink-0">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                  <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ department.name }}</h3>
                <p class="text-xs text-gray-500">Code: {{ department.department_code }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                v-if="canUpdate"
                type="button"
                @click="openEditModal(department)"
                class="text-blue-600 hover:text-blue-900 p-1"
                title="Edit"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                v-if="canDelete"
                type="button"
                @click="confirmDelete(department)"
                class="text-red-600 hover:text-red-900 p-1"
                title="Delete"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Department Details -->
          <div class="space-y-2 border-t border-gray-200 pt-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Status</span>
              <span :class="statusBadgeClass(department.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                {{ department.status ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Load More Indicator -->
        <div v-if="loadingMore" class="text-center py-4">
          <svg class="mx-auto h-6 w-6 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-xs text-gray-500">Loading more...</p>
        </div>

        <!-- End of Results -->
        <div v-if="!pagination.next_page_url && departments.length > 0" class="text-center py-4">
          <p class="text-xs text-gray-500">No more departments to load</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Form Modal -->
    <DepartmentFormModal
      :show="showFormModal"
      :department="selectedDepartment"
      @close="closeFormModal"
      @saved="handleDepartmentSaved"
    />

    <!-- Delete Confirmation Modal -->
    <Modal
      :show="deleteModal.show"
      type="danger"
      title="Delete Department"
      :message="`Are you sure you want to delete ${deleteModal.department?.name}? This action cannot be undone.`"
      confirm-text="Delete"
      cancel-text="Cancel"
      :loading="deleteModal.loading"
      @close="deleteModal.show = false"
      @confirm="deleteDepartment"
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import DepartmentFormModal from '@/Components/DepartmentFormModal.vue';
import { usePermissions } from '@/composables/usePermissions';

const authStore = useAuthStore();
const { hasPermission } = usePermissions();


const canCreate = computed(() => hasPermission('department.create'));
const canUpdate = computed(() => hasPermission('department.update'));
const canDelete = computed(() => hasPermission('department.delete'));

const loading = ref(false);
const loadingMore = ref(false);
const departments = ref([]);
const perPage = ref(20);
const scrollContainer = ref(null);
const showFormModal = ref(false);
const selectedDepartment = ref(null);

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
  status: '',
});

const deleteModal = reactive({
  show: false,
  department: null,
  loading: false,
});

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

let timeout = null;
watch(filters, () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    pagination.current_page = 1;
    departments.value = [];
    fetchDepartments();
  }, 300);
}, { deep: true });

const fetchDepartments = async (page = pagination.current_page, append = false) => {
  if (append) {
    loadingMore.value = true;
  } else {
    loading.value = true;
  }
  
  try {
    const params = new URLSearchParams();
    params.append('page', page);
    params.append('per_page', perPage.value);
    if (filters.search) params.append('search', filters.search);
    if (filters.status !== '') params.append('status', filters.status);

    const response = await fetch(`/api/departments?${params}`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      const result = await response.json();
      const data = result.data;
      
      if (append) {
        departments.value = [...departments.value, ...(data.data || [])];
      } else {
        departments.value = data.data || [];
      }
      
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
    console.error('Failed to fetch departments:', error);
  } finally {
    loading.value = false;
    loadingMore.value = false;
  }
};

const handleScroll = () => {
  if (loadingMore.value || !pagination.next_page_url) return;
  
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  const scrollHeight = document.documentElement.scrollHeight;
  const clientHeight = document.documentElement.clientHeight;
  
  if (scrollTop + clientHeight >= scrollHeight * 0.8) {
    fetchDepartments(pagination.current_page + 1, true);
  }
};

const handlePerPageChange = () => {
  pagination.current_page = 1;
  departments.value = [];
  fetchDepartments();
};

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page && page !== '...') {
    fetchDepartments(page);
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.status = '';
};

const openCreateModal = () => {
  selectedDepartment.value = null;
  showFormModal.value = true;
};

const openEditModal = (department) => {
  selectedDepartment.value = { ...department };
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  selectedDepartment.value = null;
};

const handleDepartmentSaved = () => {
  closeFormModal();
  departments.value = [];
  fetchDepartments(pagination.current_page);
};

const confirmDelete = (department) => {
  deleteModal.department = department;
  deleteModal.show = true;
};

const deleteDepartment = async () => {
  deleteModal.loading = true;
  try {
    const response = await fetch(`/api/departments/${deleteModal.department.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      deleteModal.show = false;
      deleteModal.department = null;
      departments.value = [];
      fetchDepartments(pagination.current_page);
    } else {
      alert('Failed to delete department');
    }
  } catch (error) {
    console.error('Delete failed:', error);
    alert('Failed to delete department');
  } finally {
    deleteModal.loading = false;
  }
};

const statusBadgeClass = (status) => {
  return status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

onMounted(() => {
  fetchDepartments();
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>
