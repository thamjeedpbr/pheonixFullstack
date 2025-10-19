<template>
  <AuthenticatedLayout>
    <!-- Header -->
    <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Shifts</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage work shifts and schedules</p>
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
        Add Shift
      </button>
    </div>

    <!-- Mobile Floating Filter Bar -->
    <div class="md:hidden mb-4 sticky top-16 z-10 bg-gray-50 -mx-3 px-3 py-3 border-b border-gray-200 shadow-sm">
      <div class="flex items-center gap-2">
        <div class="flex-1">
          <div class="relative">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search shifts..."
              class="w-full rounded-lg border border-gray-300 pl-10 pr-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <button
          @click="showAdvancedFilters = !showAdvancedFilters"
          type="button"
          class="flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-700 hover:bg-gray-50 transition-colors"
          :class="{ 'bg-blue-50 border-blue-500 text-blue-600': hasActiveFilters }"
        >
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          <span v-if="hasActiveFilters" class="ml-1 text-xs font-medium">{{ activeFiltersCount }}</span>
        </button>
      </div>

      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div v-if="showAdvancedFilters" class="mt-3 space-y-3 bg-white rounded-lg p-3 shadow-md border border-gray-200">
          <div>
            <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
            <select
              v-model="filters.status"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>

          <div class="flex gap-2">
            <button
              type="button"
              @click="resetFilters"
              class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Reset
            </button>
            <button
              type="button"
              @click="showAdvancedFilters = false"
              class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
              Apply
            </button>
          </div>
        </div>
      </transition>
    </div>

    <!-- Desktop Filters -->
    <div class="hidden md:block mb-4 sm:mb-6 rounded-lg bg-white p-3 sm:p-4 shadow-md">
      <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Shift ID or Name..."
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
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Shift ID</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Shift Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Start Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">End Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Duration</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Status</th>
              <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="loading">
              <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2">Loading shifts...</p>
              </td>
            </tr>
            <tr v-else-if="!shifts.length">
              <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2">No shifts found</p>
              </td>
            </tr>
            <tr v-else v-for="shift in shifts" :key="shift.id" class="hover:bg-gray-50">
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ shift.shift_id }}</div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ shift.shift_name }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatTime(shift.start_time) }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatTime(shift.end_time) }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ calculateDuration(shift.start_time, shift.end_time) }}</td>
              <td class="whitespace-nowrap px-6 py-4">
                <span :class="statusBadgeClass(shift.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                  {{ shift.status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button v-if="canUpdate" type="button" @click="openEditModal(shift)" class="text-blue-600 hover:text-blue-900" title="Edit">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button v-if="canDelete" type="button" @click="confirmDelete(shift)" class="text-red-600 hover:text-red-900" title="Delete">
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

      <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div>
          <p class="text-sm text-gray-700">
            Page <span class="font-medium">{{ pagination.current_page }}</span> of <span class="font-medium">{{ pagination.last_page }}</span>
          </p>
        </div>
        <div>
          <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
            <button type="button" @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50">
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
              </svg>
            </button>
            <template v-for="page in visiblePages" :key="page">
              <span v-if="page === '...'" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>
              <button v-else type="button" @click="changePage(page)" :class="[page === pagination.current_page ? 'z-10 bg-blue-600 text-white' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50', 'relative inline-flex items-center px-4 py-2 text-sm font-semibold']">{{ page }}</button>
            </template>
            <button type="button" @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50">
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
      <div class="mb-3 text-xs sm:text-sm text-gray-600 text-center">Showing {{ shifts.length }} of {{ pagination.total || 0 }} shifts</div>
      <div ref="scrollContainer" class="space-y-3">
        <div v-if="loading && !shifts.length" class="text-center py-12">
          <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-sm text-gray-500">Loading shifts...</p>
        </div>
        <div v-else-if="!shifts.length" class="text-center py-12 bg-white rounded-lg shadow-md">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="mt-2 text-sm text-gray-500">No shifts found</p>
        </div>
        <div v-for="shift in shifts" :key="shift.id" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-200">
          <div class="flex items-start justify-between mb-3">
            <div class="flex items-center space-x-3">
              <div class="h-12 w-12 flex-shrink-0">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                  <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ shift.shift_name }}</h3>
                <p class="text-xs text-gray-500">ID: {{ shift.shift_id }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button v-if="canUpdate" type="button" @click="openEditModal(shift)" class="text-blue-600 hover:text-blue-900 p-1" title="Edit">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button v-if="canDelete" type="button" @click="confirmDelete(shift)" class="text-red-600 hover:text-red-900 p-1" title="Delete">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
          <div class="space-y-2 border-t border-gray-200 pt-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Start Time</span>
              <span class="text-sm font-medium text-gray-900">{{ formatTime(shift.start_time) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">End Time</span>
              <span class="text-sm font-medium text-gray-900">{{ formatTime(shift.end_time) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Duration</span>
              <span class="text-sm font-medium text-gray-900">{{ calculateDuration(shift.start_time, shift.end_time) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Status</span>
              <span :class="statusBadgeClass(shift.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                {{ shift.status ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>
        <div v-if="loadingMore" class="text-center py-4">
          <svg class="mx-auto h-6 w-6 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-xs text-gray-500">Loading more...</p>
        </div>
        <div v-if="!pagination.next_page_url && shifts.length > 0" class="text-center py-4">
          <p class="text-xs text-gray-500">No more shifts to load</p>
        </div>
      </div>
    </div>

    <ShiftFormModal :show="showFormModal" :shift="selectedShift" @close="closeFormModal" @saved="handleShiftSaved" />
    <Modal :show="deleteModal.show" type="danger" title="Delete Shift" :message="`Are you sure you want to delete ${deleteModal.shift?.shift_name}? This action cannot be undone.`" confirm-text="Delete" cancel-text="Cancel" :loading="deleteModal.loading" @close="deleteModal.show = false" @confirm="deleteShift" />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import ShiftFormModal from '@/Components/ShiftFormModal.vue';

const authStore = useAuthStore();
const hasPermission = (permission) => {
  const userPermission = authStore.user?.permission;
  if (!userPermission) return false;
  if (Array.isArray(userPermission.permissions)) return userPermission.permissions.includes(permission);
  return userPermission[permission] ?? false;
};

const canCreate = computed(() => hasPermission('shift_create'));
const canUpdate = computed(() => hasPermission('shift_update'));
const canDelete = computed(() => hasPermission('shift_delete'));

const loading = ref(false);
const loadingMore = ref(false);
const shifts = ref([]);
const perPage = ref(20);
const scrollContainer = ref(null);
const showAdvancedFilters = ref(false);
const showFormModal = ref(false);
const selectedShift = ref(null);

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
  shift: null,
  loading: false,
});

const hasActiveFilters = computed(() => filters.status !== '');
const activeFiltersCount = computed(() => (filters.status !== '' ? 1 : 0));

const visiblePages = computed(() => {
  const pages = [];
  const current = pagination.current_page;
  const last = pagination.last_page;
  const delta = 2;
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) pages.push(i);
  if (current - delta > 2) pages.unshift('...');
  if (current + delta < last - 1) pages.push('...');
  pages.unshift(1);
  if (last > 1) pages.push(last);
  return pages.filter((v, i, a) => a.indexOf(v) === i);
});

const formatTime = (time) => {
  if (!time) return 'N/A';
  const [hours, minutes] = time.split(':');
  const hour = parseInt(hours, 10);
  const ampm = hour >= 12 ? 'PM' : 'AM';
  const hour12 = hour % 12 || 12;
  return `${hour12}:${minutes} ${ampm}`;
};

const calculateDuration = (startTime, endTime) => {
  if (!startTime || !endTime) return 'N/A';
  const [startHours, startMinutes] = startTime.split(':').map(Number);
  const [endHours, endMinutes] = endTime.split(':').map(Number);
  let totalMinutes = (endHours * 60 + endMinutes) - (startHours * 60 + startMinutes);
  if (totalMinutes < 0) totalMinutes += 24 * 60;
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;
  return `${hours}h ${minutes}m`;
};

let timeout = null;
watch(filters, () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    pagination.current_page = 1;
    shifts.value = [];
    fetchShifts();
  }, 300);
}, { deep: true });

const fetchShifts = async (page = pagination.current_page, append = false) => {
  if (append) loadingMore.value = true;
  else loading.value = true;
  try {
    const params = new URLSearchParams();
    params.append('page', page);
    params.append('per_page', perPage.value);
    if (filters.search) params.append('search', filters.search);
    if (filters.status !== '') params.append('status', filters.status);
    const response = await fetch(`/api/shifts?${params}`, {
      headers: { 'Authorization': `Bearer ${authStore.token}`, 'Accept': 'application/json' },
    });
    if (response.ok) {
      const result = await response.json();
      const data = result.data;
      if (append) shifts.value = [...shifts.value, ...(data.data || [])];
      else shifts.value = data.data || [];
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
    console.error('Failed to fetch shifts:', error);
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
  if (scrollTop + clientHeight >= scrollHeight * 0.8) fetchShifts(pagination.current_page + 1, true);
};

const handlePerPageChange = () => {
  pagination.current_page = 1;
  shifts.value = [];
  fetchShifts();
};

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page && page !== '...') fetchShifts(page);
};

const resetFilters = () => {
  filters.search = '';
  filters.status = '';
  showAdvancedFilters.value = false;
};

const openCreateModal = () => {
  selectedShift.value = null;
  showFormModal.value = true;
};

const openEditModal = (shift) => {
  selectedShift.value = { ...shift };
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  selectedShift.value = null;
};

const handleShiftSaved = () => {
  closeFormModal();
  shifts.value = [];
  fetchShifts(pagination.current_page);
};

const confirmDelete = (shift) => {
  deleteModal.shift = shift;
  deleteModal.show = true;
};

const deleteShift = async () => {
  deleteModal.loading = true;
  try {
    const response = await fetch(`/api/shifts/${deleteModal.shift.id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${authStore.token}`, 'Accept': 'application/json' },
    });
    if (response.ok) {
      deleteModal.show = false;
      deleteModal.shift = null;
      shifts.value = [];
      fetchShifts(pagination.current_page);
    } else {
      alert('Failed to delete shift');
    }
  } catch (error) {
    console.error('Delete failed:', error);
    alert('Failed to delete shift');
  } finally {
    deleteModal.loading = false;
  }
};

const statusBadgeClass = (status) => status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

onMounted(() => {
  fetchShifts();
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>
