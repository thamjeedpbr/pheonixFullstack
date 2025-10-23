# 🎯 QUICK REFERENCE: Order → Section → Form Flow

## 📊 Visual Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│                        ORDER                                 │
│  Job Card: JC-2025-001                                      │
│  Client: ABC Manufacturing                                   │
│  Delivery: Oct 30, 2025                                     │
│  [Edit Order] [Delete] [+ Add Section]                      │
└─────────────────────────────────────────────────────────────┘
                         │
        ┌────────────────┴────────────────┐
        │                                  │
        ▼                                  ▼
┌──────────────────┐              ┌──────────────────┐
│   SECTION 1      │              │   SECTION 2      │
│   SEC-001        │              │   SEC-002        │
│   📄 3 Forms     │              │   📄 2 Forms     │
│   [View Forms] 🗑 │              │   [View Forms] 🗑 │
└──────────────────┘              └──────────────────┘
        │                                  │
        │                                  │
        ▼                                  ▼
┌───────────────┐                 ┌───────────────┐
│   FORM 1      │                 │   FORM 4      │
│   Printing    │                 │   Cutting     │
│   ⚙️ Machine 1 │                 │   ⚙️ Machine 3 │
│   👤 2 Ops    │                 │   👤 1 Op     │
│   Status: ⚫   │                 │   Status: 🟢  │
│   [View] →    │                 │   [View] →    │
└───────────────┘                 └───────────────┘
```

## 🔄 Complete User Flow

### Step 1: Create Order
```
URL: /orders/create
┌─────────────────────────────────┐
│  CREATE ORDER                    │
│  ─────────────────────────────  │
│  Job Card #: [JC-2025-001]      │
│  Client:     [ABC Mfg     ]     │
│  Title:      [Print Job  ]      │
│  Delivery:   [Oct 30     ]      │
│  Priority:   [High ▼    ]       │
│                                  │
│  [Cancel]  [Create Order]       │
└─────────────────────────────────┘
         ↓
    Order Created
         ↓
    Redirect to /orders/{id}
```

### Step 2: View Order & Add Section
```
URL: /orders/{id}
┌─────────────────────────────────────────┐
│  ORDER DETAILS            [+ Add Section]│
│  ─────────────────────────────────────  │
│  ╔════════════════════════════════════╗ │
│  ║ Job Card: JC-2025-001              ║ │
│  ║ Client: ABC Manufacturing          ║ │
│  ║ Delivery: Oct 30, 2025 (7 days)   ║ │
│  ╚════════════════════════════════════╝ │
│                                          │
│  SECTIONS (0)                            │
│  ┌────────────────────────────────────┐ │
│  │  📭 No sections yet                 │ │
│  │  Create your first section          │ │
│  │  [+ Create First Section]           │ │
│  └────────────────────────────────────┘ │
└─────────────────────────────────────────┘

Click [+ Add Section] →

┌──────────────────────────┐
│  ADD NEW SECTION         │
│  ──────────────────────  │
│  Section ID *            │
│  [SEC-001          ]     │
│                          │
│  [Cancel] [Create]      │
└──────────────────────────┘
         ↓
    Section Created
         ↓
    Modal Closes & Page Refreshes
```

### Step 3: View Sections (After Creation)
```
URL: /orders/{id}
┌─────────────────────────────────────────┐
│  SECTIONS (2)              [+ Add Section]│
│  ─────────────────────────────────────  │
│  ┌─────────────┐  ┌─────────────┐     │
│  │ SECTION 1   │  │ SECTION 2   │     │
│  │ SEC-001     │  │ SEC-002     │     │
│  │ ✅ Active   │  │ ✅ Active   │     │
│  │             │  │             │     │
│  │     📄      │  │     📄      │     │
│  │      3      │  │      2      │     │
│  │    Forms    │  │    Forms    │     │
│  │             │  │             │     │
│  │ [View Forms]│  │ [View Forms]│     │
│  │          🗑  │  │          🗑  │     │
│  └─────────────┘  └─────────────┘     │
└─────────────────────────────────────────┘

Click [View Forms] →
         ↓
    Go to /sections/{id}
```

### Step 4: View Section & Add Form
```
URL: /sections/{id}
┌─────────────────────────────────────────┐
│  Orders > JC-2025-001 > Section SEC-001 │
│                                          │
│  SECTION SEC-001             [+ Add Form]│
│  ─────────────────────────────────────  │
│  ╔════════════════════════════════════╗ │
│  ║ Section ID: SEC-001                ║ │
│  ║ Status: ✅ Active                  ║ │
│  ║ Total Forms: 3                     ║ │
│  ╚════════════════════════════════════╝ │
│                                          │
│  FORMS                                   │
│  ┌─────────────┐  ┌─────────────┐      │
│  │ Printing    │  │ Lamination  │      │
│  │ ⚫ Pending   │  │ 🟢 Started  │      │
│  │ ⚙️ Machine 1 │  │ ⚙️ Machine 2 │      │
│  │ 👤 2 Ops    │  │ 👤 1 Op     │      │
│  │ 📅 Oct 25   │  │ 📅 Oct 26   │      │
│  │ Good: 1000  │  │ Good: 500   │      │
│  │ Total: 1200 │  │ Total: 550  │      │
│  │ Created: 2d │  │ Created: 1d │      │
│  │          → │  │          → │      │
│  └─────────────┘  └─────────────┘      │
└─────────────────────────────────────────┘

