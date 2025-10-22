<template>
  <AuthenticatedLayout>
    <!-- Header -->
    <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Machine Types</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage machine type categories and specifications</p>
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
        Add Machine Type
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
              placeholder="Search machine types..."
              class="w-full rounded-lg border border-gray-300 pl-10 pr-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Advanced Filter Button -->
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

      <!-- Advanced Filters Dropdown (Mobile) -->
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
            <label class="mb-1 block text-xs font-medium text-gray-700">Machine Type</label>
            <select
              v-model="filters.machine_type"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Types</option>
              <option value="PRE_PRESS">Pre Press</option>
              <option value="PRESS">Press</option>
              <option value="POST_PRESS">Post Press</option>
              <option value="DIE_CUT">Die Cut</option>
              <option value="OTHER">Other</option>
            </select>
          </div>

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
      <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Type ID or Name..."
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Machine Type</label>
          <select
            v-model="filters.machine_type"
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          >
            <option value="">All Types</option>
            <option value="PRE_PRESS">Pre Press</option>
            <option value="PRESS">Press</option>
            <option value="POST_PRESS">Post Press</option>
            <option value="DIE_CUT">Die Cut</option>
            <option value="OTHER">Other</option>
          </select>
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

    <!-- Desktop Table View (hidden on mobile) -->
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
                Type ID
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                Machine Type
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
              <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2">Loading machine types...</p>
              </td>
            </tr>
            <tr v-else-if="!machineTypes.length">
              <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
                <p class="mt-2">No machine types found</p>
              </td>
            </tr>
            <tr v-else v-for="machineType in machineTypes" :key="machineType.id" class="hover:bg-gray-50">
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ machineType.type_id }}</div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                {{ machineType.name }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span :class="machineTypeBadgeClass(machineType.machine_type)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                  {{ machineTypeLabel(machineType.machine_type) }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span :class="statusBadgeClass(machineType.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                  {{ machineType.status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button
                    v-if="canUpdate"
                    type="button"
                    @click="openEditModal(machineType)"
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
                    @click="confirmDelete(machineType)"
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

    <!-- Mobile Card View (visible only on mobile) -->
    <div class="md:hidden">
      <!-- Results Info -->
      <div class="mb-3 text-xs sm:text-sm text-gray-600 text-center">
        Showing {{ machineTypes.length }} of {{ pagination.total || 0 }} machine types
      </div>

      <!-- Machine Type Cards with Infinite Scroll -->
      <div 
        ref="scrollContainer"
        class="space-y-3"
      >
        <!-- Loading State -->
        <div v-if="loading && !machineTypes.length" class="text-center py-12">
          <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-sm text-gray-500">Loading machine types...</p>
        </div>

        <!-- No Machine Types State -->
        <div v-else-if="!machineTypes.length" class="text-center py-12 bg-white rounded-lg shadow-md">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
          </svg>
          <p class="mt-2 text-sm text-gray-500">No machine types found</p>
        </div>

        <!-- Machine Type Cards -->
        <div
          v-for="machineType in machineTypes"
          :key="machineType.id"
          class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-200"
        >
          <!-- Machine Type Header -->
          <div class="flex items-start justify-between mb-3">
            <div class="flex items-center space-x-3">
              <div class="h-12 w-12 flex-shrink-0">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                  <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ machineType.name }}</h3>
                <p class="text-xs text-gray-500">ID: {{ machineType.type_id }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                v-if="canUpdate"
                type="button"
                @click="openEditModal(machineType)"
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
                @click="confirmDelete(machineType)"
                class="text-red-600 hover:text-red-900 p-1"
                title="Delete"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Machine Type Details -->
          <div class="space-y-2 border-t border-gray-200 pt-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Type</span>
              <span :class="machineTypeBadgeClass(machineType.machine_type)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                {{ machineTypeLabel(machineType.machine_type) }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Status</span>
              <span :class="statusBadgeClass(machineType.status)" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                {{ machineType.status ? 'Active' : 'Inactive' }}
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
        <div v-if="!pagination.next_page_url && machineTypes.length > 0" class="text-center py-4">
          <p class="text-xs text-gray-500">No more machine types to load</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Form Modal -->
    <MachineTypeFormModal
      :show="showFormModal"
      :machineType="selectedMachineType"
      @close="closeFormModal"
      @saved="handleMachineTypeSaved"
    />

    <!-- Delete Confirmation Modal -->
    <Modal
      :show="deleteModal.show"
      type="danger"
      title="Delete Machine Type"
      :message="`Are you sure you want to delete ${deleteModal.machineType?.name}? This action cannot be undone.`"
      confirm-text="Delete"
      cancel-text="Cancel"
      :loading="deleteModal.loading"
      @close="deleteModal.show = false"
      @confirm="deleteMachineType"
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import MachineTypeFormModal from '@/Components/MachineTypeFormModal.vue';
import { usePermissions } from '@/composables/usePermissions';

const authStore = useAuthStore();
const { hasPermission } = usePermissions();

const canCreate = computed(() => hasPermission('machine-type.create'));
const canUpdate = computed(() => hasPermission('machine-type.update'));
const canDelete = computed(() => hasPermission('machine-type.delete'));

const loading = ref(false);
const loadingMore = ref(false);
const machineTypes = ref([]);
const perPage = ref(20);
const scrollContainer = ref(null);
const showAdvancedFilters = ref(false);
const showFormModal = ref(false);
const selectedMachineType = ref(null);

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
  machine_type: '',
  status: '',
});

const deleteModal = reactive({
  show: false,
  machineType: null,
  loading: false,
});

// Check if there are active filters
const hasActiveFilters = computed(() => {
  return filters.machine_type !== '' || filters.status !== '';
});

// Count active filters
const activeFiltersCount = computed(() => {
  let count = 0;
  if (filters.machine_type) count++;
  if (filters.status !== '') count++;
  return count;
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
    machineTypes.value = [];
    fetchMachineTypes();
  }, 300);
}, { deep: true });

