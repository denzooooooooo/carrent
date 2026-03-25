#!/bin/bash
# Déploiement URGENT packages touristiques PROD
# Exécuter sur SERVEUR 72.60.90.8

echo "🚀 INSERT 3 PACKAGES TOURISTIQUES PROD (simple SQL)"

mysql -u root -p carr_carrepremiun << 'EOF'
USE carr_carrepremiun;

TRUNCATE TABLE tour_packages;

INSERT INTO tour_packages (category_id, title_fr, title_en, slug, description_fr, description_en, package_type, destination, duration, duration_text_fr, departure_city, price, max_participants, min_participants, is_featured, is_active, commission_rate) VALUES
(3, 'Roland Garros 2026 VIP', 'Roland Garros 2026 VIP', 'roland-garros-vip', 'Hospitalité VIP Roland Garros Paris 2026', 'VIP Hospitality Roland Garros 2026', 'luxury', 'Paris, France', 14, '24 mai au 7 juin 2026', 'Abidjan', 265000.00, 500, 1, 1, 1, 20.00),
(3, 'Formule 1 2026 Hospitalité', 'F1 2026 Hospitality', 'f1-2026-hospitality', 'Packages VIP Formule 1 2026 multiples GP', 'F1 2026 VIP Packages Multiple GP', 'adventure', 'Circuits F1 mondiaux', 120, 'Mars à Juin 2026', 'Abidjan', 315000.00, 1000, 3, 1, 1, 20.00),
(3, 'UEFA Europa League Final Istanbul', 'UEFA Europa Final Istanbul', 'uefa-europa-istanbul-2026', 'Finale UEFA Europa League Istanbul 2026', 'UEFA Europa League Final Istanbul 2026', 'luxury', 'Istanbul, Turquie', 3, '20 mai 2026', 'Abidjan', 675000.00, 500, 1, 1, 1, 20.00);

SELECT '✅ 3 PACKAGES TOURISTIQUES INSÉRÉS SIMPLEMENT' as status;
SELECT COUNT(*) as total FROM tour_packages;
SELECT id, title_fr, package_type FROM tour_packages;
EOF

echo "🌐 Vérifiez : https://carrepremium.com/admin/packages"
echo "🎉 TERMINÉ !"

