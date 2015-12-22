ALTER TABLE `sap_ambassadors`
ADD `hash_for_ceating_password` CHAR(42) NULL DEFAULT NULL UNIQUE AFTER `registration_time`,
ADD `has_activated` BOOLEAN NOT NULL DEFAULT FALSE AFTER `hash_for_ceating_password`,
ADD `is_removed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `has_activated`;
