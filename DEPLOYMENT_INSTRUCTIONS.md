# Instructions de déploiement pour hébergement mutualisé

## Étape 1: Connexion à votre serveur
Connectez-vous à votre hébergement mutualisé via SSH ou FTP/SFTP

## Étape 2: Aller dans le répertoire du projet
```bash
cd /chemin/vers/votre/projet/carrent-collaborative
```

## Étape 3: Récupérer les dernières modifications
```bash
git pull origin main
```

## Étape 4: Vérifier que les assets sont présents
```bash
ls -la public/build/
ls -la public/build/assets/
```

## Étape 5: Nettoyer les caches Laravel (optionnel mais recommandé)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Étape 6: Vérifier les permissions (si nécessaire)
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## Étape 7: Tester le site
Visitez votre site web pour vérifier que les styles s'affichent correctement

## Fichiers importants déployés
- `public/build/manifest.json` - Manifest Vite
- `public/build/assets/app-inwEMoMZ.css` - Styles CSS compilés
- `public/build/assets/app-Bj43h_rG.js` - JavaScript compilé
- `bootstrap/cache/config.php` - Cache de configuration Laravel
- `bootstrap/cache/routes-v7.php` - Cache des routes Laravel

## Résolution du problème
Le problème des styles qui ne s'affichaient pas était dû au fait que le dossier `public/build` était exclu par `.gitignore`, empêchant le déploiement des assets compilés. Cette exclusion a été supprimée et tous les fichiers de build ont été ajoutés au dépôt.
