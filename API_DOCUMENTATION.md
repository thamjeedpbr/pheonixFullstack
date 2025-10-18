# API Documentation - Phoenix Manufacturing System (Part 2)

## Third-party Integration (Continued)

### 35. Receive Third-party Data (Continued)
**Response** (200):
```json
{
  "success": true,
  "message": "Data received and queued for processing",
  "data": {
    "received_count": 1,
    "processed_count": 1,
    "failed_count": 0,
    "records": [
      {
        "unq_id": "TP-12345",
        "status": "processed",
        "created_order_id": 125,
        "created_sections": ["SEC-001"],
        "created_forms": [567, 568]
      }
    ]
  }
}
```

---

## DMI (Device-Machine Integration) Endpoints

### 36. Create DMI Data Entry
**POST** `/api/v1/dmi-data`

**Permission Required**: `dmi_create`

**Request Body**:
```json
{
  "form_id": 567,
  "machine_id": 5,
  "start_time": "2025-01-20T08:30:00",
  "end_time": "2025-01-20T10:30:00",
  "speed": "5000",
  "employee_count": "3",
  "good_lm": "450",
  "bad_lm": "25",
  "ups": "2",
  "active_description": "running",
  "status_id": 1,
  "sub_status_id": 3,
  "remark": "Normal production run"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "DMI data recorded successfully",
  "data": {
    "id": 890,
    "form_id": 567,
    "machine_id": 5,
    "start_time": "2025-01-20T08:30:00",
    "end_time": "2025-01-20T10:30:00",
    "duration_minutes": 120,
    "good_lm": "450",
    "bad_lm": "25",
    "efficiency": 94.7,
    "created_at": "2025-01-20T10:30:00Z"
  }
}
```

---

### 37. List DMI Data
**GET** `/api/v1/dmi-data?page=1&form_id=567&machine_id=5&date_from=2025-01-01&date_to=2025-01-31`

**Permission Required**: `dmi_view`

**Query Parameters**:
- `page`: integer
- `per_page`: integer
- `form_id`: integer
- `machine_id`: integer
- `date_from`: date
- `date_to`: date
- `active_description`: make_ready|running|pause|stop|other

**Response** (200):
```json
{
  "success": true,
  "data": {
    "dmi_records": [
      {
        "id": 890,
        "form": {
          "id": 567,
          "form_name": "Front Side Printing"
        },
        "machine": {
          "id": 5,
          "machine_name": "Press Machine 01"
        },
        "start_time": "2025-01-20T08:30:00",
        "end_time": "2025-01-20T10:30:00",
        "duration_minutes": 120,
        "speed": "5000",
        "good_lm": "450",
        "bad_lm": "25",
        "active_description": "running",
        "status": {
          "id": 1,
          "status_name": "Running"
        },
        "created_at": "2025-01-20T10:30:00Z"
      }
    ],
    "summary": {
      "total_good_lm": 4500,
      "total_bad_lm": 250,
      "average_speed": 5000,
      "total_production_time_minutes": 1200
    }
  },
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 45
  }
}
```

---

## Button Management Endpoints

### 38. Create Button Group
**POST** `/api/v1/button-groups`

**Permission Required**: `button_group_create`

**Request Body**:
```json
{
  "name": "Standard Production Buttons",
  "description": "Default button set for production operations",
  "status": true
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Button group created successfully",
  "data": {
    "id": 2,
    "name": "Standard Production Buttons",
    "status": true,
    "created_at": "2025-01-15T10:00:00Z"
  }
}
```

---

### 39. Create Button
**POST** `/api/v1/buttons`

**Permission Required**: `buttons_create`

**Request Body**:
```json
{
  "button_name": "Emergency Stop",
  "button_group_id": 2,
  "description": "Emergency stop button for immediate halt",
  "status": true
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Button created successfully",
  "data": {
    "id": 15,
    "button_name": "Emergency Stop",
    "button_group_id": 2,
    "status": true,
    "created_at": "2025-01-15T10:00:00Z"
  }
}
```

