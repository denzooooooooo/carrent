# Deployment Instructions for Booking Fixes

## Overview
This deployment fixes two critical booking errors:
1. Location booking - Missing `location_bookings` table
2. Package booking - 404 error due to incorrect route binding

## Files Changed
1. `database/migrations/2025_12_08_193000_create_location_bookings_table.php` (NEW)
2. `app/Models/TourPackage.php` (MODIFIED)
3. `app/Http/Controllers/PackageController.php` (MODIFIED)
4. `TODO.md` (UPDATED)

## 🚨 CRITICAL: Production Database Migration Required

**The location_bookings table error indicates that the migration has NOT been run on production yet.**

**ERROR:** `SQLSTATE[42S02]: Base table or view not found: 1146 Table 'u608034730_carrepremim.location_bookings' doesn't exist`

**LOCATION:** `PaymentController.php:44` - `$booking->load(['event', 'eventBooking', 'flightBooking', 'package', 'location', 'locationBooking']);`

**SOLUTION:** Run the migration on production server immediately.

## Deployment Steps

### Step 1: Deploy Code to Production Server
Upload or pull the latest code to your production server at `public.monnkama.shop`

### Step 2: Run Migrations on Production (REQUIRED - Fixes the table error)

**Option 1: Manual Commands**
SSH into your production server and run:

```bash
cd /path/to/your/laravel/project
php artisan migrate --force
```

**Option 2: Automated Script (Recommended)**
Upload the `migration_script.sh` file to your production server and run:

```bash
chmod +x migration_script.sh
./migration_script.sh
```

**CRITICAL:** The `--force` flag is required for production environments. This command will create the missing `location_bookings` table.

### Step 3: Clear Caches
After migration, clear all caches:

```bash
php artisan optimize:clear
```

Or individually:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 4: Verify Migration
Check that the migration ran successfully:

```bash
php artisan migrate:status
```

Look for:
```
2025_12_08_193000_create_location_bookings_table ............................................ [XX] Ran
```

### Step 5: Verify Table Exists
```bash
php artisan db:table location_bookings
```

This should show the table structure with 15 columns.

## Testing After Deployment

### Test Location Booking:
1. Navigate to: `https://public.monnkama.shop/location/{id}`
2. Fill out the booking form
3. Submit and verify:
   - No SQL errors
   - Redirects to payment instructions
   - Data saved in both `bookings` and `location_bookings` tables

### Test Package Booking:
1. Navigate to: `https://public.monnkama.shop/packages/{slug}`
2. Fill out the booking form
3. Submit and verify:
   - No 404 errors
   - Redirects to payment instructions
   - Booking created successfully
   - Confirmation email sent

## Rollback Plan (If Needed)

If issues occur, you can rollback the migration:

```bash
php artisan migrate:rollback --step=1
```

This will drop the `location_bookings` table.

## Database Differences

**Local Database:** `carrepremium`
**Production Database:** `u608034730_carrepremim`

Make sure your production `.env` file has the correct database credentials.

## Support

If you encounter any issues during deployment:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify database connection in `.env`
4. Ensure proper file permissions

## Summary of Changes

### Migration Created:
- Table: `location_bookings`
- Columns: id, location_id, booking_id, user details, dates, pricing, status
- Foreign Keys: location_id → locations.id, booking_id → bookings.id
- Indexes: Added for performance on frequently queried columns

### Model Updated:
- `TourPackage::getRouteKeyName()` now returns 'slug' for proper route binding

### Controller Fixed:
- `PackageController::book()` now passes both required parameters to email class

## Post-Deployment Verification

Run these checks after deployment:

```bash
# 1. Check migration status
php artisan migrate:status | grep location_bookings

# 2. Verify table structure
php artisan db:table location_bookings

# 3. Test database connection
php artisan tinker
>>> \DB::connection()->getPdo();
>>> exit

# 4. Clear all caches
php artisan optimize:clear
```

All checks should pass without errors.
