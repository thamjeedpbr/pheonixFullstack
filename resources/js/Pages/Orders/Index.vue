<template>
  <AuthenticatedLayout>
    <!-- Header -->
    <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Order Management</h1>
        <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage job cards, clients, and delivery schedules</p>
      </div>
      <button
        @click="openCreateModal"
        type="button"
        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 sm:px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create Order
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
              placeholder="Search orders..."
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
            <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
            <select
              v-model="filters.status"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <div>
            <label class="mb-1 block text-xs font-medium text-gray-700">Priority</label>
            <select
              v-model="filters.priority"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">All Priorities</option>
              <option value="low">Low</option>
              <option value="normal">Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div>
            <label class="mb-1 block text-xs font-medium text-gray-700">Date From</label>
            <input
              v-model="filters.date_from"
              type="date"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="mb-1 block text-xs font-medium text-gray-700">Date To</label>
            <input
              v-model="filters.date_to"
              type="date"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
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
      <div class="grid gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-6">
        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Search</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Job card, client..."
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
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Priority</label>
          <select
            v-model="filters.priority"
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          >
            <option value="">All Priorities</option>
            <option value="low">Low</option>
            <option value="normal">Normal</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>

        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Date From</label>
          <input
            v-model="filters.date_from"
            type="date"
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="mb-1 block text-xs sm:text-sm font-medium text-gray-700">Date To</label>
          <input
            v-model="filters.date_to"
            type="date"
            class="w-full rounded-lg border border-gray-300 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
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
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Job Card No</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Client Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Title</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Sections</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Priority</th>
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Delivery Date</th>
              <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="loading">
              <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                <svg class="mx-auto h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </td>
            </tr>
            <tr v-else-if="orders.length === 0">
              <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                No orders found. Try adjusting your filters or create a new order.
              </td>
            </tr>
            <tr v-else v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ order.job_card_no }}</div>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm text-gray-900">{{ order.client_name }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate" :title="order.title">{{ order.title }}</div>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                  {{ order.sections_count || 0 }} sections
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="getStatusClass(order.status)">
                  {{ order.status_label }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="getPriorityClass(order.priority)">
                  {{ order.priority_label }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                <div class="text-sm text-gray-900">{{ order.delivery_date_formatted }}</div>
                <div v-if="order.days_until_delivery !== null" class="text-xs" :class="order.days_until_delivery < 0 ? 'text-red-600' : 'text-gray-500'">
                  {{ order.days_until_delivery < 0 ? 'Overdue' : `${order.days_until_delivery} days` }}
                </div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button @click="viewOrder(order.id)" class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button @click="editOrder(order.id)" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Edit">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete(order)" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
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
      <div class="border-t border-gray-200 px-4 py-3 sm:px-6">
        <div class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
            <button @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                  <span class="sr-only">Previous</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </button>
                <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[page === pagination.current_page ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', 'relative inline-flex items-center px-4 py-2 border text-sm font-medium']">{{ page }}</button>
                <button @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                  <span class="sr-only">Next</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden">
      <div v-if="loading" class="flex justify-center py-12">
        <svg class="h-8 w-8 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <div v-else-if="orders.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new order.</p>
      </div>

      <div v-else class="space-y-3" ref="ordersContainer">
        <div v-for="order in orders" :key="order.id" class="rounded-lg bg-white p-4 shadow-md border border-gray-200">
          <div class="flex items-start justify-between mb-3">
            <div>
              <div class="text-xs text-gray-500 mb-1">Job Card No</div>
              <div class="text-base font-bold text-gray-900">{{ order.job_card_no }}</div>
            </div>
            <div class="flex gap-2">
              <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium" :class="getStatusClass(order.status)">{{ order.status_label }}</span>
              <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium" :class="getPriorityClass(order.priority)">{{ order.priority_label }}</span>
            </div>
          </div>
          <div class="mb-2">
            <div class="text-xs text-gray-500">Client</div>
            <div class="text-sm font-medium text-gray-900">{{ order.client_name }}</div>
          </div>
          <div class="mb-3">
            <div class="text-xs text-gray-500">Title</div>
            <div class="text-sm text-gray-700">{{ order.title }}</div>
          </div>
          <div class="mb-3">
            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ order.sections_count || 0 }} sections</span>
          </div>
          <div class="mb-3 flex items-center text-sm text-gray-600">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ order.delivery_date_formatted }}</span>
            <span v-if="order.days_until_delivery !== null" class="ml-2" :class="order.days_until_delivery < 0 ? 'text-red-600 font-medium' : 'text-gray-500'">
              ({{ order.days_until_delivery < 0 ? 'Overdue' : `${order.days_until_delivery} days` }})
            </span>
          </div>
          <div class="flex gap-2 pt-3 border-t border-gray-200">
            <button @click="viewOrder(order.id)" class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
              <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              View
            </button>
            <button @click="editOrder(order.id)" class="flex-1 inline-flex items-center justify-center rounded-lg border border-yellow-300 bg-yellow-50 px-3 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-100">
              <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
            </button>
            <button @click="confirmDelete(order)" class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Load More -->
      <div v-if="hasMorePages && !loading" class="mt-4 text-center">
        <button @click="loadMore" :disabled="loadingMore" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">
          <svg v-if="loadingMore" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ loadingMore ? 'Loading...' : 'Load More' }}
        </button>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Order</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Are you sure you want to delete order "{{ orderToDelete?.job_card_no }}"? This action cannot be undone.</p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
              <button @click="deleteOrder" :disabled="deleting" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
              <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const orders = ref([]);
