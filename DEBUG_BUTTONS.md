# Quick Debug Guide

## Temporarily Show Buttons (For Testing)

Replace these lines in `/resources/js/Pages/Users/Index.vue`:

```javascript
// TEMPORARY - Remove after debugging
const canCreate = computed(() => true);
const canUpdate = computed(() => true);
const canDelete = computed(() => true);
```

## Check User Permissions in Browser Console

Open browser console and type:
```javascript
// Check if user is loaded
console.log('User:', JSON.stringify(window.$pinia?.state?.value?.auth?.user, null, 2));

// Check permissions
console.log('Permissions:', window.$pinia?.state?.value?.auth?.user?.permissions);
console.log('Permission List:', window.$pinia?.state?.value?.auth?.user?.permission_list);
console.log('Roles:', window.$pinia?.state?.value?.auth?.user?.roles);
```

## What to Check

1. **Login Response** - Check network tab after login, look for `/api/auth/login` response:
   - Should contain `user.permissions` array
   - Should contain `user.roles` array

2. **Me Endpoint** - Check `/api/auth/me` response:
   - Same structure as login

## If Permissions Are Empty

The user might not have roles assigned. Run:
```bash
php artisan tinker
```

```php
$user = \App\Models\User::where('user_name', 'admin')->first();
$user->roles;  // Should show roles
$user->getAllPermissions()->pluck('name');  // Should show permissions
```

If empty, reassign role:
```php
$user->syncRoles(['Super Admin']);
```
