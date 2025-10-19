#!/bin/bash

echo "🔍 Searching for old permission format in Vue files..."
echo ""

# Search for old permission patterns
echo "Files containing old permission format:"
echo "=========================================="
grep -r -l "hasPermission.*_" resources/js/ 2>/dev/null | grep "\.vue$"
echo ""

echo "Permission checks found:"
echo "=========================================="
grep -r -n "hasPermission" resources/js/ 2>/dev/null | grep "\.vue:" | head -20
echo ""

echo "User.permission references:"
echo "=========================================="
grep -r -n "user\.permission\." resources/js/ 2>/dev/null | grep "\.vue:"
echo ""

echo "✅ Search complete!"
echo ""
echo "Files to update manually:"
echo "- Any files listed above"
echo "- Change permission_name to permission-name.action"
echo "- Change user.permission.permissions to user.permissions"