const loading = ref(false);
const loadingMore = ref(false);
const deleting = ref(false);
const showAdvancedFilters = ref(false);
const showDeleteModal = ref(false);
const orderToDelete = ref(null);
const perPage = ref(20);

const filters = ref({
  search: '',
  status: '',
  priority: '',
  date_from: '',
  date_to: ''
});

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
  from: 0,
  to: 0
});

let searchTimeout = null;

const hasActiveFilters = computed(() => {
  return filters.value.search || filters.value.status || filters.value.priority || 
         filters.value.date_from || filters.value.date_to;
});

const activeFiltersCount = computed(() => {
  let count = 0;
  if (filters.value.status) count++;
  if (filters.value.priority) count++;
  if (filters.value.date_from) count++;
  if (filters.value.date_to) count++;
  return count;
});

const hasMorePages = computed(() => {
  return pagination.value.current_page < pagination.value.last_page;
});

const visiblePages = computed(() => {
  const current = pagination.value.current_page;
  const last = pagination.value.last_page;
  const delta = 2;
  const range = [];
  const rangeWithDots = [];
  let l;

  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
      range.push(i);
    }
  }

  range.forEach((i) => {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push('...');
      }
    }
    rangeWithDots.push(i);
    l = i;
  });

  return rangeWithDots;
});

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-gray-100 text-gray-800',
    'in_progress': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getPriorityClass = (priority) => {
  const classes = {
    'low': 'bg-gray-100 text-gray-700',
    'normal': 'bg-blue-100 text-blue-700',
    'high': 'bg-orange-100 text-orange-700',
    'urgent': 'bg-red-100 text-red-700'
  };
  return classes[priority] || 'bg-gray-100 text-gray-700';
};

const fetchOrders = async (page = 1, append = false) => {
  if (!append) {
    loading.value = true;
  } else {
    loadingMore.value = true;
  }

  try {
    const params = {
      page,
      per_page: perPage.value,
      ...filters.value
    };

    const response = await axios.get('/api/orders', { params });
    
    if (response.data.success) {
      if (append) {
        orders.value = [...orders.value, ...response.data.data];
      } else {
        orders.value = response.data.data;
      }
      pagination.value = response.data.meta;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  } finally {
    loading.value = false;
    loadingMore.value = false;
  }
};

const loadMore = () => {
  if (hasMorePages.value && !loadingMore.value) {
    fetchOrders(pagination.value.current_page + 1, true);
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page && page !== pagination.value.current_page) {
    fetchOrders(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const handlePerPageChange = () => {
  pagination.value.current_page = 1;
  fetchOrders(1);
};

const resetFilters = () => {
  filters.value = {
    search: '',
    status: '',
    priority: '',
    date_from: '',
    date_to: ''
  };
  pagination.value.current_page = 1;
  fetchOrders(1);
};

const openCreateModal = () => {
  router.push('/orders/create');
};

const router = useRouter();

const viewOrder = (id) => {
  router.push(`/orders/${id}`);
};

const editOrder = (id) => {
  router.push(`/orders/${id}/edit`);
};

const confirmDelete = (order) => {
  orderToDelete.value = order;
  showDeleteModal.value = true;
};

const deleteOrder = async () => {
  if (!orderToDelete.value) return;
  
  deleting.value = true;
  
  try {
    const response = await axios.delete(`/api/orders/${orderToDelete.value.id}`);
    
    if (response.data.success) {
      showDeleteModal.value = false;
      orderToDelete.value = null;
      fetchOrders(pagination.value.current_page);
    }
  } catch (error) {
    console.error('Error deleting order:', error);
    alert(error.response?.data?.message || 'Failed to delete order');
  } finally {
    deleting.value = false;
  }
};

watch(() => filters.value.search, () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.value.current_page = 1;
    fetchOrders(1);
  }, 300);
});

watch([() => filters.value.status, () => filters.value.priority, () => filters.value.date_from, () => filters.value.date_to], () => {
  pagination.value.current_page = 1;
  fetchOrders(1);
});

onMounted(() => {
  fetchOrders(1);
});

onUnmounted(() => {
  clearTimeout(searchTimeout);
});
</script>
