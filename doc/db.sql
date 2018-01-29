--
-- Datenbank: `magento_translation`
--
-- CREATE DATABASE IF NOT EXISTS `magento_translation` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- USE `magento_translation`;


--
-- Tabellenstruktur für Tabelle `suggestion`
--
CREATE TABLE IF NOT EXISTS `suggestion` (
    `suggestion_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `translation_id` int(10) UNSIGNED NOT NULL,
    `suggested_translation` text NOT NULL,
    PRIMARY KEY (`suggestion_id`),
    KEY `translation_id` (`translation_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `supported_locale`
--
CREATE TABLE IF NOT EXISTS `supported_locale` (
    `locale` varchar(5) NOT NULL,
    UNIQUE KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation`
--
CREATE TABLE IF NOT EXISTS `translation` (
    `translation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `base_id` int(10) UNSIGNED NOT NULL,
    `locale` varchar(5) NOT NULL,
    `current_translation` text DEFAULT NULL,
    `unclear_translation` tinyint(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`translation_id`),
    KEY `base_id` (`base_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation_base`
--
CREATE TABLE IF NOT EXISTS `translation_base` (
    `base_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `translation_file_id` int(10) UNSIGNED NOT NULL,
    `translation_file` varchar(256) NOT NULL,
    `origin_source` text DEFAULT NULL,
    `not_in_use` tinyint(4) NOT NULL DEFAULT 0,
    `screen_path` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`base_id`),
    KEY `translation_file_id` (`translation_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation_base_to_version`
--
CREATE TABLE IF NOT EXISTS `translation_base_to_version` (
    `translation_base_to_version_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `base_id` int(10) UNSIGNED NOT NULL,
    `mage_version` varchar(20) NOT NULL COMMENT 'version of magento',
    `dev_stage` varchar(20) DEFAULT NULL COMMENT 'suffix e.g. beta or rc1',
    PRIMARY KEY (`translation_base_to_version_id`),
    KEY `base_id` (`base_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='all Magento versions';


--
-- Tabellenstruktur für Tabelle `translation_file`
--
CREATE TABLE IF NOT EXISTS `translation_file` (
    `translation_file_id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(255) NOT NULL,
    `source_path` text NOT NULL,
    `destination_path` text NOT NULL,
    PRIMARY KEY (`translation_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Constraints der Tabelle `translation`
--
ALTER TABLE `translation`
    ADD CONSTRAINT `FK_base_translation` FOREIGN KEY (`base_id`) REFERENCES `translation_base` (`base_id`),
    ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `translation_base` (`base_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
