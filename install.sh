#!/bin/bash

echo "Installing Spatie Permission..."
echo ""

# Install package
echo "1. Installing package..."
composer require spatie/laravel-permission

# Publish config
echo "2. Publishing config..."
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Fresh migration
echo "3. Running fresh migrations (WARNING: Deletes all data)..."
read -p "Continue? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan migrate:fresh --seed
    php artisan permission:cache-reset
    echo ""
    echo "✅ Installation complete!"
    echo ""
    echo "Test accounts:"
    echo "admin / password - Super Admin"
    echo "supervisor1 / password - Supervisor"
    echo "operator1 / password - Operator"
fi
