# TODO - Implémentation du sélecteur de langue

## ✅ Étapes complétées

1. ✅ **Créer le LanguageController** (`app/Http/Controllers/LanguageController.php`)
   - Méthode `change()` pour changer la langue via AJAX
   - Méthode `current()` pour obtenir la langue actuelle
   - Stockage de la langue dans la session

2. ✅ **Créer le Middleware LanguageSwitcher** (`app/Http/Middleware/LanguageSwitcher.php`)
   - Application automatique de la langue stockée en session
   - Définition de la locale de l'application

3. ✅ **Créer les fichiers de traduction**
   - `resources/lang/fr.json` (langue par défaut - Français)
   - `resources/lang/en.json` (Anglais)

4. ✅ **Ajouter les routes** dans `routes/web.php`
   - Route POST `/language/change` pour changer la langue
   - Route GET `/language/current` pour obtenir la langue actuelle

5. ✅ **Mettre à jour le header** (`resources/views/layouts/header.blade.php`)
   - Ajout du sélecteur de langue avec icônes de drapeaux (FR/EN)
   - Implémentation de la fonction JavaScript `changeLanguage()`
   - Positionné à côté du sélecteur de devise

6. ✅ **Enregistrer le middleware** dans `bootstrap/app.php`
   - Ajout du middleware LanguageSwitcher aux middlewares web globaux

7. ✅ **Configurer la locale par défaut** dans `config/app.php`
   - Changement de la locale par défaut de 'en' à 'fr'
   - Changement de la fallback_locale de 'en' à 'fr'

## 📋 Prochaines étapes

- [ ] Tester le changement de langue dans le navigateur
- [ ] Vérifier que la langue persiste entre les pages
- [ ] S'assurer que l'icône affiche correctement la langue actuelle
- [ ] Tester avec un utilisateur authentifié (sauvegarde de la préférence)

## 📝 Notes

- Le sélecteur de langue est maintenant visible dans la navbar
- Il affiche le drapeau français (FR) ou anglais (EN) selon la langue active
- Le changement de langue recharge automatiquement la page
- La langue est stockée en session et persiste entre les pages
- Pour les utilisateurs authentifiés, la préférence peut être sauvegardée en base de données (nécessite l'ajout du champ `preferred_language` dans la table users)
- [ ] Tester le changement de langue dans le navigateur
- [ ] Vérifier que la langue persiste entre les pages
- [ ] S'assurer que l'icône affiche correctement la langue actuelle
- [ ] Tester avec un utilisateur authentifié (sauvegarde de la préférence)

## 📝 Notes

- Le sélecteur de langue est maintenant visible dans la navbar
- Il affiche le drapeau français (FR) ou anglais (EN) selon la langue active
- Le changement de langue recharge automatiquement la page
- La langue est stockée en session et persiste entre les pages
- Pour les utilisateurs authentifiés, la préférence peut être sauvegardée en base de données (nécessite l'ajout du champ `preferred_language` dans la table users)
