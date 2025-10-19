#!/bin/bash

# Spatie Permission Setup Script
# This script automates the installation and setup of Spatie Permission

echo "================================================"
echo "Spatie Permission Installation & Setup"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Install Spatie Permission Package
echo -e "${YELLOW}Step 1: Installing Spatie Permission package...${NC}"
composer require spatie/laravel-permission
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Package installed successfully${NC}"
else
    echo -e "${RED}✗ Package installation failed${NC}"
    exit 1
fi
echo ""

# Step 2: Publish Configuration
echo -e "${YELLOW}Step 2: Publishing configuration files...${NC}"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Configuration published${NC}"
else
    echo -e "${RED}✗ Configuration publishing failed${NC}"
    exit 1
fi
echo ""

# Step 3: Run Migrations
echo -e "${YELLOW}Step 3: Running migrations...${NC}"
echo "Do you want to run fresh migrations (WARNING: This will delete all data)? (y/N)"
read -r response
if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
    php artisan migrate:fresh
    FRESH_MIGRATION=true
else
    php artisan migrate
    FRESH_MIGRATION=false
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed${NC}"
else
    echo -e "${RED}✗ Migrations failed${NC}"
    exit 1
fi
echo ""

# Step 4: Seed Roles and Permissions
echo -e "${YELLOW}Step 4: Seeding roles and permissions...${NC}"
php artisan db:seed --class=RolesAndPermissionsSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Roles and permissions seeded${NC}"
else
    echo -e "${RED}✗ Seeding failed${NC}"
    exit 1
fi
echo ""

# Step 5: Seed Users
if [ "$FRESH_MIGRATION" = true ]; then
    echo -e "${YELLOW}Step 5: Seeding users...${NC}"
    php artisan db:seed --class=UserSeeder
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Users seeded${NC}"
    else
        echo -e "${RED}✗ User seeding failed${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}Step 5: Migrating existing users...${NC}"
    php artisan permission:migrate-to-spatie
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Users migrated${NC}"
    else
        echo -e "${RED}✗ User migration failed${NC}"
        exit 1
    fi
fi
echo ""

# Step 6: Clear Cache
echo -e "${YELLOW}Step 6: Clearing caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan permission:cache-reset
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Caches cleared${NC}"
else
    echo -e "${RED}✗ Cache clearing failed${NC}"
fi
echo ""

# Summary
echo "================================================"
echo -e "${GREEN}✅ Setup completed successfully!${NC}"
echo "================================================"
echo ""
echo "Default User Credentials:"
echo "------------------------"
echo "Username: admin       | Password: password | Role: Super Admin"
echo "Username: supervisor1 | Password: password | Role: Supervisor"
echo "Username: operator1   | Password: password | Role: Operator"
echo "Username: operator2   | Password: password | Role: Operator"
echo "Username: qc1         | Password: password | Role: QC Inspector"
echo ""
echo "Documentation:"
echo "------------------------"
echo "- Full Guide: SPATIE_PERMISSION_GUIDE.md"
echo "- Quick Reference: SPATIE_QUICK_REFERENCE.md"
echo "- Official Docs: https://spatie.be/docs/laravel-permission"
echo ""
echo "Next Steps:"
echo "------------------------"
echo "1. Test login with the credentials above"
echo "2. Update your routes to use new permission format"
echo "3. Update frontend to use new permission names"
echo "4. Review and test all permission checks"
echo ""
