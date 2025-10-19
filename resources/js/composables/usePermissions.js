import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

/**
 * Permission helper composable for checking user permissions
 * Uses Spatie Permission format: 'module.action'
 */
export function usePermissions() {
  const authStore = useAuthStore();
  const user = computed(() => authStore.user);

  /**
   * Check if user has a specific permission
   * @param {string} permission - Permission name (e.g., 'user-menu.view')
   * @returns {boolean}
   */
  const hasPermission = (permission) => {
    if (!user.value) return false;
    
    const permissions = user.value.permissions || user.value.permission_list || [];
    return permissions.includes(permission);
  };

  /**
   * Check if user has any of the given permissions
   * @param {Array<string>} permissions - Array of permission names
   * @returns {boolean}
   */
  const hasAnyPermission = (permissions) => {
    if (!user.value) return false;
    
    const userPermissions = user.value.permissions || user.value.permission_list || [];
    return permissions.some(permission => userPermissions.includes(permission));
  };

  /**
   * Check if user has all of the given permissions
   * @param {Array<string>} permissions - Array of permission names
   * @returns {boolean}
   */
  const hasAllPermissions = (permissions) => {
    if (!user.value) return false;
    
    const userPermissions = user.value.permissions || user.value.permission_list || [];
    return permissions.every(permission => userPermissions.includes(permission));
  };

  /**
   * Check if user has a specific role
   * @param {string} roleName - Role name (e.g., 'Super Admin')
   * @returns {boolean}
   */
  const hasRole = (roleName) => {
    if (!user.value) return false;
    
    const roles = user.value.roles || [];
    return roles.some(role => role.name === roleName);
  };

  /**
   * Check if user has any of the given roles
   * @param {Array<string>} roleNames - Array of role names
   * @returns {boolean}
   */
  const hasAnyRole = (roleNames) => {
    if (!user.value) return false;
    
    const roles = user.value.roles || [];
    return roleNames.some(roleName => 
      roles.some(role => role.name === roleName)
    );
  };

  /**
   * Get all user permissions
   * @returns {Array<string>}
   */
  const getAllPermissions = () => {
    if (!user.value) return [];
    return user.value.permissions || user.value.permission_list || [];
  };

  /**
   * Get all user roles
   * @returns {Array<Object>}
   */
  const getAllRoles = () => {
    if (!user.value) return [];
    return user.value.roles || [];
  };

  /**
   * Check if user is super admin
   * @returns {boolean}
   */
  const isSuperAdmin = () => {
    return hasRole('Super Admin');
  };

  return {
    hasPermission,
    hasAnyPermission,
    hasAllPermissions,
    hasRole,
    hasAnyRole,
    getAllPermissions,
    getAllRoles,
    isSuperAdmin,
  };
}