---

### 40. List Form Button Actions
**GET** `/api/v1/forms/{id}/button-actions`

**Permission Required**: `job_menu_view`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "form_id": 567,
    "button_actions": [
      {
        "id": 123,
        "action_type": "START",
        "button": {
          "id": 10,
          "button_name": "Start Production"
        },
        "performed_by": {
          "id": 10,
          "name": "John Operator"
        },
        "login_detail": {
          "id": 45,
          "machine": {
            "id": 5,
            "machine_name": "Press Machine 01"
          },
          "shift": {
            "id": 2,
            "shift_name": "Afternoon Shift"
          }
        },
        "reason": null,
        "created_at": "2025-01-20T08:30:00Z"
      },
      {
        "id": 124,
        "action_type": "PAUSE",
        "reason": "Lunch break",
        "performed_by": {
          "id": 10,
          "name": "John Operator"
        },
        "created_at": "2025-01-20T13:00:00Z"
      }
    ]
  }
}
```

---

## Additional Master Data Endpoints

### 41. Create/List Processes
**POST** `/api/v1/processes`
**GET** `/api/v1/processes?page=1`

**Permission Required**: `process_create` / `process_view`

**Request Body (POST)**:
```json
{
  "name": "Offset Printing",
  "msi_id": "PROC-002",
  "status": true
}
```

**Response List (GET)**:
```json
{
  "success": true,
  "data": {
    "processes": [
      {
        "id": 2,
        "name": "Offset Printing",
        "msi_id": "PROC-002",
        "status": true,
        "machines_count": 5,
        "created_at": "2024-01-01T00:00:00Z"
      }
    ]
  }
}
```

---

### 42. Create/List Departments
**POST** `/api/v1/departments`
**GET** `/api/v1/departments?page=1`

**Permission Required**: `department_create` / `department_view`

**Request Body (POST)**:
```json
{
  "name": "Press Department",
  "description": "Handles all printing press operations",
  "status": true
}
```

---

### 43. Create/List Machine Types
**POST** `/api/v1/machine-types`
**GET** `/api/v1/machine-types`

**Permission Required**: `machine_type_create` / `machine_type_view`

**Response (GET)**:
```json
{
  "success": true,
  "data": {
    "machine_types": [
      {
        "id": 1,
        "name": "PRE_PRESS",
        "description": "Pre-press operations",
        "machines_count": 3,
        "status": true
      },
      {
        "id": 2,
        "name": "PRESS",
        "description": "Printing press machines",
        "machines_count": 8,
        "status": true
      }
    ]
  }
}
```

---

### 44. Create/List Sub-Statuses
**POST** `/api/v1/sub-statuses`
**GET** `/api/v1/sub-statuses?status_id=1`

**Permission Required**: `sub_status_menu_create` / `sub_status_menu_view`

**Request Body (POST)**:
```json
{
  "sub_status_code": "SUB-001",
  "sub_status_name": "Normal Speed Running",
  "status_id": 1,
  "remark": "Production running at normal speed",
  "status": true
}
```

**Response (GET)**:
```json
{
  "success": true,
  "data": {
    "sub_statuses": [
      {
        "id": 3,
        "sub_status_code": "SUB-001",
        "sub_status_name": "Normal Speed Running",
        "status": {
          "id": 1,
          "status_name": "Running",
          "status_type": "productive"
        },
        "status": true,
        "created_at": "2024-01-01T00:00:00Z"
      }
    ]
  }
}
```

---

### 45. Create/List QC Masters
**POST** `/api/v1/qc-masters`
**GET** `/api/v1/qc-masters?page=1`

**Permission Required**: `qc_master_create` / `qc_master_view`

**Request Body (POST)**:
```json
{
  "qc_code": "QC-001",
  "qc_name": "Color Accuracy Check",
  "description": "Verify color matching with sample",
  "status": true
}
```

---

### 46. Create/List Sheets
**POST** `/api/v1/sheets`
**GET** `/api/v1/sheets?page=1`

**Permission Required**: `sheet_size_create` / `sheet_size_view`

**Request Body (POST)**:
```json
{
  "sheet_size": "A4",
  "width": "210",
  "height": "297",
  "description": "Standard A4 size",
  "status": true
}
```

---

### 47. Create/List Tags
**POST** `/api/v1/tags`
**GET** `/api/v1/tags?page=1`

**Permission Required**: `tag_create` / `tag_view`

**Request Body (POST)**:
```json
{
  "tag_name": "Rush Order",
  "description": "Priority rush order",
  "color": "#FF0000",
  "status": true
}
```

---

## Sticky Notes & Press Notes

### 48. Create Sticky Note
**POST** `/api/v1/sticky-notes`

**Request Body**:
```json
{
  "title": "Material Change Required",
  "content": "Switch to 200gsm coated paper for this job",
  "form_id": 567,
  "machine_id": 5,
  "priority": "high",
  "status": true
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Sticky note created successfully",
  "data": {
    "id": 45,
    "title": "Material Change Required",
    "content": "Switch to 200gsm coated paper for this job",
    "priority": "high",
    "form": {
      "id": 567,
      "form_name": "Front Side Printing"
    },
    "machine": {
      "id": 5,
      "machine_name": "Press Machine 01"
    },
    "created_by": {
      "id": 1,
      "name": "Supervisor"
    },
    "created_at": "2025-01-20T10:00:00Z"
  }
}
```

---

### 49. List Sticky Notes
**GET** `/api/v1/sticky-notes?form_id=567&machine_id=5&priority=high`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "sticky_notes": [
      {
        "id": 45,
        "title": "Material Change Required",
        "content": "Switch to 200gsm coated paper for this job",
        "priority": "high",
        "form_id": 567,
        "machine_id": 5,
        "created_by": {
          "id": 1,
          "name": "Supervisor"
        },
        "created_at": "2025-01-20T10:00:00Z"
      }
    ]
  }
}
```