const fetchMachineTypes = async (page = pagination.current_page, append = false) => {
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
    if (filters.machine_type) params.append('machine_type', filters.machine_type);
    if (filters.status !== '') params.append('status', filters.status);

    const response = await fetch(`/api/machine-types?${params}`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      const result = await response.json();
      const data = result.data;
      
      if (append) {
        machineTypes.value = [...machineTypes.value, ...(data.data || [])];
      } else {
        machineTypes.value = data.data || [];
      }
      
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
    console.error('Failed to fetch machine types:', error);
  } finally {
    loading.value = false;
    loadingMore.value = false;
  }
};

// Infinite scroll handler for mobile
const handleScroll = () => {
  if (loadingMore.value || !pagination.next_page_url) return;
  
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  const scrollHeight = document.documentElement.scrollHeight;
  const clientHeight = document.documentElement.clientHeight;
  
  if (scrollTop + clientHeight >= scrollHeight * 0.8) {
    fetchMachineTypes(pagination.current_page + 1, true);
  }
};

const handlePerPageChange = () => {
  pagination.current_page = 1;
  machineTypes.value = [];
  fetchMachineTypes();
};

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page && page !== '...') {
    fetchMachineTypes(page);
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.machine_type = '';
  filters.status = '';
  showAdvancedFilters.value = false;
};

const openCreateModal = () => {
  selectedMachineType.value = null;
  showFormModal.value = true;
};

const openEditModal = (machineType) => {
  selectedMachineType.value = { ...machineType };
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  selectedMachineType.value = null;
};

const handleMachineTypeSaved = () => {
  closeFormModal();
  machineTypes.value = [];
  fetchMachineTypes(pagination.current_page);
};

const confirmDelete = (machineType) => {
  deleteModal.machineType = machineType;
  deleteModal.show = true;
};

const deleteMachineType = async () => {
  deleteModal.loading = true;
  try {
    const response = await fetch(`/api/machine-types/${deleteModal.machineType.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      deleteModal.show = false;
      deleteModal.machineType = null;
      machineTypes.value = [];
      fetchMachineTypes(pagination.current_page);
    } else {
      alert('Failed to delete machine type');
    }
  } catch (error) {
    console.error('Delete failed:', error);
    alert('Failed to delete machine type');
  } finally {
    deleteModal.loading = false;
  }
};

const statusBadgeClass = (status) => {
  return status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const machineTypeLabel = (type) => {
  const labels = {
    'PRE_PRESS': 'Pre Press',
    'PRESS': 'Press',
    'POST_PRESS': 'Post Press',
    'DIE_CUT': 'Die Cut',
    'OTHER': 'Other'
  };
  return labels[type] || type;
};

const machineTypeBadgeClass = (type) => {
  const classes = {
    'PRE_PRESS': 'bg-purple-100 text-purple-800',
    'PRESS': 'bg-blue-100 text-blue-800',
    'POST_PRESS': 'bg-indigo-100 text-indigo-800',
    'DIE_CUT': 'bg-orange-100 text-orange-800',
    'OTHER': 'bg-gray-100 text-gray-800'
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

onMounted(() => {
  fetchMachineTypes();
  
  // Add scroll event listener for infinite scroll on mobile
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  // Remove scroll event listener
  window.removeEventListener('scroll', handleScroll);
});
</script>
