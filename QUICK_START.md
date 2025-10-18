# 🚀 QUICK START GUIDE - Phoenix Manufacturing System

## ⚡ TL;DR - Start Using Now!

### 1. Start Servers (2 commands)
```bash
# Terminal 1
php artisan serve

# Terminal 2  
npm run dev
```

### 2. Login
```
URL: http://localhost:8000
Username: admin
Password: password
```

### 3. Done! ✅
You're now logged into the dashboard!

---

## 📋 Test Accounts

```
🔴 Super Admin (Full Access)
Username: admin
Password: password

🟡 Supervisor
Username: supervisor1
Password: password

🟢 Operator
Username: operator1
Password: password
```

---

## 🔥 What's Working Right Now

### ✅ Completed Features
- Login/Logout
- Dashboard
- User menu
- Sidebar navigation
- Permission checking
- Session tracking
- API responses

### ⏳ Coming Next
- User management (CRUD)
- Machine management
- Material management
- Order management
- Production tracking

---

## 🐛 Had Issues? Fixed!

**Problem:** Login error about `is_active` column  
**Solution:** ✅ Fixed - now uses correct `status` column  
**Status:** Working perfectly now!

---

## 📁 Key Files Reference

### Backend
```
app/Traits/ApiResponseTrait.php       → API responses
app/Http/Controllers/AuthController.php → Login logic
app/Http/Middleware/CheckPermission.php → Permissions
routes/api.php                         → API routes
routes/web.php                         → Web routes
```

### Frontend
```
resources/js/Pages/Auth/Login.vue     → Login page
resources/js/Pages/Dashboard.vue      → Dashboard
resources/js/Components/Navbar.vue    → Top bar
resources/js/Components/Sidebar.vue   → Side menu
```

---

## 🔌 API Quick Test

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"user_name":"admin","password":"password"}'
```

### Get User (with token)
```bash
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🎨 UI Components

### Colors
```
Blue:   #3B82F6  (Primary)
Green:  #10B981  (Success)
Yellow: #F59E0B  (Warning)
Red:    #EF4444  (Danger)
```

### Sizes
```
Navbar:  64px height
Sidebar: 256px / 64px
Cards:   rounded-lg shadow-md
```

---

## 📱 Responsive?

✅ Desktop - Full layout  
✅ Tablet - Collapsible sidebar  
✅ Mobile - Drawer menu  

Test with Chrome DevTools → Toggle device toolbar

---

## 🆘 Common Issues

### Issue: Page not loading
**Fix:** Check both servers are running
```bash
php artisan serve
npm run dev
```

### Issue: Login not working
**Fix:** Check database is running
```bash
php artisan tinker
>>> User::count()  # Should return 4
```

### Issue: Styles not showing
**Fix:** Clear cache and restart Vite
```bash
npm run dev
```

---

## 📚 Documentation

1. **MODULE_COMPLETION_REPORT.md** - Full summary
2. **AUTH_MODULE_COMPLETE.md** - Technical docs
3. **AUTH_BUG_FIXES.md** - Bug fixes
4. **SYSTEM_PROMPT.md** - Dev standards

---

## 🎯 Next Module Preview

**Module 2: User Management**
- List users
- Create user
- Edit user
- Delete user
- Assign permissions
- Assign machines

**ETA:** 4-6 hours

---

## 💡 Pro Tips

1. Use `admin` account for full access
2. Check Navbar dropdown for user menu
3. Sidebar items are permission-based
4. All API responses are standardized
5. Mobile responsive works great!

---

## 🎉 You're Ready!

Everything is set up and working.  
Login and start exploring! 🚀

**Happy coding!**

---

**Project:** Phoenix Manufacturing System  
**Module:** Authentication & Dashboard  
**Status:** ✅ Production Ready  
**Last Updated:** October 18, 2025
