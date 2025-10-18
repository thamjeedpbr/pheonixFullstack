# 🔧 Fix Sanctum Migration Issue

## Issue: personal_access_tokens table missing

Run these commands:

```bash
# Publish Sanctum migrations
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run migrations
php artisan migrate

# Verify table exists
php artisan tinker
>>> DB::select("SHOW TABLES LIKE 'personal_access_tokens'");
```

This will create the `personal_access_tokens` table that Sanctum needs to store API tokens.
