# 🎯 FIX EVENTS + UEFA PAGE (Local → Prod)

## 1. Local (carrent-collaborative)
```bash
# Fix migration syntax error (delete bad file)
rm database/migrations/2026_03_15_213001_fix_event_type_category_id_nullable.php

# Run pending migrations
php artisan migrate

# Seed UEFA + autres
php artisan db:seed --class=UefaEuropaLeagueSeeder

# Test
php artisan serve
open http://localhost:8000/events/finale-uefa-europa-league
```

## 2. Prod (72.60.90.8)
```bash
ssh root@72.60.90.8
cd /home/carrepremium.com/public_html

# Fix migrations pending
php artisan migrate

# Pull local code + seed
git pull origin main
php artisan db:seed --class=UefaEuropaLeagueSeeder

# Test
curl https://carrepremium.com/events/finale-uefa-europa-league | grep UEFA
```

## 3. DB Prod Sync (26 events + packages)
```bash
# Dump prod events/packages
ssh root@72.60.90.8 "mysqldump -u carrepremium_carrepremium -pJ6CsHtQeRrNXO0
