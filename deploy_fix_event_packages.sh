#!/bin/bash
# Script de déploiement pour corriger erreur event_packages manquante
# À exécuter sur le serveur production

echo "=== Fix Event Packages - Déploiement Production ==="

# 1. Se positionner dans le dossier projet (ajuster chemin)
cd /path/to/your/carrent-collaborative/current  # MODIFIER CE CHEMIN

# 2. Git pull les derniers changements
echo "1. Récupération des derniers changements..."
git pull origin main

# 3. Composer install (sans dev)
echo "2. Installation dépendances..."
composer install --no-dev --optimize-autoloader

# 4. Mettre à jour migrations et cache
echo "3. Migrations et optimisations..."
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Permissions
echo "4. Permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Adapter utilisateur web

echo "✅ Déploiement terminé!"
echo "Testez: https://carrepremium.com/events/finale-uefa-europa-league"
echo ""
echo "Vérification table:"
php artisan tinker --execute="Schema::hasTable('event_packages') ? '✅ OK' : '❌ Manquante';"

