<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
          <router-link :to="`/forms/${id}`" class="text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </router-link>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Form</h1>
        </div>
        <p class="text-sm text-gray-600">Update production form details</p>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Form -->
      <form v-else-if="formData.form_no" @submit.prevent="submitForm" class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Section <span class="text-red-500">*</span>
              </label>
              <select v-model="formData.section_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">Select Section</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">
                  {{ section.section_no }} - {{ section.section_name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Form Number <span class="text-red-500">*</span>
              </label>
              <input v-model="formData.form_no" type="text" required class="w-full rounded-lg border border-gray-300 px-3 py-2" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Schedule Date <span class="text-red-500">*</span>
              </label>
              <input v-model="formData.schedule_date" type="date" required :min="today" class="w-full rounded-lg border border-gray-300 px-3 py-2" />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Form Name <span class="text-red-500">*</span>
              </label>
              <input v-model="formData.form_name" type="text" required class="w-full rounded-lg border border-gray-300 px-3 py-2" />
            </div>
          </div>
        </div>

        <!-- Machine Assignment -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Machine Assignment</h2>
          <select v-model="formData.machine_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="">No Machine Assigned</option>
            <option v-for="machine in machines" :key="machine.id" :value="machine.id">
              {{ machine.machine_id }} - {{ machine.machine_name }}
            </option>
          </select>
        </div>

        <!-- Operators -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Operators Assignment</h2>
          <div class="space-y-2 max-h-64 overflow-y-auto">
            <label v-for="operator in operators" :key="operator.id" class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
              <input type="checkbox" :value="operator.id" v-model="formData.operator_ids" class="h-4 w-4 text-blue-600 rounded" />
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ operator.name }}</div>
                <div class="text-sm text-gray-500">{{ operator.emp_code }}</div>
              </div>
            </label>
          </div>
          <div v-if="formData.operator_ids.length > 0" class="mt-3 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800"><strong>{{ formData.operator_ids.length }}</strong> operator(s) selected</p>
          </div>
        </div>

        <!-- Materials -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Materials Assignment</h2>
          
          <div class="flex gap-2 mb-4">
            <select v-model="selectedMaterial" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm">
              <option value="">Select Material</option>
              <option v-for="material in availableMaterials" :key="material.id" :value="material">
                {{ material.material_code }} - {{ material.material_name }}
              </option>
            </select>
            <input v-model.number="materialQuantity" type="number" min="0" step="0.01" placeholder="Quantity" class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm" />
            <button type="button" @click="addMaterial" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
              Add
            </button>
          </div>

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
          <div v-else class="text-center py-8 text-gray-500 text-sm">No materials assigned</div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button type="submit" :disabled="submitting" class="flex-1 md:flex-none md:px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium disabled:opacity-50">
            {{ submitting ? 'Updating...' : 'Update Form' }}
          </button>
          <router-link :to="`/forms/${id}`" class="flex-1 md:flex-none md:px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center">
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
  props: ['id'],
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
      loading: false,
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
    this.fetchForm();
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
        console.error('Error:', error);
      }
    },
    async fetchForm() {
      this.loading = true;
      try {
        const response = await axios.get(`/api/forms/${this.id}`);
        if (response.data.success) {
          const form = response.data.data;
          this.formData = {
            section_id: form.section?.id || '',
            form_no: form.form_no,
            form_name: form.form_name,
            schedule_date: form.schedule_date,
            machine_id: form.machine?.id || '',
            operator_ids: form.operators?.map(op => op.id) || [],
            material_ids: [],
            material_quantities: []
          };
          
          // Pre-fill materials
          if (form.materials && form.materials.length > 0) {
            this.assignedMaterials = form.materials.map(mat => ({
              material: mat,
              quantity: mat.quantity_assigned
            }));
          }
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Failed to fetch form');
        this.$router.push('/forms');
      } finally {
        this.loading = false;
      }
    },
    addMaterial() {
      if (!this.selectedMaterial || this.materialQuantity <= 0) {
        alert('Please select material and enter quantity');
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

      this.formData.material_ids = this.assignedMaterials.map(item => item.material.id);
      this.formData.material_quantities = this.assignedMaterials.map(item => item.quantity);

      try {
        const response = await axios.put(`/api/forms/${this.id}`, this.formData);
        
        if (response.data.success) {
          alert('Form updated successfully!');
          this.$router.push(`/forms/${this.id}`);
        }
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors || {};
        } else {
          alert(error.response?.data?.message || 'Failed to update form');
        }
      } finally {
        this.submitting = false;
      }
    }
  }
};
</script>
