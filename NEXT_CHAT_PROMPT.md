# 🚀 Quick Start Guide - Next Chat Session

## Copy & Paste This Prompt to Start Your Next Session

---

### **PROMPT FOR NEXT CHAT:**

```
I'm continuing development of the Phoenix Manufacturing System (Laravel 11 + Vue.js 3 + Inertia.js).

Project Location: /Users/thamjeedlal/Herd/pheonixFullstack/

Current Status:
✅ Database: 36 tables migrated (38 migrations complete)
✅ Models: 30 models with 162+ relationships
✅ Seeders: 55 records seeded (4 users, 4 machines, 7 materials, etc.)
✅ Documentation: Complete

Current Phase: Module-by-Module Development (Backend API + Frontend UI)

Starting Module: AUTHENTICATION & LOGIN

Please read the system prompt first:
FILE: /Users/thamjeedlal/Herd/pheonixFullstack/SYSTEM_PROMPT.md

Then create the Authentication & Login module:

BACKEND (Create First):
1. app/Http/Requests/LoginRequest.php - Login validation
2. app/Http/Controllers/AuthController.php - Login, logout, me, refresh methods
3. app/Http/Resources/UserResource.php - User API transformer
4. app/Http/Middleware/CheckPermission.php - Permission middleware
5. Update routes/api.php - Add auth routes with Sanctum

Requirements:
- Use Laravel Sanctum for token authentication
- Use DB transactions where needed
- Proper error handling with try-catch
- Return JSON responses
- HTTP status codes (200, 201, 401, 500)
- Follow PSR-12 standards

FRONTEND (Create After Backend):
1. resources/js/Layouts/GuestLayout.vue - Login page layout
2. resources/js/Pages/Auth/Login.vue - Login form page
3. resources/js/Layouts/AuthenticatedLayout.vue - Admin panel layout
4. resources/js/Components/Navbar.vue - Top navigation bar
5. resources/js/Components/Sidebar.vue - Left sidebar menu
6. resources/js/Pages/Dashboard.vue - Main dashboard

Requirements:
- Modern, responsive design (mobile-first)
- Use Tailwind CSS
- Dark/light mode support
- Smooth transitions
- Form validation
- Loading states
- Error handling
- Professional UI (similar to Tailwind UI / Shadcn)

Design Standards:
- Navbar: h-16 (64px)
- Sidebar: w-64 (256px) expanded, w-16 collapsed on mobile
- Primary Color: Blue (#3B82F6)
- Cards: rounded-lg shadow-md
- Buttons: rounded-lg with hover effects
- Clean, spacious layouts

Test Credentials:
- admin / password (Super Admin)
- supervisor1 / password (Supervisor)
- operator1 / password (Operator)

Existing Models (Already Created):
- User (app/Models/User.php)
- UserPermission (app/Models/UserPermission.php)

Follow all standards in SYSTEM_PROMPT.md

Start with Backend files first. Create complete, production-ready code.
```

---

## 📁 **Key Files to Reference:**

1. **SYSTEM_PROMPT.md** - Complete development guide
2. **PROGRESS_TRACKER.md** - Current project status
3. **DATABASE_SCHEMA.md** - All table structures
4. **API_DOCUMENTATION.md** - API endpoints reference
5. **SEEDER_DOCUMENTATION.md** - Seeded data reference

---

## 🔑 **Important Information:**

### Test Users:
| Username | Password | Role | Type |
|----------|----------|------|------|
| admin | password | Super Admin | admin |
| supervisor1 | password | Supervisor | super_wiser |
| operator1 | password | Operator | operator |
| operator2 | password | Operator | operator |

### Database:
- **36 tables** created and seeded
- **55 records** available for testing
- All foreign keys working
- All relationships defined

### Models Location:
```
/Users/thamjeedlal/Herd/pheonixFullstack/app/Models/
```

### Frontend Location:
```
/Users/thamjeedlal/Herd/pheonixFullstack/resources/js/
```

---

## 📋 **Module Development Order:**

After Login module is complete, continue with:

1. ✅ **Authentication & Login** - (CURRENT)
2. ⏳ **Master Data Management** - Machines, Materials, Processes
3. ⏳ **Production Management** - Orders, Forms, Jobs
4. ⏳ **Production Tracking** - Real-time monitoring
5. ⏳ **Quality Control** - QC verification
6. ⏳ **Supporting Features** - Notes, Documents, Reports

---

## ✅ **What You'll Get:**

After Login module completion:
- ✅ Working login system with Sanctum authentication
- ✅ Beautiful responsive login page
- ✅ Admin panel with navbar and sidebar
- ✅ Dashboard page
- ✅ Permission-based access control
- ✅ Mobile-responsive design
- ✅ Professional UI

---

## 🎯 **Success Criteria:**

**Backend:**
- [ ] Can login via API and get token
- [ ] Can get current user info
- [ ] Can logout and invalidate token
- [ ] Permission middleware working

**Frontend:**
- [ ] Login page looks professional
- [ ] Can login and see dashboard
- [ ] Sidebar and navbar working
- [ ] Responsive on mobile
- [ ] Smooth transitions

---

## 📞 **Commands After Module Creation:**

```bash
# Test backend
php artisan serve
# Access: http://localhost:8000

# Test API with curl
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"user_name":"admin","password":"password"}'

# Frontend (if using Vite)
npm run dev
```

---

## 💡 **Tips:**

1. **Always start with Backend first** - Test APIs before building UI
2. **Use Postman/Insomnia** - Test all API endpoints
3. **Check console** - Look for errors during development
4. **Test on mobile** - Use browser dev tools responsive mode
5. **Follow standards** - Reference SYSTEM_PROMPT.md constantly

---

## 🚨 **Common Issues:**

**Issue:** CORS errors
**Fix:** Check `config/cors.php` settings

**Issue:** 401 Unauthorized
**Fix:** Check Sanctum configuration in `config/sanctum.php`

**Issue:** Token not working
**Fix:** Ensure `Accept: application/json` header is sent

**Issue:** Styles not loading
**Fix:** Run `npm run dev` or `npm run build`

---

## 📊 **Current Project Stats:**

- **Overall Progress:** 70%
- **Database:** 100% ✅
- **Models:** 100% ✅
- **Seeders:** 100% ✅
- **API Controllers:** 0%
- **Frontend Pages:** 0%

**Estimated Time for Login Module:** 1-2 days

---

**Ready to start! Copy the prompt above and paste in your next chat session.** 🚀

---

*Quick Start Guide v1.0*  
*Created: October 18, 2025*
