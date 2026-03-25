USE carr_carrepremiun;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE event_packages;
TRUNCATE TABLE event_seat_zones;

-- Insert event_packages (40 rows from local data.sql)
INSERT INTO `event_packages` VALUES 
(1,2,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-san-francisco-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2154530.00,'XOF',100,10,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(2,2,'Single Match Pitchside Lounge Premium','Single Match Pitchside Lounge Premium','CDM2026-san-francisco-2','Package premium avec services additionnels','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2500000.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(3,2,'VIP Standard','VIP Standard','CDM2026-san-francisco-3','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,1876055.00,'XOF',100,10,NULL,1,3,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(4,2,'VIP Premium','VIP Premium','CDM2026-san-francisco-4','Package VIP premium avec accès special','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,3200000.00,'XOF',100,10,NULL,1,4,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(5,3,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-los-angeles-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2131300.00,'XOF',100,10,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(6,3,'Single Match Pitchside Lounge Premium','Single Match Pitchside Lounge Premium','CDM2026-los-angeles-2','Package premium avec services additionnels','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2620700.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(7,3,'VIP Standard','VIP Standard','CDM2026-los-angeles-3','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,1876055.00,'XOF',100,10,NULL,1,3,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(8,4,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-seattle-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2543625.00,'XOF',100,10,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(9,4,'VIP Standard','VIP Standard','CDM2026-seattle-2','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2100000.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(10,5,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-atlanta-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2200000.00,'XOF',100,10,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(11,5,'VIP Standard','VIP Standard','CDM2026-atlanta-2','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,1850000.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(12,6,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-boston-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2138570.00,'XOF',98,10,NULL,1,1,'2026-02-21 12:04:12','2026-03-15 19:01:34'),
(13,6,'Single Match Pitchside Lounge Premium','Single Match Pitchside Lounge Premium','CDM2026-boston-2','Package premium avec services additionnels','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2298150.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(14,6,'VIP Standard','VIP Standard','CDM2026-boston-3','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,1819145.00,'XOF',100,10,NULL,1,3,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(15,7,'Single Match Pitchside Lounge Standard','Single Match Pitchside Lounge Standard','CDM2026-miami-1','Places haut de gamme le long de la ligne de touche','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2244890.00,'XOF',100,10,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(16,7,'Single Match Pitchside Lounge Premium','Single Match Pitchside Lounge Premium','CDM2026-miami-2','Package premium avec services additionnels','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,2489545.00,'XOF',100,10,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(17,7,'VIP Standard','VIP Standard','CDM2026-miami-3','Places surélevées avec services VIP','Accès au match, Services hospitality, Boissons incluses, Cadeau souvenir',NULL,1787800.00,'XOF',100,10,NULL,1,3,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(18,8,'VIP1 - INFINITY CIRCLE VIP PACKAGE','VIP1 - INFINITY CIRCLE VIP PACKAGE','CONCERT-katy-perry-1',' billet en fosse or debout, produit dérivé VIP, laminé commemoratif','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,239096.00,'XOF',49,6,NULL,1,1,'2026-02-21 12:04:12','2026-03-15 19:06:48'),
(19,8,'VIP2 - LIFETIMES FRONT OF STAGE VIP PACKAGE','VIP2 - LIFETIMES FRONT OF STAGE VIP PACKAGE','CONCERT-katy-perry-2','billet en fosse or debout, produit derive VIP, accesoires','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,203019.00,'XOF',50,6,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(20,9,'VIP - EXPERIENCE DU SALON VIP','VIP - EXPERIENCE DU SALON VIP','CONCERT-one-republic-1','billet premium, acces salon VIP, cadeau VIP','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,199411.00,'XOF',48,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 14:03:29'),
(21,9,'VIP - FORFAIT VIP ENTREE ANTICIPEE','VIP - FORFAIT VIP ENTREE ANTICIPEE','CONCERT-one-republic-2','billet Gold Circle, entree anticipée, laminé VIP','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,112825.00,'XOF',50,6,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(22,10,'PLATINUM 1','PLATINUM 1','CONCERT-sting-1','Package Platinum','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,364056.00,'XOF',49,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-26 20:57:52'),
(23,10,'PLATINUM 2','PLATINUM 2','CONCERT-sting-2','Package Platinum','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,357497.00,'XOF',50,6,NULL,1,2,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(24,11,'VIP PACKAGE','VIP PACKAGE','CONCERT-m-pokora-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,163989.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(25,12,'VIP PACKAGE','VIP PACKAGE','CONCERT-amir-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,144311.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(26,13,'VIP PACKAGE','VIP PACKAGE','CONCERT-soprano-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,131191.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(27,14,'VIP PACKAGE','VIP PACKAGE','CONCERT-dinos-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,118072.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(28,15,'VIP PACKAGE','VIP PACKAGE','CONCERT-kendji-girac-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,124632.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(29,16,'VIP PACKAGE','VIP PACKAGE','CONCERT-indochine-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,183668.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(30,17,'VIP PACKAGE','VIP PACKAGE','CONCERT-nej-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,98394.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(31,18,'VIP PACKAGE','VIP PACKAGE','CONCERT-will-smith-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,229585.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(32,19,'VIP PACKAGE','VIP PACKAGE','CONCERT-yseult-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,104953.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(33,20,'VIP PACKAGE','VIP PACKAGE','CONCERT-pit-baccardi-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,114792.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(34,21,'VIP PACKAGE','VIP PACKAGE','CONCERT-gad-elmaleh-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,131191.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(35,22,'VIP PACKAGE','VIP PACKAGE','CONCERT-angelique-kidjo-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,118072.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(36,23,'VIP PACKAGE','VIP PACKAGE','CONCERT-vybz-kartel-1','Package VIP special','Billet d\'accès, Accès VIP, Cadeau souvenir',NULL,108233.00,'XOF',50,6,NULL,1,1,'2026-02-21 12:04:12','2026-02-21 12:04:12'),
(37,24,'sdvf','dvdf','dfvfdf','dfdd','dgdgdg',NULL,22222.00,'XOF',100,10,NULL,1,1,'2026-02-27 01:00:28','2026-02-27 01:00:28'),
(38,25,'ffs','fsfsfs','fsfsfs','fddfgd','dggdgd',NULL,111.00,'XOF',95,10,NULL,1,1,'2026-02-27 01:04:21','2026-03-15 18:59:19'),
(39,26,'eef','fefe','v\"\'\'','erere','reerfref',NULL,1222222.00,'XOF',98,10,NULL,1,1,'2026-03-04 19:57:41','2026-03-04 19:58:35'),
(40,27,'gfgb','bbfg','fb','fbbg','gbffg',NULL,1222.00,'XOF',100,10,NULL,1,1,'2026-03-09 23:31:48','2026-03-09 23:31:48');

-- Insert event_seat_zones (4 rows)
INSERT INTO `event_seat_zones` VALUES 
(1,24,'Zone Standard','Standard Zone','STD','standard',11.00,NULL,1,1,NULL,NULL,1,'2026-02-27 01:00:28','2026-02-27 01:00:28',NULL),
(2,24,'Zone VIP','VIP Zone','VIP','vip',1.00,NULL,1,1,NULL,NULL,1,'2026-02-27 01:00:28','2026-02-27 01:00:28',NULL),
(3,24,'Zone VVIP','VVIP Zone','VVIP','vvip',2.00,NULL,2,2,NULL,NULL,1,'2026-02-27 01:00:28','2026-02-27 01:00:28',NULL),
(4,24,'Zone Premium','Premium Zone','PREM','premium',3.00,NULL,3,3,NULL,NULL,1,'2026-02-27 01:00:28','2026-02-27 01:00:28',NULL);

SET FOREIGN_KEY_CHECKS = 1;

SELECT '✅ 40 PACKAGES + 4 ZONES importés' as SUCCESS;


