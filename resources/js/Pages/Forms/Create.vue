<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
          <router-link to="/forms" class="text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </router-link>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Create Production Form</h1>
        </div>
        <p class="text-sm text-gray-600">Fill in the details to create a new production job</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Basic Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Section -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Section <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.section_id"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.section_id }"
              >
                <option value="">Select Section</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">
                  {{ section.section_no }} - {{ section.section_name }} (Order: {{ section.order?.job_card_no }})
                </option>
              </select>
              <p v-if="errors.section_id" class="mt-1 text-sm text-red-600">{{ errors.section_id[0] }}</p>
            </div>

            <!-- Form Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Form Number <span class="text-red-500">*</span>
              </label>
              <div class="flex gap-2">
                <input
                  v-model="formData.form_no"
                  type="text"
                  required
                  placeholder="F-001"
                  class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.form_no }"
                />
                <button type="button" @click="generateFormNo" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                  Generate
                </button>
              </div>
              <p v-if="errors.form_no" class="mt-1 text-sm text-red-600">{{ errors.form_no[0] }}</p>
            </div>

            <!-- Schedule Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Schedule Date <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.schedule_date"
                type="date"
                required
                :min="today"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.schedule_date }"
              />
              <p v-if="errors.schedule_date" class="mt-1 text-sm text-red-600">{{ errors.schedule_date[0] }}</p>
            </div>

            <!-- Form Name -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Form Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.form_name"
                type="text"
                required
                placeholder="Production Job Name"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.form_name }"
              />
              <p v-if="errors.form_name" class="mt-1 text-sm text-red-600">{{ errors.form_name[0] }}</p>
            </div>
          </div>
        </div>

        <!-- Machine Assignment Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Machine Assignment</h2>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Select Machine (Optional)</label>
            <select
              v-model="formData.machine_id"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            >
              <option value="">No Machine Assigned</option>
              <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                {{ machine.machine_id }} - {{ machine.machine_name }} ({{ machine.machine_type?.name }})
              </option>
            </select>
          </div>
        </div>

        <!-- Operators Assignment Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Operators Assignment</h2>
          
          <div class="space-y-2 max-h-64 overflow-y-auto">
            <label v-for="operator in operators" :key="operator.id" class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
              <input
                type="checkbox"
                :value="operator.id"
                v-model="formData.operator_ids"
                class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500"
              />
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ operator.name }}</div>
                <div class="text-sm text-gray-500">{{ operator.emp_code }} - {{ operator.department?.name }}</div>
              </div>
            </label>
          </div>
          
          <div v-if="formData.operator_ids.length > 0" class="mt-3 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
              <strong>{{ formData.operator_ids.length }}</strong> operator(s) selected
            </p>
          </div>
        </div>

        <!-- Materials Assignment Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Materials Assignment</h2>
          
          <!-- Add Material -->
          <div class="flex gap-2 mb-4">
            <select v-model="selectedMaterial" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm">
              <option value="">Select Material</option>
              <option v-for="material in availableMaterials" :key="material.id" :value="material">
                {{ material.material_code }} - {{ material.material_name }}
              </option>
            </select>
            <input
              v-model.number="materialQuantity"
              type="number"
              min="0"
              step="0.01"
              placeholder="Quantity"
              class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm"
            />
            <button type="button" @click="addMaterial" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
              Add
            </button>
          </div>

          <!-- Materials List -->
          <div v-if="assignedMaterials.length > 0" class="space-y-2">
            <div v-for="(item, index) in assignedMaterials" :key="index" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ item.material.material_name }}</div>
                <div class="text-sm text-gray-500">{{ item.material.material_code }} - Qty: {{ item.quantity }}</div>
              </div>
              <button type="button" @click="removeMaterial(index)" class="text-red-600 hover:text-red-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
          
          <div v-else class="text-center py-8 text-gray-500 text-sm">
            No materials assigned yet
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button
            type="submit"
            :disabled="submitting"
            class="flex-1 md:flex-none md:px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="submitting" class="flex items-center justify-center">
              <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
              Creating...
            </span>
            <span v-else>Create Form</span>
          </button>
          <router-link
            to="/forms"
            class="flex-1 md:flex-none md:px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center"
          >
            Cancel
          </router-link>
        </div>
      </form>
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
      formData: {
        section_id: '',
        form_no: '',
        form_name: '',
        schedule_date: '',
        machine_id: '',
        operator_ids: [],
        material_ids: [],
        material_quantities: []
      },
      sections: [],
      machines: [],
      operators: [],
      materials: [],
      assignedMaterials: [],
      selectedMaterial: '',
      materialQuantity: 0,
      errors: {},
      submitting: false,
      today: new Date().toISOString().split('T')[0]
    };
  },
  computed: {
    availableMaterials() {
      const assignedIds = this.assignedMaterials.map(item => item.material.id);
      return this.materials.filter(m => !assignedIds.includes(m.id));
    }
  },
  mounted() {
    this.fetchDropdownData();
  },
  methods: {
    async fetchDropdownData() {
      try {
        const [sections, machines, operators, materials] = await Promise.all([
          axios.get('/api/sections/dropdown'),
          axios.get('/api/machines/dropdown'),
          axios.get('/api/users/dropdown'),
          axios.get('/api/materials/dropdown')
        ]);
        
        this.sections = sections.data.data || [];
        this.machines = machines.data.data || [];
        this.operators = operators.data.data || [];
        this.materials = materials.data.data || [];
      } catch (error) {
        console.error('Error fetching dropdown data:', error);
        alert('Failed to load form data');
      }
    },
    generateFormNo() {
      const timestamp = Date.now().toString().slice(-6);
      this.formData.form_no = `F-${timestamp}`;
    },
    addMaterial() {
      if (!this.selectedMaterial || this.materialQuantity <= 0) {
        alert('Please select a material and enter a valid quantity');
        return;
      }
      
      this.assignedMaterials.push({
        material: this.selectedMaterial,
        quantity: this.materialQuantity
      });
      
      this.selectedMaterial = '';
      this.materialQuantity = 0;
    },
    removeMaterial(index) {
      this.assignedMaterials.splice(index, 1);
    },
    async submitForm() {
      this.errors = {};
      this.submitting = true;

      // Prepare material data
      this.formData.material_ids = this.assignedMaterials.map(item => item.material.id);
      this.formData.material_quantities = this.assignedMaterials.map(item => item.quantity);

      try {
        const response = await axios.post('/api/forms', this.formData);
        
        if (response.data.success) {
          alert('Form created successfully!');
          this.$router.push('/forms');
        }
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors || {};
        } else {
          alert(error.response?.data?.message || 'Failed to create form');
        }
      } finally {
        this.submitting = false;
      }
    }
  }
};
</script>
