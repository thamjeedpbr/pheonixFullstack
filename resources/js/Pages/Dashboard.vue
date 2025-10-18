<template>
  <AuthenticatedLayout :user="user">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="mt-2 text-gray-600">Welcome back, {{ user?.name }}! Here's what's happening today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Active Jobs -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Active Jobs</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.activeJobs }}</p>
            <p class="text-sm text-green-600 mt-2">
              <span class="font-medium">+12%</span> from last week
            </p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Machines -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Total Machines</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalMachines }}</p>
            <p class="text-sm text-gray-500 mt-2">
              <span class="font-medium">{{ stats.activeMachines }}</span> active
            </p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Users -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Total Users</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalUsers }}</p>
            <p class="text-sm text-gray-500 mt-2">
              <span class="font-medium">{{ stats.activeUsers }}</span> online
            </p>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Completed Today -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Completed Today</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.completedToday }}</p>
            <p class="text-sm text-green-600 mt-2">
              <span class="font-medium">+8%</span> from yesterday
            </p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Production Chart -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Overview</h3>
        <div class="h-64 flex items-center justify-center text-gray-400">
          <p>Chart will be displayed here</p>
        </div>
      </div>

      <!-- Machine Status -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Machine Status</h3>
        <div class="space-y-4">
          <div v-for="machine in recentMachines" :key="machine.id" class="flex items-center justify-between pb-4 border-b border-gray-200 last:border-0">
            <div class="flex items-center space-x-3">
              <div 
                class="w-3 h-3 rounded-full"
                :class="{
                  'bg-green-500': machine.status === 'running',
                  'bg-yellow-500': machine.status === 'idle',
                  'bg-red-500': machine.status === 'stopped'
                }"
              ></div>
              <div>
                <p class="font-medium text-gray-900">{{ machine.name }}</p>
                <p class="text-sm text-gray-500">{{ machine.type }}</p>
              </div>
            </div>
            <span 
              class="px-3 py-1 text-xs font-medium rounded-full"
              :class="{
                'bg-green-100 text-green-800': machine.status === 'running',
                'bg-yellow-100 text-yellow-800': machine.status === 'idle',
                'bg-red-100 text-red-800': machine.status === 'stopped'
              }"
            >
              {{ machine.status.toUpperCase() }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
      <div class="space-y-4">
        <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-4 pb-4 border-b border-gray-200 last:border-0">
          <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ activity.description }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// State
const user = ref(null);
const loading = ref(true);

// Stats data
const stats = ref({
  activeJobs: 24,
  totalMachines: 12,
  activeMachines: 8,
  totalUsers: 45,
  activeUsers: 12,
  completedToday: 18,
});

// Recent machines
const recentMachines = ref([
  { id: 1, name: 'Heidelberg SM102', type: 'Offset Press', status: 'running' },
  { id: 2, name: 'Komori Lithrone', type: 'Offset Press', status: 'running' },
  { id: 3, name: 'Bobst Die Cutter', type: 'Die Cutting', status: 'idle' },
  { id: 4, name: 'Lamination Unit 1', type: 'Lamination', status: 'stopped' },
]);

// Recent activities
const recentActivities = ref([
  {
    id: 1,
    title: 'Job #1234 Started',
    description: 'Operator John started production on Heidelberg SM102',
    time: '5 minutes ago',
  },
  {
    id: 2,
    title: 'Quality Check Completed',
    description: 'QC verification passed for Job #1220',
    time: '15 minutes ago',
  },
  {
    id: 3,
    title: 'New Order Created',
    description: 'Order #5678 created by Admin User',
    time: '1 hour ago',
  },
  {
    id: 4,
    title: 'Machine Maintenance',
    description: 'Scheduled maintenance completed on Komori Lithrone',
    time: '2 hours ago',
  },
]);

// Fetch dashboard data on mount
onMounted(async () => {
  // Check if user is logged in
  const token = localStorage.getItem('auth_token');
  const storedUser = localStorage.getItem('user');
  
  if (!token) {
    // No token, redirect to login
    window.location.href = '/login';
    return;
  }
  
  // Use stored user data initially
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }
  
  // Fetch fresh user data from API
  try {
    const response = await fetch('/api/auth/me', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    if (response.ok) {
      const data = await response.json();
      if (data.success && data.data) {
        user.value = data.data;
        // Update localStorage
        localStorage.setItem('user', JSON.stringify(data.data));
      }
    } else {
      // Token invalid, redirect to login
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
  } catch (error) {
    console.error('Failed to fetch user:', error);
  } finally {
    loading.value = false;
  }
  
  // TODO: Fetch real dashboard data from API
  // await fetchDashboardStats();
});
</script>

<style scoped>
/* Custom styles if needed */
</style>
