# 🚀 Système de Déploiement Automatique - Carrent Collaborative

## Vue d'ensemble

Votre projet dispose d'un **système de déploiement automatique (CI/CD)** utilisant GitHub Actions pour déployer automatiquement votre application Laravel sur vos serveurs de production.

---

## 📁 Fichiers de Configuration CI/CD

### 1. `.github/workflows/prod.yml` - Déploiement branche `prod`

| Paramètre | Valeur |
|-----------|--------|
| **Déclencheur** | Push sur branche `prod` |
| **Serveur cible** | `/home/carrepremium.com/public_html` |
| **URL de production** | https://carrepremium.com |

**Étapes exécutées :**
1. Checkout du code source
2. Configuration SSH avec clé privée
3. Connexion au serveur
4. Mise à jour du dépôt git
5. Installation des dépendances Composer
6. Optimisation Laravel (clear caches)
7. Installation NPM et build des assets
8. Migration de la base de données
9. Optimisation finale

---

### 2. `.github/workflows/deploy.yml` - Déploiement branche `main`

| Paramètre | Valeur |
|-----------|--------|
| **Déclencheur** | Push sur branche `main` |
| **Serveur cible** | `/home/u608034730/domains/monnkama.shop/public_html` |
| **URL de production** | https://monnkama.shop |

**Étapes exécutées :**
1. Checkout du code source
2. Configuration SSH
3. Connexion au serveur
4. Git fetch et reset hard
5. Composer install
6. NPM install et build
7. Laravel optimize:clear
8. PHP artisan migrate
9. PHP artisan optimize

---

## 🔧 Script de Migration (Manuel)

### Fichier : `migration_script.sh`

Ce script peut être exécuté manuellement sur le serveur pour résoudre les problèmes de base de données :

```bash
# Rendre le script exécutable
chmod +x migration_script.sh

# Exécuter le script
./migration_script.sh
```

**Ce que fait le script :**
- Vérifie la présence du fichier `artisan`
- Affiche le statut des migrations
- Exécute `php artisan migrate --force`
- Affiche le nouveau statut des migrations
- Vide tous les caches Laravel

---

## ⚙️ Configuration Requise (Secrets GitHub)

Pour que les workflows fonctionnent, vous devez configurer ces secrets dans :

**GitHub > Repository > Settings > Secrets and variables > Actions**

### Secrets Obligatoires

| Secret | Description | Exemple |
|--------|-------------|---------|
| `SSH_PRIVATE_KEY` | Clé SSH privée pour la connexion | `-----BEGIN OPENSSH PRIVATE KEY...` |
| `SSH_USER` | Nom d'utilisateur SSH sur le serveur | `u608034730` ou `carrepremium` |
| `SSH_HOST` | Adresse IP ou hostname du serveur | `193.33.61.25` |
| `SSH_PORT` | Port SSH (généralement 22) | `22` |

### Comment générer une clé SSH

```bash
# Sur votre machine locale
ssh-keygen -t ed25519 -C "github-actions"

# Copier la clé publique vers le serveur
ssh-copy-id -p 22 utilisateur@hostname

# Afficher la clé privée pour GitHub
cat ~/.ssh/id_ed25519
```

---

## 📋 Comment Activer le Déploiement Automatique

### Étape 1 : Configurer les secrets GitHub

1. Allez sur **GitHub.com** > Votre repository
2. Cliquez sur **Settings** > **Secrets and variables** > **Actions**
3. Ajoutez chaque secret :
   - `SSH_PRIVATE_KEY` : Collez votre clé privée SSH
   - `SSH_USER` : Votre nom d'utilisateur sur le serveur
   - `SSH_HOST` : L'adresse IP de votre serveur
   - `SSH_PORT` : Le port SSH (22 par défaut)

### Étape 2 : Vérifier les branches

Assurez-vous que vos workflows sont configurés pour les bonnes branches :
- `prod.yml` écoute la branche `prod`
- `deploy.yml` écoute la branche `main`

### Étape 3 : Tester le déploiement

```bash
# Faire un changement et pousser sur main ou prod
git add .
git commit -m "Test déploiement"
git push origin main
# OU
git push origin prod
```

Allez sur **GitHub > Actions** pour voir le déploiement en cours.

---

## 🔄 Processus de Déploiement

```
┌─────────────┐     ┌──────────────────┐     ┌─────────────────┐
│   Git Push  │────▶│  GitHub Actions  │────▶│   Serveur VPS   │
│  (main/prod)│     │   (CI/CD)        │     │  (SSH)          │
└─────────────┘     └──────────────────┘     └─────────────────┘
                           │                          │
                           ▼                          ▼
                    • Checkout code            • git pull
                    • Setup SSH               • composer install
                    • SSH connect             • npm install/build
                                              • php artisan migrate
                                              • php artisan optimize
```

---

## 📝 Commandes Manuelles (si nécessaire)

### Sur le serveur de production

```bash
# Se connecter en SSH
ssh -p 22 utilisateur@hostname

# Aller dans le dossier du projet
cd /home/u608034730/domains/monnkama.shop/public_html

# Mettre à jour le code
git fetch origin main
git reset --hard origin/main

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Nettoyer les caches
php artisan optimize:clear

# Exécuter les migrations
php artisan migrate --force

# Reconstruire les caches
php artisan optimize

# Redémarrer les queues (si nécessaire)
php artisan queue:restart
```

---

## 🛠️ Dépannage

### Problème : "Permission denied" SSH

```bash
# Vérifier les permissions de la clé privée
chmod 600 ~/.ssh/id_ed25519

# Vérifier que la clé publique est sur le serveur
ssh -i ~/.ssh/id_ed25519 -p 22 utilisateur@hostname
```

### Problème : "Table doesn't exist"

Exécuter les migrations manuellement :
```bash
php artisan migrate --force
```

### Problème : Assets non mis à jour

Nettoyer et reconstruire :
```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
npm run build
php artisan optimize
```

---

## 📚 Fichiers de Documentation Complémentaires

| Fichier | Description |
|---------|-------------|
| `DEPLOYMENT_INSTRUCTIONS.md` | Instructions de déploiement détaillées |
| `DEPLOYMENT_GUIDE_PHP83.md` | Guide pour PHP 8.3 |
| `DEPLOIEMENT_PHP82.md` | Guide pour PHP 8.2 |
| `GUIDE_MISE_A_JOUR_PHP_SANS_TERMINAL.md` | Mise à jour PHP sans terminal |

---

## ✅ Résumé

| Élément | Valeur |
|---------|--------|
| **CI/CD** | GitHub Actions |
| **Serveurs** | 2 (carrepremium.com & monnkama.shop) |
| **Déclencheur** | Push sur `main` ou `prod` |
| **Installation** | Composer + NPM |
| **Base de données** | Auto-migration avec `--force` |
| **Secrets requis** | SSH_USER, SSH_HOST, SSH_PORT, SSH_PRIVATE_KEY |

 