-- Create event_series table
CREATE TABLE IF NOT EXISTS `event_series` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_fr` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL, 
  `description_fr` text,
  `description_en` text,
  `venue_name` varchar(255) DEFAULT NULL,
  `venue_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL,
  `sport_type` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_series_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create event_packages table
CREATE TABLE IF NOT EXISTS `event_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `package_name_fr` varchar(255) NOT NULL,
  `package_name_en` varchar(255) DEFAULT NULL,
  `package_code` varchar(255) DEFAULT NULL,
  `description_fr` text,
  `description_included_fr` text,
  `description_included_en` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) NOT NULL DEFAULT 'XAF',
  `available_quantity` int NOT NULL DEFAULT '0',
  `max_per_order` int NOT NULL DEFAULT '10',
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_packages_event_id_foreign` (`event_id`),
  CONSTRAINT `event_packages_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

