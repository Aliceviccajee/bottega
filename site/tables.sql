-- local_bottega_development.wp_woocommerce_shipping_zones definition

CREATE TABLE `wp_delivery_slots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_date` DATE DEFAULT NOW(),
  `time` TIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;