---

### 50. Create Press Note
**POST** `/api/v1/press-notes`

**Request Body**:
```json
{
  "form_id": 567,
  "note_content": "Ink density adjusted to 1.2 for better color reproduction"
}
```

---

## Daily Tasks & Maintenance

### 51. Create Daily Task
**POST** `/api/v1/daily-tasks`

**Permission Required**: `daily_task_create`

**Request Body**:
```json
{
  "task_name": "Machine PM-001 Cleaning",
  "task_date": "2025-01-21",
  "assigned_to": 10,
  "status": "pending"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Daily task created successfully",
  "data": {
    "id": 78,
    "task_name": "Machine PM-001 Cleaning",
    "task_date": "2025-01-21",
    "assigned_to": {
      "id": 10,
      "name": "John Operator"
    },
    "status": "pending",
    "created_at": "2025-01-20T15:00:00Z"
  }
}
```

---

### 52. List Daily Tasks
**GET** `/api/v1/daily-tasks?task_date=2025-01-21&assigned_to=10&status=pending`

**Permission Required**: `daily_task_view`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "daily_tasks": [
      {
        "id": 78,
        "task_name": "Machine PM-001 Cleaning",
        "task_date": "2025-01-21",
        "assigned_to": {
          "id": 10,
          "name": "John Operator"
        },
        "status": "pending",
        "created_at": "2025-01-20T15:00:00Z"
      }
    ]
  }
}
```

---

### 53. Update Daily Task Status
**PATCH** `/api/v1/daily-tasks/{id}/status`

**Request Body**:
```json
{
  "status": "completed"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Task status updated successfully",
  "data": {
    "id": 78,
    "status": "completed",
    "completed_at": "2025-01-21T08:30:00Z"
  }
}
```

---

### 54. Create Daily Maintenance Data
**POST** `/api/v1/daily-maintenance`

**Permission Required**: `dm_task_create`

**Request Body**:
```json
{
  "machine_id": 5,
  "maintenance_date": "2025-01-21",
  "maintenance_notes": "Routine maintenance performed. All parts checked and lubricated.",
  "performed_by": 10
}
```

---

## Standard Production Values

### 55. Create Standard Production
**POST** `/api/v1/standard-productions`

**Permission Required**: `standerd_production_create`

**Request Body**:
```json
{
  "machine_id": 5,
  "standard_value": "5000",
  "description": "Standard hourly production rate",
  "status": true
}
```

---

### 56. List Standard Productions
**GET** `/api/v1/standard-productions?machine_id=5`

**Permission Required**: `standerd_production_view`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "standard_productions": [
      {
        "id": 12,
        "machine": {
          "id": 5,
          "machine_name": "Press Machine 01"
        },
        "standard_value": "5000",
        "description": "Standard hourly production rate",
        "status": true,
        "created_at": "2024-01-01T00:00:00Z"
      }
    ]
  }
}
```