Click [+ Add Form] →

┌──────────────────────────┐
│  CREATE NEW FORM         │
│  ──────────────────────  │
│  You'll be redirected to │
│  the form creation page  │
│  for this section.       │
│                          │
│  [Cancel] [Continue]    │
└──────────────────────────┘
         ↓
    Redirect to /forms/create?section_id={id}
```

### Step 5: Create Form (Pre-filled Section)
```
URL: /forms/create?section_id={id}
┌─────────────────────────────────────────┐
│  CREATE PRODUCTION FORM                  │
│  ─────────────────────────────────────  │
│  Section: [SEC-001 ▼]  (Pre-filled!)    │
│  Form Name: [Printing Job        ]      │
│  Schedule: [Oct 25, 2025        ]       │
│  Machine: [Machine 1 ▼          ]       │
│  Operators: ☑ John Doe                  │
│             ☑ Jane Smith                │
│  Materials: Paper - 1000 sheets         │
│             Ink - 5 liters              │
│                                          │
│  [Cancel]  [Create Form]                │
└─────────────────────────────────────────┘
         ↓
    Form Created
         ↓
    Redirect to /forms (or /sections/{id})
```

## 🎨 Card Design Reference

### Order Page - Section Card
```
┌─────────────────────────────┐
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ │ ← Gradient Header
│ Section SEC-001  [✅ Active] │
├─────────────────────────────┤
│                             │
│          ┌─────┐           │
│          │ 📄  │           │
│          └─────┘           │
│             3               │ ← Large Number
│           Forms             │
│                             │
├─────────────────────────────┤
│ [      View Forms      ] 🗑  │ ← Action Buttons
└─────────────────────────────┘
   ↑ Hover: Shadow increases
   ↑ Border turns blue
```

### Section Page - Form Card
```
┌─────────────────────────────┐
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░ │ ← Subtle Header
│ Printing Job    [⚫ Pending] │
├─────────────────────────────┤
│ ⚙️  Machine 1               │
│ 👤  2 Operators             │
│ 📅  Oct 25, 2025            │
│ ─────────────────────────   │
│ Print 1000 sheets on...     │ ← Description
├─────────────────────────────┤
│ Good: 1000    Total: 1200   │ ← Quantities
├─────────────────────────────┤
│ Created: Oct 23        →   │ ← Footer
└─────────────────────────────┘
   ↑ Hover: Arrow slides right
   ↑ Shadow + Border animate
```

## 🎨 Color Palette

### Status Colors
```
⚫ Pending       → #6B7280 (Gray)
🟡 Make Ready   → #F59E0B (Yellow)
🟢 Started      → #10B981 (Green)
🟠 Paused       → #F97316 (Orange)
🔴 Stopped      → #EF4444 (Red)
🔵 Completed    → #3B82F6 (Blue)
🟣 QC Verified  → #8B5CF6 (Purple)
🟦 Line Cleared → #14B8A6 (Teal)
```

### UI Elements
```
Primary Button:  #2563EB (Blue)
Success:         #10B981 (Green)
Danger:          #EF4444 (Red)
Warning:         #F59E0B (Yellow)
Card Background: #FFFFFF (White)
Card Border:     #E5E7EB (Gray)
Gradient Start:  #EFF6FF (Blue-50)
Gradient End:    #E0E7FF (Indigo-50)
```

## 📱 Responsive Breakpoints

```
Mobile:  < 768px   → 1 column grid
Tablet:  768-1024  → 2 column grid
Desktop: > 1024px  → 3 column grid
```

## 🔑 Key Features Summary

### Order Page:
✅ Add sections inline (modal)
✅ View section cards
✅ Delete sections (protected)
✅ Beautiful gradient info card
✅ Responsive grid layout

### Section Page:
✅ Breadcrumb navigation
✅ Add forms (redirects to create)
✅ View form cards with details
✅ Status badges
✅ Icon representations
✅ Hover animations
✅ Click to view details

### Data Protection:
✅ Sections must have order
✅ Forms must have section
✅ Cannot delete with children
✅ Unique IDs enforced
✅ Audit trail maintained

## 🚀 URLs Reference

```
Order Management:
/orders                  → List all orders
/orders/create           → Create new order
/orders/{id}             → View order with sections
/orders/{id}/edit        → Edit order

Section Management:
/sections/{id}           → View section with forms

Form Management:
/forms/create            → Create form (section_id in query)
/forms/{id}              → View form details
/forms/{id}/edit         → Edit form
```

## 🎯 Testing Shortcuts

```bash
# Quick test flow
1. Login → /orders
2. Click "Create Order"
3. Fill and submit
4. Click "View" on created order
5. Click "Add Section"
6. Enter "SEC-001" and submit
7. Click "View Forms" on section card
8. Click "Add Form"
9. Click "Continue"
10. Fill form and submit
11. See form card in section
12. Click form card to view details
```

---

*Quick Reference Guide*  
*Phoenix Manufacturing System*  
*Order → Section → Form Complete!* ✅
