<template>
  <AuthenticatedLayout>
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
              <span class="font-medium">{{ stats.onlineUsers }}</span> online
            </p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Production Overview -->
      <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Overview</h3>
        <div class="h-64 flex items-center justify-center text-gray-500">
          <p>Chart placeholder - Install Chart.js or similar</p>
        </div>
      </div>

      <!-- Machine Status -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Machine Status</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
            <div class="flex items-center space-x-3">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-900">Press Machine 1</span>
            </div>
            <span class="text-xs text-green-600 font-medium">Running</span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
            <div class="flex items-center space-x-3">
              <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
              <span class="text-sm font-medium text-gray-900">Press Machine 2</span>
            </div>
            <span class="text-xs text-yellow-600 font-medium">Setup</span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
            <div class="flex items-center space-x-3">
              <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-sm font-medium text-gray-900">Die Cut 1</span>
            </div>
            <span class="text-xs text-green-600 font-medium">Running</span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
              <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
              <span class="text-sm font-medium text-gray-900">Die Cut 2</span>
            </div>
            <span class="text-xs text-gray-600 font-medium">Idle</span>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="lg:col-span-3 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4">
          <div class="flex items-start space-x-3 pb-4 border-b border-gray-200">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">Job #12345 completed</p>
              <p class="text-xs text-gray-500 mt-1">Completed by Operator 1 - 2 minutes ago</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3 pb-4 border-b border-gray-200">
            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">Machine alert: Press Machine 2</p>
              <p class="text-xs text-gray-500 mt-1">Low material warning - 15 minutes ago</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">New order created: #ORD-2024-001</p>
              <p class="text-xs text-gray-500 mt-1">Created by Supervisor 1 - 1 hour ago</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const authStore = useAuthStore();
const user = computed(() => authStore.user);

// Mock stats data
const stats = reactive({
  activeJobs: 24,
  totalMachines: 12,
  activeMachines: 8,
  totalUsers: 18,
  onlineUsers: 5,
  completedToday: 42,
});

// TODO: Fetch real dashboard data from API
</script>