---

## Search & Filter Endpoints

### 57. Global Search
**GET** `/api/v1/search?q=ABC&type=all`

**Query Parameters**:
- `q`: required, string, min:2
- `type`: all|orders|forms|machines|users

**Response** (200):
```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 125,
        "job_card_no": "JC-2025-001",
        "client_name": "ABC Corporation",
        "type": "order"
      }
    ],
    "forms": [
      {
        "id": 567,
        "form_name": "ABC Front Side",
        "msi_id": "MSI-12345",
        "type": "form"
      }
    ],
    "machines": [],
    "users": []
  }
}
```

---

### 58. Advanced Form Filters
**GET** `/api/v1/forms/advanced-filter`

**Query Parameters**:
- `job_card_no`: string
- `client_name`: string
- `material_ids`: array of integers
- `machine_ids`: array of integers
- `user_ids`: array of integers
- `form_statuses`: array (job_pending, job_started, job_completed)
- `schedule_date_from`: date
- `schedule_date_to`: date
- `quality_verified`: boolean
- `line_cleared`: boolean
- `has_issues`: boolean (forms with stop/pause reasons)

**Response** (200):
```json
{
  "success": true,
  "data": {
    "forms": [
      // Filtered forms array
    ],
    "filters_applied": {
      "material_ids": [3],
      "machine_ids": [5, 6],
      "schedule_date_from": "2025-01-01",
      "schedule_date_to": "2025-01-31"
    }
  },
  "meta": {
    "total": 45
  }
}
```

---

## Bulk Operations

### 59. Bulk Update Forms Status
**POST** `/api/v1/forms/bulk-update-status`

**Permission Required**: `job_menu_update`

**Request Body**:
```json
{
  "form_ids": [567, 568, 569],
  "status_id": 2,
  "sub_status_id": 5
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "3 forms updated successfully",
  "data": {
    "updated_count": 3,
    "failed_count": 0
  }
}
```

---

### 60. Bulk Assign Users to Forms
**POST** `/api/v1/forms/bulk-assign-users`

**Permission Required**: `job_menu_update`

**Request Body**:
```json
{
  "form_ids": [567, 568, 569],
  "user_ids": [10, 11, 12]
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Users assigned to 3 forms successfully",
  "data": {
    "forms_updated": 3,
    "users_assigned": 3
  }
}
```

---

## Export Endpoints

### 61. Export Forms to Excel
**GET** `/api/v1/forms/export?format=xlsx&date_from=2025-01-01&date_to=2025-01-31`

**Permission Required**: `job_menu_view`

**Query Parameters**:
- `format`: xlsx|csv
- All standard form filter parameters

**Response**: File download (Excel or CSV)

---

### 62. Export Production Report
**GET** `/api/v1/reports/production/export?format=pdf&date_from=2025-01-01&date_to=2025-01-31`

**Permission Required**: Dashboard view

**Query Parameters**:
- `format`: pdf|xlsx|csv
- `date_from`: required
- `date_to`: required
- `machine_id`: optional

**Response**: File download (PDF, Excel, or CSV)

