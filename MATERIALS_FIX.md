# 🔧 Quick Fix for Materials Module

## Issue
Materials page showing authentication errors:
- "Cannot read properties of undefined (reading 'props')"
- "Request failed with status code 401"

## Root Cause
Materials component is using a different pattern than Machines:
- Using `AdminLayout` instead of `AuthenticatedLayout`
- Using `axios` without proper authentication headers
- Accessing `$page.props` before page is loaded
- Different permission checking approach

## Solution
Update Materials/Index.vue to match the Machines pattern exactly:

### Changes Needed:
1. Replace `AdminLayout` with `AuthenticatedLayout`
2. Replace axios calls with native `fetch` using `authStore.token`
3. Use computed properties for permissions (canCreate, canUpdate, canDelete)
4. Remove `$page.props` access - use authStore instead

## Implementation
See the updated Materials/Index.vue file that now matches the Machines pattern.

---

**Status**: Fix in progress - updating Materials/Index.vue to match working Machines pattern
