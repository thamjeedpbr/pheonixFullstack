<template>
  <AuthenticatedLayout>
    <!-- Header -->
    <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Form Management</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage production jobs, machines, operators, and materials</p>
      </div>
      <router-link
        to="/forms/create"
        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
      >
        <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create Form
      </router-link>
    </div>

    <!-- Mobile Floating Filter Bar -->
    <div class="md:hidden mb-4 sticky top-16 z-10 bg-gray-50 -mx-3 px-3 py-3 border-b border-gray-200 shadow-sm">
      <div class="flex items-center gap-2">
        <div class="flex-1">
          <div class="relative">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search forms..."
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
          class="flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-700 hover:bg-gray-50"
          :class="{ 'bg-blue-50 border-blue-500 text-blue-600': hasActiveFilters }"
        >
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          <span v-if="hasActiveFilters" class="ml-1 text-xs font-medium">{{ activeFiltersCount }}</span>
        </button>
      </div>

      <!-- Advanced Filters Dropdown -->
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
            <select v-model="filters.status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
              <option value="">All Statuses</option>
              <option value="job_pending">Pending</option>
              <option value="make_ready">Make Ready</option>
              <option value="job_started">In Production</option>
              <option value="paused">Paused</option>
              <option value="stopped">Stopped</option>
              <option value="job_completed">Completed</option>
              <option value="quality_verified">QC Verified</option>
              <option value="line_cleared">Line Cleared</option>
            </select>
          </div>

          <div class="flex items-center gap-2 pt-2">
            <button @click="applyFilters" class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
              Apply
            </button>
            <button @click="resetFilters" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
              Reset
            </button>
          </div>
        </div>
      </transition>
    </div>

    <!-- Desktop Filters -->
    <div class="hidden md:block mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="grid grid-cols-4 gap-4">
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Search</label>
          <input v-model="filters.search" type="text" placeholder="Form No, Name..." class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
        </div>
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Status</label>
          <select v-model="filters.status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">All Statuses</option>
            <option value="job_pending">Pending</option>
            <option value="make_ready">Make Ready</option>
            <option value="job_started">In Production</option>
            <option value="paused">Paused</option>
            <option value="stopped">Stopped</option>
            <option value="job_completed">Completed</option>
            <option value="quality_verified">QC Verified</option>
            <option value="line_cleared">Line Cleared</option>
          </select>
        </div>
        <div class="col-span-2 flex items-end">
          <button @click="resetFilters" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Mobile Cards -->
    <div v-else-if="isMobile && forms.length > 0" class="space-y-3">
      <div v-for="form in forms" :key="form.id" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-start justify-between mb-3">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">{{ form.form_no }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ form.form_name }}</p>
          </div>
          <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusClass(form.status)">
            {{ form.status_label }}
          </span>
        </div>

        <div class="mb-3 p-2 bg-gray-50 rounded-lg text-xs text-gray-600">
          <div>{{ form.section?.section_no }} - {{ form.section?.section_name }}</div>
          <div v-if="form.section?.order" class="mt-1">{{ form.section.order.job_card_no }}</div>
        </div>

        <div class="grid grid-cols-3 gap-2 mb-3">
          <div class="text-center p-2 bg-blue-50 rounded-lg">
            <p class="text-xs text-gray-600">Machine</p>
            <p class="text-xs font-medium">{{ form.machine?.machine_id || 'N/A' }}</p>
          </div>
          <div class="text-center p-2 bg-green-50 rounded-lg">
            <p class="text-xs text-gray-600">Operators</p>
            <p class="text-xs font-medium">{{ form.operators?.length || 0 }}</p>
          </div>
          <div class="text-center p-2 bg-orange-50 rounded-lg">
            <p class="text-xs text-gray-600">Materials</p>
            <p class="text-xs font-medium">{{ form.materials?.length || 0 }}</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <router-link :to="`/forms/${form.id}`" class="flex-1 rounded-lg border border-blue-600 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 text-center">
            View
          </router-link>
          <router-link :to="`/forms/${form.id}/edit`" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 text-center">
            Edit
          </router-link>
          <button v-if="form.status === 'job_pending'" @click="confirmDelete(form)" class="rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Desktop Table -->
    <div v-else-if="!isMobile && forms.length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Form No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Form Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Machine</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ops</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Mats</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Schedule</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="form in forms" :key="form.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ form.form_no }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ form.form_name }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">
              <div>{{ form.section?.section_no }}</div>
              <div class="text-xs text-gray-500">{{ form.section?.order?.job_card_no }}</div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ form.machine?.machine_id || 'N/A' }}</td>
            <td class="px-6 py-4 text-center">
              <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                {{ form.operators?.length || 0 }}
              </span>
            </td>
            <td class="px-6 py-4 text-center">
              <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                {{ form.materials?.length || 0 }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusClass(form.status)">
                {{ form.status_label }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ form.schedule_date_formatted }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center justify-end gap-2">
                <router-link :to="`/forms/${form.id}`" class="text-blue-600 hover:text-blue-900">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </router-link>
                <router-link :to="`/forms/${form.id}/edit`" class="text-gray-600 hover:text-gray-900">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                <button v-if="form.status === 'job_pending'" @click="confirmDelete(form)" class="text-red-600 hover:text-red-900">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-700">
              Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} results
            </span>
          </div>
          <div class="flex gap-2">
            <button @click="previousPage" :disabled="meta.current_page === 1" class="rounded-lg border px-4 py-2 text-sm disabled:opacity-50">
              Previous
            </button>
            <button @click="nextPage" :disabled="meta.current_page === meta.last_page" class="rounded-lg border px-4 py-2 text-sm disabled:opacity-50">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading && forms.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No forms found</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new production form.</p>
      <div class="mt-6">
        <router-link to="/forms/create" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
          Create Form
        </router-link>
      </div>
    </div>

    <!-- Delete Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showDeleteModal = false"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg font-medium text-gray-900">Delete Form</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete "{{ formToDelete?.form_no }}"? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button @click="deleteForm" :disabled="deleting" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto">
              {{ deleting ? 'Deleting...' : 'Delete' }}
            </button>
            <button @click="showDeleteModal = false" class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

