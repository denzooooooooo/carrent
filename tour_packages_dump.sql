-- MySQL dump 10.13  Distrib 9.5.0, for macos26.0 (arm64)
--
-- Host: localhost    Database: carrepremium
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '46b98398-408e-11f0-9773-b34b5df09b37:1-8123';

--
-- Table structure for table `tour_packages`
--

DROP TABLE IF EXISTS `tour_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `title_fr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_fr` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `package_type` enum('helicopter','private_jet','cruise','safari','city_tour','adventure','luxury','sport_event','motorsport','football') COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL COMMENT 'Duration in days',
  `duration_text_fr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_text_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departure_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'XOF',
  `event_date_start` date DEFAULT NULL,
  `event_date_end` date DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL COMMENT 'Prix d''achat/coût',
  `max_participants` int NOT NULL DEFAULT '1',
  `min_participants` int NOT NULL DEFAULT '1',
  `included_services_fr` json DEFAULT NULL COMMENT 'Array of included services',
  `included_services_en` json DEFAULT NULL,
  `excluded_services_fr` json DEFAULT NULL,
  `excluded_services_en` json DEFAULT NULL,
  `itinerary_fr` json DEFAULT NULL COMMENT 'Day by day itinerary',
  `itinerary_en` json DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_dates` json DEFAULT NULL COMMENT 'Array of available dates',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_reviews` int NOT NULL DEFAULT '0',
  `meta_title_fr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_fr` text COLLATE utf8mb4_unicode_ci,
  `meta_description_en` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profit_margin` decimal(5,2) DEFAULT NULL COMMENT 'Marge bénéficiaire en %',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '20.00' COMMENT 'Taux de commission en %',
  `supplier_cost` decimal(10,2) DEFAULT NULL COMMENT 'Coût fournisseur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tour_packages_slug_unique` (`slug`),
  KEY `tour_packages_category_id_foreign` (`category_id`),
  KEY `tour_packages_slug_index` (`slug`),
  KEY `tour_packages_package_type_index` (`package_type`),
  KEY `tour_packages_destination_index` (`destination`),
  FULLTEXT KEY `idx_ft_title_description` (`title_fr`,`description_fr`),
  CONSTRAINT `tour_packages_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_packages`
--

