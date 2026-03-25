#!/bin/bash
# Sync LOCAL packages/events → PROD server 72.60.90.8

echo "=== SYNC LOCAL → PROD Event Packages ==="

# 1. Dump local (carrepremium)
echo "1. Dump local DB..."
mysqldump -u root -p carrepremium \
  --no-create-info --skip-triggers \
  event_series event_packages events event_seat_zones event_categories event_types > local_packages.sql

echo "2. Copy vers PROD..."
scp local_packages.sql root@72.60.90.8:/tmp/

echo "3. Import PROD (exécutez sur PROD):"
echo "ssh root@72.60.90.8"
echo "mysql -u \$USER -p carr_carrepremiun < /tmp/local_packages.sql"
echo "rm /tmp/local_packages.sql"

echo "✅ Prêt ! Exécutez step 3 sur PROD."
echo "Vérif: php artisan tinker --execute=\"App\\\\Models\\\\EventPackage::count()\""