---

## Real-time Updates (WebSocket/Pusher)

### 63. Subscribe to Form Updates
**Channel**: `form.{form_id}`

**Events**:
- `FormStatusChanged`
- `FormButtonActionPerformed`
- `FormDmiDataUpdated`
- `FormQualityVerified`

**Event Payload Example**:
```json
{
  "event": "FormStatusChanged",
  "data": {
    "form_id": 567,
    "old_status": "job_started",
    "new_status": "job_completed",
    "changed_by": {
      "id": 10,
      "name": "John Operator"
    },
    "timestamp": "2025-01-20T17:00:00Z"
  }
}
```

---

### 64. Subscribe to Machine Updates
**Channel**: `machine.{machine_id}`

**Events**:
- `MachineStatusChanged`
- `MachineFormAssigned`
- `MachineMaintenanceScheduled`

---

### 65. Subscribe to Dashboard Updates
**Channel**: `dashboard`

**Events**:
- `ProductionSummaryUpdated`
- `AlertCreated`
- `UserLoggedIn`
- `UserLoggedOut`

---

## Notification Endpoints

### 66. Get User Notifications
**GET** `/api/v1/notifications?page=1&unread=1`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": "uuid-123",
        "type": "form_completed",
        "title": "Form Completed",
        "message": "Form 'Front Side Printing' has been completed",
        "data": {
          "form_id": 567,
          "form_name": "Front Side Printing"
        },
        "read_at": null,
        "created_at": "2025-01-20T17:00:00Z"
      }
    ],
    "unread_count": 5
  }
}
```

---

### 67. Mark Notification as Read
**PATCH** `/api/v1/notifications/{id}/mark-read`

**Response** (200):
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

---

### 68. Mark All Notifications as Read
**POST** `/api/v1/notifications/mark-all-read`

**Response** (200):
```json
{
  "success": true,
  "message": "All notifications marked as read",
  "data": {
    "marked_count": 5
  }
}
```

---

## File Upload Endpoints

### 69. Upload Document
**POST** `/api/v1/documents/upload`

**Request**: Multipart form-data
- `file`: required, max:10MB, types: pdf,doc,docx,xls,xlsx,jpg,png
- `related_entity_type`: optional (order, form, machine)
- `related_entity_id`: optional

**Response** (201):
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "data": {
    "id": 89,
    "file_name": "job_specifications.pdf",
    "file_path": "/storage/documents/2025/01/job_specifications_abc123.pdf",
    "file_type": "application/pdf",
    "file_size": 245678,
    "related_entity_type": "order",
    "related_entity_id": 125,
    "url": "https://phoenix.yourdomain.com/storage/documents/2025/01/job_specifications_abc123.pdf",
    "created_at": "2025-01-20T10:00:00Z"
  }
}
```

---

### 70. List Documents
**GET** `/api/v1/documents?related_entity_type=order&related_entity_id=125`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "documents": [
      {
        "id": 89,
        "file_name": "job_specifications.pdf",
        "file_type": "application/pdf",
        "file_size": 245678,
        "url": "https://phoenix.yourdomain.com/storage/documents/2025/01/job_specifications_abc123.pdf",
        "created_by": {
          "id": 1,
          "name": "Admin"
        },
        "created_at": "2025-01-20T10:00:00Z"
      }
    ]
  }
}
```

---

### 71. Delete Document
**DELETE** `/api/v1/documents/{id}`

**Response** (200):
```json
{
  "success": true,
  "message": "Document deleted successfully"
}
```

---

## Analytics & Insights

### 72. Machine Efficiency Report
**GET** `/api/v1/analytics/machine-efficiency?date_from=2025-01-01&date_to=2025-01-31`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "period": {
      "from": "2025-01-01",
      "to": "2025-01-31"
    },
    "machines": [
      {
        "machine_id": 5,
        "machine_name": "Press Machine 01",
        "total_hours": 720,
        "productive_hours": 680,
        "downtime_hours": 40,
        "efficiency_percentage": 94.4,
        "forms_completed": 45,
        "average_speed": 5100,
        "quality_rate": 96.5,
        "rank": 1
      }
    ],
    "overall_efficiency": 92.3
  }
}
```

