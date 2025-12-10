# 🔧 Solution: Page Blanche - Dossier Vendor Manquant

## 🎯 Problème Identifié

Le diagnostic montre que le dossier `vendor/` est **manquant** sur votre serveur.
C'est la cause de la page blanche.

**Votre serveur:**
- ✅ PHP 8.2.29 (compatible!)
- ✅ Toutes les extensions PHP installées
- ❌ Dossier `vendor/` manquant

---

## 💡 Solution: Installer les Dépendances Composer

Vous devez exécuter `composer install --no-dev` sur votre serveur.

### Option 1: Via Terminal SSH (Si disponible)

```bash
cd /home/u608034730/domains/monnkama.shop/public_html
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option 2: Via Panneau d'Hébergement (Sans Terminal)

Si vous n'avez pas accès SSH, voici comment procéder:

#### A. Installer Composer sur votre hébergement

1. **Connectez-vous à votre panneau d'hébergement** (cPanel, Plesk, etc.)

2. **Accédez au gestionnaire de fichiers**

3. **Créez un fichier `install-composer.php` à la racine:**

```php
<?php
copy('https://getcomposer.org/installer', 'composer-setup.php');
include 'composer-setup.php';
unlink('composer-setup.php');
?>
```

4. **Exécutez ce fichier via votre navigateur:**
   ```
   https://monnkama.shop/install-composer.php
   ```

5. **Cela créera `composer.phar` à la racine**

#### B. Installer les dépendances

1. **Créez un fichier `install-dependencies.php` à la racine:**

```php
<?php
set_time_limit(300); // 5 minutes
ini_set('memory_limit', '512M');

echo "<h1>Installation des dépendances Composer</h1>";
echo "<pre>";

// Aller dans le répertoire du projet
chdir(__DIR__);

// Exécuter composer install
$output = shell_exec('php composer.phar install --no-dev --optimize-autoloader 2>&1');
echo $output;

echo "</pre>";
echo "<h2>Installation terminée!</h2>";
?>
```

2. **Exécutez ce fichier via votre navigateur:**
   ```
   https://monnkama.shop/install-dependencies.php
   ```

3. **Attendez que l'installation se termine** (peut prendre 2-5 minutes)

#### C. Nettoyer les fichiers temporaires

Supprimez ces fichiers après utilisation:
- `install-composer.php`
- `install-dependencies.php`
- `composer.phar` (optionnel, vous pouvez le garder pour les futures mises à jour)
- `public/debug.php`

---

## 🚀 Option 3: Déploiement Automatique via GitHub Actions

Si vous utilisez GitHub, vous pouvez automatiser le déploiement:

1. **Créez `.github/workflows/deploy.yml`:**

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        
    - name: Install Composer dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Deploy to server
      uses: easingthemes/ssh-deploy@main
      env:
        SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
        REMOTE_HOST: ${{ secrets.REMOTE_HOST }}
        REMOTE_USER: ${{ secrets.REMOTE_USER }}
        TARGET: /home/u608034730/domains/monnkama.shop/public_html
```

---

## ✅ Vérification

Après l'installation, vérifiez que:

1. **Le dossier `vendor/` existe** sur votre serveur
2. **Le site fonctionne** (plus de page blanche)
3. **Supprimez `debug.php`** pour la sécurité

---

## 📝 Notes Importantes

### Pourquoi `--no-dev`?

- Installe uniquement les packages de **production**
- Ignore PHPUnit qui requiert PHP 8.3+
- Réduit la taille et améliore les performances

### Fichiers à NE PAS pousser sur Git

Assurez-vous que `.gitignore` contient:
```
/vendor/
/node_modules/
.env
```

Le dossier `vendor/` ne doit **jamais** être dans Git!

---

## 🆘 Besoin d'Aide?

Si vous rencontrez des problèmes:

1. Vérifiez que PHP 8.2+ est installé: `php -v`
2. Vérifiez que Composer est installé: `composer --version`
3. Vérifiez les permissions: `chmod -R 755 storage bootstrap/cache`
4. Consultez les logs d'erreur de votre hébergeur

---

## 🎉 Résultat Attendu

Après avoir suivi ces étapes:
- ✅ Dossier `vendor/` créé
- ✅ Site fonctionnel
- ✅ Compatible PHP 8.2.29
- ✅ Prêt pour la production
