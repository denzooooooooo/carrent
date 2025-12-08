#!/bin/bash

# Migration script for production deployment
# Run this script on your production server to fix the location_bookings table error

echo "=== Laravel Migration Script for Production ==="
echo "This script will run the pending migrations to fix the booking errors"
echo ""

# Change to the Laravel project directory (adjust path as needed)
cd /path/to/your/laravel/project

echo "Current directory: $(pwd)"
echo ""

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please make sure you're in the Laravel project root directory."
    exit 1
fi

echo "✅ Laravel project detected"
echo ""

# Check migration status
echo "📋 Checking current migration status..."
php artisan migrate:status

echo ""
echo "🔄 Running migrations with --force flag..."
php artisan migrate --force

echo ""
echo "📋 Migration status after running migrations:"
php artisan migrate:status

echo ""
echo "🧹 Clearing Laravel caches..."
php artisan optimize:clear

echo ""
echo "✅ Migration completed successfully!"
echo ""
echo "The location_bookings table should now be available."
echo "Test your booking forms to verify they work correctly."