---

### 73. Operator Performance Report
**GET** `/api/v1/analytics/operator-performance?date_from=2025-01-01&date_to=2025-01-31`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "operators": [
      {
        "user_id": 10,
        "name": "John Operator",
        "forms_completed": 25,
        "total_production": 125000,
        "average_quality_rate": 97.2,
        "total_hours_worked": 160,
        "productivity_score": 95.5
      }
    ]
  }
}
```

---

### 74. Quality Trends Report
**GET** `/api/v1/analytics/quality-trends?date_from=2025-01-01&date_to=2025-01-31`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "overall_quality_rate": 96.5,
    "daily_trends": [
      {
        "date": "2025-01-15",
        "total_production": 50000,
        "good_quantity": 48500,
        "bad_quantity": 1500,
        "quality_rate": 97.0
      }
    ],
    "by_machine": [
      {
        "machine_id": 5,
        "machine_name": "Press Machine 01",
        "quality_rate": 97.5
      }
    ],
    "by_material": [
      {
        "material_id": 3,
        "material_name": "Coated Paper 150gsm",
        "quality_rate": 98.0
      }
    ]
  }
}
```

---

## Permission Check Endpoints

### 75. Check User Permissions
**GET** `/api/v1/auth/permissions`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Operator"
    },
    "permissions": {
      "dashboard_view": true,
      "job_menu_view": true,
      "job_menu_create": false,
      "job_menu_update": true,
      "job_menu_delete": false,
      // ... all 59 permissions
    },
    "permission_groups": {
      "dashboard": ["view"],
      "jobs": ["view", "update"],
      "quality": ["view"],
      "machines": ["view"]
    }
  }
}
```

---

## System Configuration Endpoints

### 76. Get System Configuration
**GET** `/api/v1/system/config`

**Permission Required**: Admin only

**Response** (200):
```json
{
  "success": true,
  "data": {
    "app_name": "Phoenix Manufacturing System",
    "version": "1.0.0",
    "pagination_limit": 20,
    "date_format": "Y-m-d",
    "time_format": "H:i:s",
    "timezone": "Asia/Kolkata",
    "features": {
      "dmi_integration": true,
      "third_party_integration": true,
      "real_time_updates": true,
      "mobile_app": false
    }
  }
}
```

---

## Error Response Examples

### Validation Error (422)
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "user_name": [
      "The user name field is required."
    ],
    "password": [
      "The password must be at least 8 characters."
    ],
    "machine_id": [
      "The selected machine id is invalid."
    ]
  },
  "code": 422
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "code": 401
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "You don't have permission to perform this action.",
  "required_permission": "job_menu_create",
  "code": 403
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found.",
  "code": 404
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "An error occurred while processing your request.",
  "code": 500,
  "error_id": "ERR-20250120-123456"
}
```

---

## Rate Limiting

**Limits**:
- Authenticated users: 1000 requests per minute
- Unauthenticated users: 60 requests per minute
- Third-party API: 500 requests per minute

**Response when rate limited (429)**:
```json
{
  "success": false,
  "message": "Too many requests. Please try again later.",
  "retry_after": 60,
  "code": 429
}
```

---

## API Versioning

**Current Version**: v1

**Version Header**: `Accept: application/vnd.phoenix.v1+json`

**Deprecation Notice**: Deprecated endpoints will be supported for 6 months after deprecation announcement

---

## Testing Endpoints (Development Only)

### 77. Seed Sample Data
**POST** `/api/v1/dev/seed-sample-data`

**Only available in development environment**

---

### 78. Reset Database
**POST** `/api/v1/dev/reset-database`

**Only available in development environment**

---

*Document Version: 1.0*
*Total Endpoints: 78+*
*Last Updated: October 18, 2025*
