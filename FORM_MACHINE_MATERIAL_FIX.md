# Form Machine and Material Selection Fix

## Summary of Changes

Fixed the form creation/edit functionality to:
- **Allow MULTIPLE machines** to be selected (was single selection)
- **Allow SINGLE material** to be selected (was allowing multiple)

## Files Modified

### 1. Backend Models

#### `/app/Models/FormMaterial.php` (NEW)
- Created new pivot model for form-material relationship
- Handles material assignments with quantity tracking

#### `/app/Models/Form.php`
- Added `materials()` relationship method for many-to-many with materials
- Kept existing `material()` belongsTo relationship for backward compatibility
- Now supports both single material reference (`material_id`) and pivot table

### 2. Backend Controller

#### `/app/Http/Controllers/FormController.php`
- Updated `store()` method:
  - Changed from single `machine_id` to array `machine_ids`
  - Changed from multiple materials to single `material_id`
  - Added proper array checks before attaching machines
  
- Updated `update()` method:
  - Uses `sync()` for multiple machines
  - Updates single `material_id` field
  
- Updated all `load()` calls to include both `material` and `materials` relationships

### 3. Frontend - Create Form

#### `/resources/js/Pages/Forms/Create.vue`

**Script Changes:**
- Changed `machine_id: ''` to `machine_ids: []` (array for multiple)
- Changed from `material_ids/material_quantities` to single `material_id`
- Removed material management functions (`addMaterial`, `removeMaterial`)
- Removed computed `availableMaterials`
- Removed `assignedMaterials`, `selectedMaterial`, `materialQuantity` refs

**Template Changes:**
- **Machine Assignment Section:**
  - Changed from dropdown to checkbox list
  - Shows all machines with checkboxes
  - Displays count of selected machines
  - Green color scheme for visual distinction
  
- **Material Assignment Section:**
  - Changed from multi-select with quantity to simple dropdown
  - Single material selection only
  - No quantity input needed

### 4. Frontend - Edit Form

#### `/resources/js/Pages/Forms/Edit.vue`

**Script Changes:**
- Changed `machine_id: ''` to `machine_ids: []`
- Added `material_id: ''` for single material
- Updated `fetchFormData()` to map machines array: `form.machines?.map(m => m.id)`
- Added materials to dropdown data fetch
- Added `materials` ref

**Template Changes:**
- Same checkbox-based machine selection as Create form
- Same single dropdown material selection as Create form

### 5. Database Migration

#### `/database/migrations/2025_01_01_000001_create_form_material_table.php` (NEW)
- Creates `form_material` pivot table
- Fields: `id`, `form_id`, `material_id`, `quantity`, `timestamps`
- Includes proper indexes and unique constraint
- Cascade delete when form or material is deleted

## Database Schema

### form_material (NEW)
```sql
- id (bigint, primary key)
- form_id (bigint, foreign key -> forms.id, cascade delete)
- material_id (bigint, foreign key -> materials.id, cascade delete)
- quantity (decimal 10,2)
- created_at (timestamp)
- updated_at (timestamp)
- UNIQUE(form_id, material_id)
```

### forms (EXISTING)
The `material_id` column remains for single material reference.

## API Changes

### POST /api/forms (Create Form)
**Request Body:**
```json
{
  "form_name": "string",
  "section_id": "integer",
  "schedule_date": "date",
  "excepted_qty": "integer|nullable",
  "description": "string|nullable",
  "machine_ids": [1, 2, 3],  // Changed from machine_id
  "operator_ids": [1, 2],
  "material_id": 1  // Changed from material_ids array
}
```

### PUT /api/forms/{id} (Update Form)
**Request Body:**
```json
{
  "form_name": "string",
  "schedule_date": "date",
  "excepted_qty": "integer|nullable",
  "description": "string|nullable",
  "machine_ids": [1, 2, 3],  // Changed from machine_id
  "operator_ids": [1, 2],
  "material_id": 1  // Changed from material_ids array
}
```

## How It Works

### Multiple Machines
1. User checks/unchecks machines from the list
2. `machine_ids` array is populated with selected machine IDs
3. Backend uses `attach()` on create and `sync()` on update
4. Stored in `form_machine` pivot table

### Single Material
1. User selects one material from dropdown
2. `material_id` is stored directly in the `forms` table
3. Backward compatible with existing data

## Migration Instructions

1. **Run the migration:**
   ```bash
   php artisan migrate
   ```

2. **Clear any cached data:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Rebuild frontend assets:**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

## Testing Checklist

- [ ] Create new form with multiple machines
- [ ] Create new form with single material
- [ ] Create new form with no machines/materials (optional fields)
- [ ] Edit existing form - add/remove machines
- [ ] Edit existing form - change material
- [ ] Verify machines are displayed in checkbox list
- [ ] Verify material is displayed in dropdown
- [ ] Check that form submission sends correct data format
- [ ] Verify database stores multiple machines in pivot table
- [ ] Verify material_id is stored in forms table

## Backward Compatibility

- The `material_id` column in forms table is preserved
- The `materials()` relationship is additional, doesn't break existing code
- Existing forms with `material_id` will continue to work
- The form_machine pivot table already existed and is unchanged

## Notes

- Machines use green color scheme to differentiate from operators (blue)
- Material selection is simpler (single dropdown) vs previous complex multi-add interface
- All assignments are optional - forms can be created without machines/materials
- The UI clearly labels sections as "(Multiple)" or "(Single)" for clarity
