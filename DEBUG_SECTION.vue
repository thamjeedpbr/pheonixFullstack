<!-- Add this right after the header section in Users/Index.vue for debugging -->
<!-- DEBUG INFO - Remove after fixing -->
<div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-xs">
  <p><strong>Debug Info:</strong></p>
  <p>User ID: {{ authStore.user?.id }}</p>
  <p>User Name: {{ authStore.user?.user_name }}</p>
  <p>Roles: {{ JSON.stringify(authStore.user?.roles) }}</p>
  <p>Permissions: {{ JSON.stringify(authStore.user?.permissions) }}</p>
  <p>Permission List: {{ JSON.stringify(authStore.user?.permission_list) }}</p>
  <p class="mt-2">
    <span class="font-semibold">Can Create:</span> {{ canCreate }} | 
    <span class="font-semibold">Can Update:</span> {{ canUpdate }} | 
    <span class="font-semibold">Can Delete:</span> {{ canDelete }}
  </p>
</div>