LOCK TABLES `tour_packages` WRITE;
/*!40000 ALTER TABLE `tour_packages` DISABLE KEYS */;
INSERT INTO `tour_packages` VALUES (1,3,'Roland Garros 2026 – Hospitalite VIP','Roland Garros 2026 – VIP Hospitality','roland-garros-2026','Vivez Roland-Garros comme jamais avec nos packages hospitalite VIP. Acces aux salons prives au coeur du court Philippe-Chatrier, restauration gastronomique, boissons a discretion et cadeaux griffes Roland-Garros. Plusieurs formules disponibles selon vos dates et votre budget, a partir de 265 000 FCFA par personne.\n\nFormules disponibles :\n• Le Comptoir – Salon partage, buffet dejeunatoire/dinatoire, places Cat. 1 ou Or\n• La Brasserie des Mousquetaires – Table reservee, repas assis gastronomique, parking inclus\n• Le Cercle – Salon sous le court Suzanne-Lenglen, Cat. 1 ou Or\n• Club Chatrier – Loge privee 4 personnes sur le court Philippe-Chatrier\n• Le Club Gold – Formule Gold premium avec parking inclus','Experience Roland-Garros like never before with our VIP hospitality packages. Access to private lounges at the heart of Philippe-Chatrier court, gastronomic catering, drinks and Roland-Garros branded gifts. Multiple formulas available from 265,000 FCFA per person.','sport_event','Paris, France',1,'24 mai – 7 juin 2026','May 24 – June 7, 2026','Abidjan',265000.00,'XOF','2026-05-24','2026-06-07',NULL,NULL,500,1,'[\"Accueil privatif\", \"Places en tribune sur le court Philippe-Chatrier ou Suzanne-Lenglen\", \"Acces libre aux courts annexes selon la session choisie\", \"Salon partage ou loge privee selon la formule\", \"Restauration (buffet ou repas assis gastronomique selon formule)\", \"Boissons a discretion tout au long de la journee\", \"1 cadeau griffe Roland-Garros par invite et par session\", \"Acces wi-fi gratuit et illimite\", \"Vestiaire\", \"Possibilite d\'ajouter les vols + hotel\"]','[\"Private welcome\", \"Seats on Philippe-Chatrier or Suzanne-Lenglen court\", \"Free access to side courts\", \"Shared lounge or private box depending on formula\", \"Catering (buffet or seated gastronomic meal)\", \"Drinks throughout the day\", \"1 Roland-Garros branded gift per guest per session\", \"Free unlimited Wi-Fi\", \"Cloakroom\", \"Option to add flights + hotel\"]','[\"Vols et hebergement (disponibles en option)\", \"Parking (inclus uniquement dans la formule Brasserie et Club Gold)\", \"Depenses personnelles\"]','[\"Flights and accommodation (available as option)\", \"Parking (included only in Brasserie and Club Gold formulas)\", \"Personal expenses\"]','[]','[]',NULL,'[]',NULL,'[{\"date\": \"2026-05-30\", \"label\": \"2eme tour – Cat. 1 – Soiree\", \"price\": 605000, \"formule\": \"Le Comptoir\"}, {\"date\": \"2026-06-04\", \"label\": \"1/2 finale – Cat. 1 – Journee\", \"price\": 790000, \"formule\": \"Le Comptoir\"}, {\"date\": \"2026-06-04\", \"label\": \"1/2 finale – Cat. Or – Journee\", \"price\": 910000, \"formule\": \"Le Comptoir\"}, {\"date\": \"2026-05-28\", \"label\": \"2eme tour – Cat. 1 – Soiree\", \"price\": 665000, \"formule\": \"La Brasserie des Mousquetaires\"}, {\"date\": \"2026-06-02\", \"label\": \"1/4 de finale – Cat. 1 – Soiree\", \"price\": 870000, \"formule\": \"La Brasserie des Mousquetaires\"}, {\"date\": \"2026-05-24\", \"label\": \"1er tour – Cat. 1 – Soiree\", \"price\": 265000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-24\", \"label\": \"1er tour – Cat. Or – Soiree\", \"price\": 305000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-25\", \"label\": \"1er tour – Cat. 1 – Soiree\", \"price\": 330000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-25\", \"label\": \"1er tour – Cat. Or – Soiree\", \"price\": 385000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-28\", \"label\": \"2eme tour – Cat. 1 – Soiree\", \"price\": 665000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-28\", \"label\": \"2eme tour – Cat. Or – Soiree\", \"price\": 760000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-29\", \"label\": \"3eme tour – Cat. 1 – Soiree\", \"price\": 580000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-29\", \"label\": \"3eme tour – Cat. Or – Soiree\", \"price\": 665000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-30\", \"label\": \"3eme tour – Cat. 1 – Soiree\", \"price\": 760000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-30\", \"label\": \"3eme tour – Cat. Or – Soiree\", \"price\": 870000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-06-04\", \"label\": \"1/2 finale – Cat. 1 – Journee\", \"price\": 760000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-06-04\", \"label\": \"1/2 finale – Cat. Or – Journee\", \"price\": 870000, \"formule\": \"Le Cercle\"}, {\"date\": \"2026-05-24\", \"label\": \"1er tour\", \"price\": 915000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-25\", \"label\": \"1er tour\", \"price\": 1055000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-26\", \"label\": \"1er tour\", \"price\": 1385000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-27\", \"label\": \"1er tour\", \"price\": 1570000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-28\", \"label\": \"2eme tour\", \"price\": 1950000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-29\", \"label\": \"2eme tour\", \"price\": 1950000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-30\", \"label\": \"3eme tour\", \"price\": 1705000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-31\", \"label\": \"3eme tour\", \"price\": 1685000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-06-01\", \"label\": \"4eme tour\", \"price\": 2160000, \"formule\": \"Club Chatrier (Loge 4 pers.)\"}, {\"date\": \"2026-05-26\", \"label\": \"1er tour\", \"price\": 1785000, \"formule\": \"Le Club Gold\"}, {\"date\": \"2026-05-28\", \"label\": \"2eme tour\", \"price\": 2490000, \"formule\": \"Le Club Gold\"}, {\"date\": \"2026-05-29\", \"label\": \"2eme tour\", \"price\": 2490000, \"formule\": \"Le Club Gold\"}]',1,1,0.00,0,NULL,NULL,NULL,NULL,'2026-02-26 23:59:16','2026-02-26 23:59:16',NULL,20.00,NULL),(2,3,'Formule 1 2026 – Packages Hospitalite','Formula 1 2026 – Hospitality Packages','formule-1-2026','Vivez la Formule 1 2026 dans les meilleures conditions avec nos packages hospitalite officiels. Disponibles sur 6 Grands Prix : Shanghai, Bahrain, Djeddah, Miami, Canada et Monaco. Plusieurs formules par GP, de la tribune couverte au Paddock Club exclusif.\n\nGrands Prix disponibles :\n• Shanghai (13-15 mars 2026) : Champion Club a partir de 3 280 000 FCFA, Gordon Ramsay Paddock a partir de 11 500 000 FCFA\n• Bahrain (10-12 avril 2026) : Le Dome a partir de 890 000 FCFA, Turn 1 Lounge a partir de 530 000 FCFA, Tribune Principale a partir de 430 000 FCFA, Tribune Virage 1 a partir de 315 000 FCFA\n• Djeddah (17-19 avril 2026) : Premium Lounge a partir de 2 120 000 FCFA, Paddock Club a partir de 5 330 000 FCFA\n• Miami (1-3 mai 2026) : Start/Finish a partir de 1 590 000 FCFA, North Beach a partir de 650 000 FCFA\n• Canada (22-24 mai 2026) : VIP Elite Suite a partir de 3 860 000 FCFA, Elite Club a partir de 3 280 000 FCFA, La Jamaique a partir de 1 265 000 FCFA, Privilege 12 a partir de 1 245 000 FCFA, La Toundra a partir de 1 180 000 FCFA, La Terrasse 21 a partir de 1 020 000 FCFA\n• Monaco (5-7 juin 2026) : Platinum Terraces a partir de 4 965 000 FCFA, Gold VIP Terrace a partir de 4 100 000 FCFA, VIP Race Garden a partir de 3 760 000 FCFA, Silver Terraces a partir de 3 235 000 FCFA, Bronze Terrace a partir de 2 845 000 FCFA, Trackside Experience a partir de 2 330 000 FCFA','Experience Formula 1 2026 in the best conditions with our official hospitality packages. Available on 6 Grand Prix: Shanghai, Bahrain, Jeddah, Miami, Canada and Monaco. Multiple formulas per GP, from covered grandstands to the exclusive Paddock Club.','motorsport','Shanghai / Bahrain / Djeddah / Miami / Canada / Monaco',3,'Mars – Juin 2026','March – June 2026','Abidjan',315000.00,'XOF','2026-03-13','2026-06-07',NULL,NULL,1000,1,'[\"Acces au circuit pendant 3 jours (selon formule)\", \"Places assises garanties (selon formule)\", \"Restauration et boissons (selon formule)\", \"Acces hospitalite VIP (selon formule)\", \"Acces paddock (formules Paddock Club uniquement)\", \"Tickets officiels Formula 1\", \"Possibilite d\'ajouter les vols + hotel\"]','[\"3-day circuit access (depending on formula)\", \"Guaranteed seating (depending on formula)\", \"Catering and drinks (depending on formula)\", \"VIP hospitality access (depending on formula)\", \"Paddock access (Paddock Club formulas only)\", \"Official Formula 1 tickets\", \"Option to add flights + hotel\"]','[\"Vols et hebergement (disponibles en option)\", \"Depenses personnelles\", \"Transport vers le circuit\"]','[\"Flights and accommodation (available as option)\", \"Personal expenses\", \"Transport to the circuit\"]','[]','[]',NULL,'[]',NULL,'[{\"gp\": \"Shanghai\", \"date\": \"2026-03-13\", \"label\": \"Shanghai 13-15 mars – Champion Club\", \"price\": 3280000, \"formule\": \"Champion Club\"}, {\"gp\": \"Shanghai\", \"date\": \"2026-03-13\", \"label\": \"Shanghai 13-15 mars – Gordon Ramsay Paddock\", \"price\": 11500000, \"formule\": \"Gordon Ramsay dans le Paddock\"}, {\"gp\": \"Bahrain\", \"date\": \"2026-04-10\", \"label\": \"Bahrain 10-12 avr – Le Dome\", \"price\": 890000, \"formule\": \"Le Dome\"}, {\"gp\": \"Bahrain\", \"date\": \"2026-04-10\", \"label\": \"Bahrain 10-12 avr – Turn 1 & Corporate Lounge\", \"price\": 530000, \"formule\": \"Turn 1 & Corporate Lounge\"}, {\"gp\": \"Bahrain\", \"date\": \"2026-04-10\", \"label\": \"Bahrain 10-12 avr – Tribune Principale\", \"price\": 430000, \"formule\": \"Tribune Principale\"}, {\"gp\": \"Bahrain\", \"date\": \"2026-04-10\", \"label\": \"Bahrain 10-12 avr – Tribune Virage 1\", \"price\": 315000, \"formule\": \"Tribune Virage 1\"}, {\"gp\": \"Djeddah\", \"date\": \"2026-04-17\", \"label\": \"Djeddah 17-19 avr – Premium Lounge\", \"price\": 2120000, \"formule\": \"Premium Lounge\"}, {\"gp\": \"Djeddah\", \"date\": \"2026-04-17\", \"label\": \"Djeddah 17-19 avr – Paddock Club\", \"price\": 5330000, \"formule\": \"Paddock Club\"}, {\"gp\": \"Miami\", \"date\": \"2026-05-01\", \"label\": \"Miami 1-3 mai – Start/Finish Grandstand\", \"price\": 1590000, \"formule\": \"Start/Finish Grandstand\"}, {\"gp\": \"Miami\", \"date\": \"2026-05-01\", \"label\": \"Miami 1-3 mai – North Beach Grandstand\", \"price\": 650000, \"formule\": \"North Beach Grandstand\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – VIP Fan\'s Elite Suite\", \"price\": 3860000, \"formule\": \"VIP Fan\'s Elite Suite\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – Elite Club\", \"price\": 3280000, \"formule\": \"Elite Club\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – La Jamaique\", \"price\": 1265000, \"formule\": \"La Jamaique\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – Privilege 12\", \"price\": 1245000, \"formule\": \"Privilege 12\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – La Toundra\", \"price\": 1180000, \"formule\": \"La Toundra\"}, {\"gp\": \"Canada\", \"date\": \"2026-05-22\", \"label\": \"Canada 22-24 mai – La Terrasse 21\", \"price\": 1020000, \"formule\": \"La Terrasse 21\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – Platinum Terraces\", \"price\": 4965000, \"formule\": \"Platinum Terraces\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – Gold VIP Terrace\", \"price\": 4100000, \"formule\": \"Gold VIP Terrace\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – VIP Race Garden\", \"price\": 3760000, \"formule\": \"VIP Race Garden\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – Silver Terraces\", \"price\": 3235000, \"formule\": \"Silver Terraces\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – Bronze Terrace\", \"price\": 2845000, \"formule\": \"Bronze Terrace\"}, {\"gp\": \"Monaco\", \"date\": \"2026-06-05\", \"label\": \"Monaco 5-7 juin – Trackside Experience\", \"price\": 2330000, \"formule\": \"Trackside Experience\"}]',1,1,0.00,0,NULL,NULL,NULL,NULL,'2026-02-26 23:59:16','2026-02-26 23:59:16',NULL,20.00,NULL),(3,3,'Finale UEFA Europa League – Istanbul 2026','UEFA Europa League Final – Istanbul 2026','finale-uefa-europa-league-istanbul-2026','Vivez la Finale de l\'UEFA Europa League le 20 mai 2026 a Istanbul dans une loge privee Shared Skybox. Trois formules disponibles : Platinum, Gold et Silver. Chaque formule inclut un acces hospitalite premium 3 heures avant le coup d\'envoi, cocktail dinatoire avec specialites locales et internationales, boissons incluses et diffusion TV.\n\nFormules disponibles :\n• Platinum – Emplacement central, meilleure visibilite, loge privee VIP exclusive : a partir de 975 000 FCFA/pers.\n• Gold – Vue panoramique, loge privee premium, acces hospitalite VIP : a partir de 825 000 FCFA/pers.\n• Silver – Vue immersive proche de l\'action, loge privee, experience VIP accessible : a partir de 675 000 FCFA/pers.','Experience the UEFA Europa League Final on May 20, 2026 in Istanbul in a private Shared Skybox. Three formulas available: Platinum, Gold and Silver. Each formula includes premium hospitality access 3 hours before kick-off, dinner cocktail with local and international specialties, drinks included and TV broadcast.','football','Istanbul, Turquie',1,'20 mai 2026','May 20, 2026','Abidjan',675000.00,'XOF','2026-05-20','2026-05-20',NULL,NULL,500,1,'[\"Billet pour le match (Shared Skybox)\", \"Loge privee avec confort premium\", \"Acces hospitalite premium 3 heures avant le coup d\'envoi\", \"Cocktail dinatoire debout avec specialites locales et internationales\", \"Boisson de bienvenue a l\'arrivee\", \"Vins, bieres et boissons sans alcool inclus\", \"Service de boissons continu\", \"Diffusion TV avant, pendant et apres le match\", \"Wi-Fi disponible\", \"Acces hospitalite 90 minutes apres le match\", \"Possibilite d\'ajouter les vols + hotel\"]','[\"Match ticket (Shared Skybox)\", \"Private box with premium comfort\", \"Premium hospitality access 3 hours before kick-off\", \"Standing dinner cocktail with local and international specialties\", \"Welcome drink on arrival\", \"Wines, beers and soft drinks included\", \"Continuous drinks service\", \"TV broadcast before, during and after the match\", \"Wi-Fi available\", \"Hospitality access 90 minutes after the match\", \"Option to add flights + hotel\"]','[\"Vols et hebergement (disponibles en option)\", \"Parking (non inclus et non disponible)\", \"Depenses personnelles\"]','[\"Flights and accommodation (available as option)\", \"Parking (not included and not available)\", \"Personal expenses\"]','[]','[]',NULL,'[]',NULL,'[{\"date\": \"2026-05-20\", \"label\": \"Finale – Shared Skybox Platinum – Emplacement central, meilleure visibilite\", \"price\": 975000, \"formule\": \"Platinum\"}, {\"date\": \"2026-05-20\", \"label\": \"Finale – Shared Skybox Gold – Vue panoramique, loge privee premium\", \"price\": 825000, \"formule\": \"Gold\"}, {\"date\": \"2026-05-20\", \"label\": \"Finale – Shared Skybox Silver – Vue immersive, experience VIP accessible\", \"price\": 675000, \"formule\": \"Silver\"}]',1,1,0.00,0,NULL,NULL,NULL,NULL,'2026-02-26 23:59:16','2026-02-26 23:59:16',NULL,20.00,NULL),(4,2,'fsfsf','ssh','fsfsf','fsfgsgfs','sfggfsgfs','motorsport','ffg',2,'3jours',NULL,'sfggfs',22222.00,'XOF',NULL,NULL,222.00,NULL,10,1,'[\"sff\"]',NULL,'[\"ffssf\"]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,0.00,0,NULL,NULL,NULL,NULL,'2026-03-04 19:55:08','2026-03-04 19:55:08',NULL,20.00,NULL),(5,2,'c','cvv','c','cvvcvc','cvvcv','motorsport','vcvc',4,'7h',NULL,'vcvc',22222.00,'XOF',NULL,NULL,2222.00,NULL,10,1,'[\"gg\"]',NULL,'[\"gccc\"]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,0.00,0,NULL,NULL,NULL,NULL,'2026-03-09 23:41:10','2026-03-09 23:41:10',NULL,20.00,NULL);
/*!40000 ALTER TABLE `tour_packages` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-17 15:33:26
