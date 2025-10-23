# Material Field Names Fix

## Issue

The Material dropdown was not showing names because of a mismatch between the Material model field names and what the frontend was expecting.

### Material Model Fields (Actual)
```php
- id
- material_id  (code/identifier)
- name         (display name)
- department_id
- status
```

### Frontend Was Expecting (Wrong)
```javascript
- material_code  ❌
- material_name  ❌
```

## API Response

### Materials Dropdown Endpoint: `/api/materials/dropdown`
```json
{
  "id": 1,
  "name": "Art Paper 130 GSM",           ← Correct field
  "material_id": "MAT001",                ← Could be used as code
  "department": {
    "id": 1,
    "name": "Paper Stock"
  }
}
```

## Files Fixed

### 1. FormResource.php ✅
Updated material transformation to use correct field names:
```php
'material' => [
    'id' => $this->material->id,
    'material_id' => $this->material->material_id,  // Code
    'name' => $this->material->name,                // Name
    // Aliases for compatibility
    'material_code' => $this->material->material_id,
    'material_name' => $this->material->name,
]
```

### 2. Create.vue ✅
Fixed dropdown option display:
```vue
<!-- Before (Wrong) -->
<option>{{ material.material_code }} - {{ material.material_name }}</option>

<!-- After (Correct) -->
<option>{{ material.material_id || material.id }} - {{ material.name }}</option>
```

### 3. Edit.vue ✅
Same fix as Create.vue:
```vue
<option>{{ material.material_id || material.id }} - {{ material.name }}</option>
```

### 4. Show.vue ✅
Fixed material display with fallbacks:
```vue
<!-- Name -->
<p>{{ form.material.material_name || form.material.name }}</p>

<!-- Code -->
<p>Code: {{ form.material.material_code || form.material.material_id }}</p>
```

## How It Works Now

### Dropdown Display Format
```
MAT001 - Art Paper 130 GSM
MAT002 - Art Paper 170 GSM
INK001 - Offset Ink - Black
```

### Material Object Structure
```javascript
{
  id: 1,
  material_id: "MAT001",      // Used as code
  name: "Art Paper 130 GSM",   // Display name
  // Aliases for backward compatibility
  material_code: "MAT001",
  material_name: "Art Paper 130 GSM"
}
```

## Backward Compatibility

The FormResource now provides **both** field naming conventions:
- `material_id` and `name` (actual database fields)
- `material_code` and `material_name` (aliases for compatibility)

The frontend uses fallbacks to support both:
```vue
{{ material.name || material.material_name }}
```

## Expected Display

### Create/Edit Form Dropdown
```
┌─────────────────────────────────────────────┐
│ Select Material:                            │
│ [MAT001 - Art Paper 130 GSM        ▼]      │
│  MAT001 - Art Paper 130 GSM                │
│  MAT002 - Art Paper 170 GSM                │
│  MAT003 - Maplitho 80 GSM                  │
│  INK001 - Offset Ink - Black               │
│  INK002 - Offset Ink - Cyan                │
└─────────────────────────────────────────────┘
```

### Show Page Material Card
```
┌─────────────────────────────────────────────┐
│ 📦 Material                                 │
├─────────────────────────────────────────────┤
│ [Icon] Art Paper 130 GSM                   │
│        Code: MAT001                         │
└─────────────────────────────────────────────┘
```

## Testing Checklist

- [x] Material dropdown shows names correctly
- [x] Material dropdown shows codes/IDs
- [x] Create form displays materials properly
- [x] Edit form displays materials properly
- [x] Show page displays material name
- [x] Show page displays material code
- [x] No console errors
- [x] Materials load from dropdown API

## Summary

✅ Fixed field name mismatch between Material model and frontend  
✅ Updated FormResource to use correct fields with aliases  
✅ Fixed Create.vue dropdown to display material info  
✅ Fixed Edit.vue dropdown to display material info  
✅ Fixed Show.vue to display material name and code  
✅ Added fallbacks for backward compatibility  

**Status: FIXED** 🎉

Now materials will display correctly in all forms!
