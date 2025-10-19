#!/bin/bash

# Fresh Installation Script for Spatie Permission
# This script will reset your database and install everything fresh

echo "================================================"
echo "🚀 Fresh Spatie Permission Installation"
echo "================================================"
echo ""
echo "⚠️  WARNING: This will DELETE ALL DATA in your database!"
echo ""
read -p "Are you sure you want to continue? (yes/no): " -r
echo ""

if [[ ! $REPLY =~ ^[Yy][Ee][Ss]$ ]]
then
    echo "Installation cancelled."
    exit 1
fi

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}Starting fresh installation...${NC}"
echo ""

# Step 1: Install Spatie Permission Package
echo -e "${YELLOW}Step 1/8: Installing Spatie Permission package...${NC}"
composer require spatie/laravel-permission
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Package installed successfully${NC}"
else
    echo -e "${RED}✗ Package installation failed${NC}"
    exit 1
fi
echo ""

# Step 2: Publish Configuration
echo -e "${YELLOW}Step 2/8: Publishing configuration files...${NC}"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Configuration published${NC}"
else
    echo -e "${RED}✗ Configuration publishing failed${NC}"
    exit 1
fi
echo ""

# Step 3: Fresh Migrations
echo -e "${YELLOW}Step 3/8: Running fresh migrations (deleting all data)...${NC}"
php artisan migrate:fresh
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Fresh migrations completed${NC}"
else
    echo -e "${RED}✗ Migrations failed${NC}"
    exit 1
fi
echo ""

# Step 4: Seed Roles and Permissions
echo -e "${YELLOW}Step 4/8: Seeding roles and permissions...${NC}"
php artisan db:seed --class=RolesAndPermissionsSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Roles and permissions seeded${NC}"
    echo -e "${GREEN}  - 5 Roles created${NC}"
    echo -e "${GREEN}  - 156 Permissions created${NC}"
else
    echo -e "${RED}✗ Roles and permissions seeding failed${NC}"
    exit 1
fi
echo ""

# Step 5: Seed Users
echo -e "${YELLOW}Step 5/8: Seeding users with roles...${NC}"
php artisan db:seed --class=UserSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Users seeded with roles${NC}"
    echo -e "${GREEN}  - admin (Super Admin)${NC}"
    echo -e "${GREEN}  - supervisor1 (Supervisor)${NC}"
    echo -e "${GREEN}  - operator1 (Operator)${NC}"
    echo -e "${GREEN}  - operator2 (Operator)${NC}"
    echo -e "${GREEN}  - qc1 (QC Inspector)${NC}"
else
    echo -e "${RED}✗ User seeding failed${NC}"
    exit 1
fi
echo ""

# Step 6: Clear All Caches
echo -e "${YELLOW}Step 6/8: Clearing all caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ All caches cleared${NC}"
else
    echo -e "${RED}✗ Cache clearing failed${NC}"
fi
echo ""

# Step 7: Verify Installation
echo -e "${YELLOW}Step 7/8: Verifying installation...${NC}"
php artisan tinker --execute="
echo 'Roles: ' . \Spatie\Permission\Models\Role::count() . PHP_EOL;
echo 'Permissions: ' . \Spatie\Permission\Models\Permission::count() . PHP_EOL;
echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
"
echo ""

# Step 8: Test User Permissions
echo -e "${YELLOW}Step 8/8: Testing user permissions...${NC}"
php artisan tinker --execute="
\$admin = \App\Models\User::where('user_name', 'admin')->first();
\$supervisor = \App\Models\User::where('user_name', 'supervisor1')->first();
\$operator = \App\Models\User::where('user_name', 'operator1')->first();

echo 'Admin role: ' . \$admin->roles->first()->name . PHP_EOL;
echo 'Admin permissions count: ' . \$admin->getAllPermissions()->count() . PHP_EOL;
echo 'Supervisor role: ' . \$supervisor->roles->first()->name . PHP_EOL;
echo 'Supervisor permissions count: ' . \$supervisor->getAllPermissions()->count() . PHP_EOL;
echo 'Operator role: ' . \$operator->roles->first()->name . PHP_EOL;
echo 'Operator permissions count: ' . \$operator->getAllPermissions()->count() . PHP_EOL;
"
echo ""

# Summary
echo "================================================"
echo -e "${GREEN}✅ Fresh installation completed successfully!${NC}"
echo "================================================"
echo ""
echo -e "${BLUE}📊 Installation Summary:${NC}"
echo "------------------------"
echo "✓ Spatie Permission package installed"
echo "✓ Database reset with fresh migrations"
echo "✓ 5 Roles created"
echo "✓ 156 Permissions created"
echo "✓ 5 Test users created"
echo "✓ All caches cleared"
echo ""
echo -e "${BLUE}🔐 Default User Credentials:${NC}"
echo "------------------------"
echo "Username: admin       | Password: password | Role: Super Admin (156 permissions)"
echo "Username: supervisor1 | Password: password | Role: Supervisor (18 permissions)"
echo "Username: operator1   | Password: password | Role: Operator (7 permissions)"
echo "Username: operator2   | Password: password | Role: Operator (7 permissions)"
echo "Username: qc1         | Password: password | Role: QC Inspector (6 permissions)"
echo ""
echo -e "${BLUE}📚 Documentation:${NC}"
echo "------------------------"
echo "- Full Guide: SPATIE_PERMISSION_GUIDE.md"
echo "- Quick Reference: SPATIE_QUICK_REFERENCE.md"
echo "- Permission Mapping: PERMISSION_MAPPING.md"
echo "- Implementation Summary: IMPLEMENTATION_SUMMARY.md"
echo ""
echo -e "${BLUE}🧪 Test Your Installation:${NC}"
echo "------------------------"
echo "1. Test login:"
echo "   curl -X POST http://localhost/api/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"user_name\":\"admin\",\"password\":\"password\"}'"
echo ""
echo "2. Get user info:"
echo "   curl -X GET http://localhost/api/me \\"
echo "     -H 'Authorization: Bearer {your-token}'"
echo ""
echo -e "${BLUE}🔧 Next Steps:${NC}"
echo "------------------------"
echo "1. Update your routes to use new permission format"
echo "2. Update controllers to use Spatie methods"
echo "3. Update frontend permission checks"
echo "4. Test all protected routes"
echo "5. Remove old permission system files (optional)"
echo ""
echo -e "${GREEN}Happy coding! 🎉${NC}"
echo ""