export default {
  components: { AuthenticatedLayout },
  data() {
    return {
      forms: [],
      loading: false,
      deleting: false,
      showDeleteModal: false,
      showAdvancedFilters: false,
      formToDelete: null,
      filters: { search: '', status: '', page: 1, per_page: 20 },
      meta: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0 },
      searchTimeout: null,
      isMobile: window.innerWidth < 768
    };
  },
  computed: {
    hasActiveFilters() { return !!this.filters.status; },
    activeFiltersCount() { return this.filters.status ? 1 : 0; }
  },
  watch: {
    'filters.search'() { this.debouncedFetch(); },
    'filters.status'() { this.fetchForms(); }
  },
  mounted() {
    this.fetchForms();
    window.addEventListener('resize', () => this.isMobile = window.innerWidth < 768);
  },
  methods: {
    async fetchForms() {
      this.loading = true;
      try {
        const params = { ...this.filters };
        Object.keys(params).forEach(key => !params[key] && delete params[key]);
        const response = await axios.get('/api/forms', { params });
        if (response.data.success) {
          this.forms = response.data.data;
          this.meta = response.data.meta;
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Failed to fetch forms');
      } finally {
        this.loading = false;
      }
    },
    debouncedFetch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => this.fetchForms(), 300);
    },
    applyFilters() {
      this.showAdvancedFilters = false;
      this.fetchForms();
    },
    resetFilters() {
      this.filters = { search: '', status: '', page: 1, per_page: 20 };
      this.showAdvancedFilters = false;
      this.fetchForms();
    },
    nextPage() {
      if (this.meta.current_page < this.meta.last_page) {
        this.filters.page++;
        this.fetchForms();
      }
    },
    previousPage() {
      if (this.meta.current_page > 1) {
        this.filters.page--;
        this.fetchForms();
      }
    },
    confirmDelete(form) {
      this.formToDelete = form;
      this.showDeleteModal = true;
    },
    async deleteForm() {
      this.deleting = true;
      try {
        await axios.delete(`/api/forms/${this.formToDelete.id}`);
        this.showDeleteModal = false;
        this.fetchForms();
        alert('Form deleted successfully');
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to delete form');
      } finally {
        this.deleting = false;
      }
    },
    getStatusClass(status) {
      const classes = {
        job_pending: 'bg-gray-100 text-gray-800',
        make_ready: 'bg-yellow-100 text-yellow-800',
        job_started: 'bg-green-100 text-green-800',
        paused: 'bg-orange-100 text-orange-800',
        stopped: 'bg-red-100 text-red-800',
        job_completed: 'bg-blue-100 text-blue-800',
        quality_verified: 'bg-purple-100 text-purple-800',
        line_cleared: 'bg-teal-100 text-teal-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    }
  }
};
</script>
