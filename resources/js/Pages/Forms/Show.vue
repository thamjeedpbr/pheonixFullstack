<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto">
      <!-- Loading -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <div v-else-if="form">
        <!-- Header -->
        <div class="mb-6">
          <div class="flex items-center gap-3 mb-2">
            <router-link to="/forms" class="text-gray-600 hover:text-gray-900">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
            </router-link>
            <div class="flex-1">
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ form.form_no }}</h1>
              <p class="text-sm text-gray-600 mt-1">{{ form.form_name }}</p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full" :class="getStatusClass(form.status)">
              {{ form.status_label }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Order & Section Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Order & Section Details</h2>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Section</p>
                  <p class="font-medium text-gray-900">{{ form.section?.section_no }} - {{ form.section?.section_name }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Job Card</p>
                  <p class="font-medium text-gray-900">{{ form.section?.order?.job_card_no }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Client</p>
                  <p class="font-medium text-gray-900">{{ form.section?.order?.client_name }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Schedule Date</p>
                  <p class="font-medium text-gray-900">{{ form.schedule_date_formatted }}</p>
                </div>
              </div>
            </div>

            <!-- Machine Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Machine Assignment</h2>
              <div v-if="form.machine" class="flex items-center gap-4">
                <div class="flex-shrink-0">
                  <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">{{ form.machine.machine_id }}</p>
                  <p class="text-sm text-gray-600">{{ form.machine.machine_name }}</p>
                  <p class="text-xs text-gray-500">{{ form.machine.machine_type?.name }}</p>
                </div>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No machine assigned</div>
            </div>

            <!-- Operators Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Assigned Operators</h2>
              <div v-if="form.operators && form.operators.length > 0" class="space-y-3">
                <div v-for="operator in form.operators" :key="operator.id" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                      <span class="text-green-700 font-medium text-sm">{{ operator.name.charAt(0) }}</span>
                    </div>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">{{ operator.name }}</p>
                    <p class="text-sm text-gray-500">{{ operator.emp_code }} - {{ operator.department }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No operators assigned</div>
            </div>

            <!-- Materials Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Assigned Materials</h2>
              <div v-if="form.materials && form.materials.length > 0" class="overflow-x-auto">
                <table class="min-w-full">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material</th>
                      <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-for="material in form.materials" :key="material.id">
                      <td class="px-4 py-3 text-sm text-gray-900">{{ material.material_code }}</td>
                      <td class="px-4 py-3 text-sm text-gray-900">{{ material.material_name }}</td>
                      <td class="px-4 py-3 text-sm text-right text-gray-900">{{ material.quantity_assigned }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No materials assigned</div>
            </div>

            <!-- Action History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Action History</h2>
              <div v-if="history.length > 0" class="space-y-4">
                <div v-for="action in history" :key="action.id" class="flex gap-3">
                  <div class="flex-shrink-0">
                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                  </div>
                  <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ action.action_name }}</p>
                    <p class="text-sm text-gray-600">{{ action.user.name }} ({{ action.user.emp_code }})</p>
                    <p v-if="action.reason" class="text-sm text-gray-500 mt-1">Reason: {{ action.reason }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ action.timestamp_formatted }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-4 text-gray-500">No actions recorded yet</div>
            </div>
          </div>

          <!-- Operation Panel Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-20">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Production Controls</h2>

              <!-- job_pending -->
              <div v-if="form.status === 'job_pending'" class="space-y-3">
                <button @click="handleMakeReady" :disabled="actionLoading" class="w-full px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium disabled:opacity-50">
                  {{ actionLoading ? 'Processing...' : 'Make Ready' }}
                </button>
              </div>

              <!-- make_ready -->
              <div v-else-if="form.status === 'make_ready'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ✓ Make Ready Complete
                </button>
                <button @click="handleStartProduction" :disabled="actionLoading" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium disabled:opacity-50">
                  {{ actionLoading ? 'Starting...' : 'Start Production' }}
                </button>
              </div>

              <!-- job_started -->
              <div v-else-if="form.status === 'job_started'" class="space-y-3">
                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed text-sm">
                  ✓ Make Ready
                </button>
                <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed text-sm">
                  ✓ Production Running
                </button>
                <button @click="showPauseModal = true" class="w-full px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                  Pause Production
                </button>
                <button @click="showStopModal = true" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                  Stop Production
                </button>
                <button @click="handleComplete" :disabled="actionLoading" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium disabled:opacity-50">
                  {{ actionLoading ? 'Completing...' : 'Complete Production' }}
                </button>
              </div>

              <!-- paused -->
              <div v-else-if="form.status === 'paused'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ⏸ Production Paused
                </button>
                <button @click="handleResume" :disabled="actionLoading" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium disabled:opacity-50">
                  {{ actionLoading ? 'Resuming...' : 'Resume Production' }}
                </button>
              </div>

              <!-- stopped -->
              <div v-else-if="form.status === 'stopped'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ⏹ Production Stopped
                </button>
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                  <p class="text-sm text-red-800">Form stopped. Create a new form to continue.</p>
                </div>
              </div>

              <!-- job_completed -->
              <div v-else-if="form.status === 'job_completed'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ✓ Production Completed
                </button>
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                  <p class="text-sm text-blue-800">Ready for quality verification</p>
                </div>
              </div>

              <!-- quality_verified -->
              <div v-else-if="form.status === 'quality_verified'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ✓ Quality Verified
                </button>
                <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg">
                  <p class="text-sm text-purple-800">Ready for line clearance</p>
                </div>
              </div>

              <!-- line_cleared -->
              <div v-else-if="form.status === 'line_cleared'" class="space-y-3">
                <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                  ✓ Line Cleared
                </button>
                <div class="p-3 bg-teal-50 border border-teal-200 rounded-lg">
                  <p class="text-sm text-teal-800">Form complete</p>
                </div>
              </div>

              <!-- Actions -->
              <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                <router-link :to="`/forms/${form.id}/edit`" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center font-medium">
                  Edit Form
                </router-link>
                <button v-if="form.status === 'job_pending'" @click="confirmDelete" class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 font-medium">
                  Delete Form
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pause Modal -->
      <div v-if="showPauseModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showPauseModal = false"></div>
          <div class="relative bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Pause Production</h3>
            <textarea v-model="pauseReason" rows="4" placeholder="Enter reason..." class="w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
            <div class="flex gap-3 mt-4">
              <button @click="confirmPause" :disabled="!pauseReason || actionLoading" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 disabled:opacity-50">
                Confirm
              </button>
              <button @click="showPauseModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Stop Modal -->
      <div v-if="showStopModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showStopModal = false"></div>
          <div class="relative bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Stop Production</h3>
            <p class="text-sm text-red-600 mb-4">Warning: Stopping is permanent.</p>
            <textarea v-model="stopReason" rows="4" placeholder="Enter reason..." class="w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
            <div class="flex gap-3 mt-4">
              <button @click="confirmStop" :disabled="!stopReason || actionLoading" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50">
                Confirm
              </button>
              <button @click="showStopModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showDeleteModal = false"></div>
          <div class="relative bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Delete Form</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure? This cannot be undone.</p>
            <div class="flex gap-3">
              <button @click="deleteForm" :disabled="deleting" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50">
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
              <button @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
              </button>
            </div>
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
  props: ['id'],
  data() {
    return {
      form: null,
      history: [],
      loading: false,
      actionLoading: false,
      deleting: false,
      showPauseModal: false,
      showStopModal: false,
      showDeleteModal: false,
      pauseReason: '',
      stopReason: ''
    };
  },
  mounted() {
    this.fetchForm();
    this.fetchHistory();
  },
  methods: {
    async fetchForm() {
      this.loading = true;
      try {
        const response = await axios.get(`/api/forms/${this.id}`);
        if (response.data.success) {
          this.form = response.data.data;
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Failed to fetch form');
        this.$router.push('/forms');
      } finally {
        this.loading = false;
      }
    },
    async fetchHistory() {
      try {
        const response = await axios.get(`/api/forms/${this.id}/history`);
        if (response.data.success) {
          this.history = response.data.data;
        }
      } catch (error) {
        console.error('Error fetching history:', error);
      }
    },
    async changeStatus(newStatus, reason = null) {
      this.actionLoading = true;
      try {
        const response = await axios.patch(`/api/forms/${this.id}/status`, {
          status: newStatus,
          reason: reason
        });
        if (response.data.success) {
          await this.fetchForm();
          await this.fetchHistory();
          alert(`Status changed to ${newStatus}`);
        }
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to change status');
      } finally {
        this.actionLoading = false;
      }
    },
    async handleMakeReady() {
      if (confirm('Start make ready phase?')) {
        await this.changeStatus('make_ready');
      }
    },
    async handleStartProduction() {
      if (confirm('Start production?')) {
        await this.changeStatus('job_started');
      }
    },
    async confirmPause() {
      await this.changeStatus('paused', this.pauseReason);
      this.showPauseModal = false;
      this.pauseReason = '';
    },
    async confirmStop() {
      await this.changeStatus('stopped', this.stopReason);
      this.showStopModal = false;
      this.stopReason = '';
    },
    async handleResume() {
      if (confirm('Resume production?')) {
        await this.changeStatus('job_started');
      }
    },
    async handleComplete() {
      if (confirm('Complete production?')) {
        await this.changeStatus('job_completed');
      }
    },
    confirmDelete() {
      this.showDeleteModal = true;
    },
    async deleteForm() {
      this.deleting = true;
      try {
        await axios.delete(`/api/forms/${this.id}`);
        alert('Form deleted');
        this.$router.push('/forms');
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to delete');
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
