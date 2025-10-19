// Update only the permission checking and API calls
// Around line 33 - Update permission check function
const { hasPermission } = usePermissions();

const canCreate = computed(() => hasPermission('user-menu.create'));
const canUpdate = computed(() => hasPermission('user-menu.update'));
const canDelete = computed(() => hasPermission('user-menu.delete'));

// Around line 70-75 - Replace permissions with roles
const roles = ref([]);

// Around line 270-280 - Replace fetchPermissions with fetchRoles
const fetchRoles = async () => {
  try {
    const response = await fetch('/api/roles', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Accept': 'application/json',
      },
    });
    if (response.ok) {
      const data = await response.json();
      roles.value = data.data || [];
    }
  } catch (error) {
    console.error('Failed to fetch roles:', error);
  }
};

// Around line 390 - Update onMounted
onMounted(() => {
  fetchUsers();
  fetchRoles(); // Changed from fetchPermissions
  fetchMachines();
  
  window.addEventListener('scroll', handleScroll);
});
