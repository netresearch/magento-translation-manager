--
-- Datenbank: `magento_translation`
--
-- CREATE DATABASE IF NOT EXISTS `magento_translation` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- USE `magento_translation`;


--
-- Tabellenstruktur für Tabelle `locale`
--
CREATE TABLE IF NOT EXISTS `locale` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `locale` varchar(5) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `locale` (`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation`
--
CREATE TABLE IF NOT EXISTS `translation` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `baseId` int(10) UNSIGNED NOT NULL,
    `locale` varchar(5) NOT NULL,
    `translation` text DEFAULT NULL,
    `unclear` tinyint(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `baseId` (`baseId`),
    KEY `locale` (`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation_base`
--
CREATE TABLE IF NOT EXISTS `translationBase` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `fileId` int(10) UNSIGNED NOT NULL,
    `file` varchar(256) DEFAULT NULL,
    `originSource` text DEFAULT NULL,
    `notInUse` tinyint(4) NOT NULL DEFAULT 0,
    `screenPath` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fileId` (`fileId`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation_file`
--
CREATE TABLE IF NOT EXISTS `translationFile` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(255) NOT NULL,
    `sourcePath` text DEFAULT NULL,
    `destinationPath` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `suggestion`
--
CREATE TABLE IF NOT EXISTS `suggestion` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `translationId` int(10) UNSIGNED NOT NULL,
    `suggestion` text NOT NULL,
    PRIMARY KEY (`id`),
    KEY `translationId` (`translationId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


--
-- Tabellenstruktur für Tabelle `translation_base_to_version`
--
--CREATE TABLE IF NOT EXISTS `translation_base_to_version` (
--    `translation_base_to_version_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
--    `base_id` int(10) UNSIGNED NOT NULL,
--    `mage_version` varchar(20) NOT NULL COMMENT 'version of magento',
--    `dev_stage` varchar(20) DEFAULT NULL COMMENT 'suffix e.g. beta or rc1',
--    PRIMARY KEY (`translation_base_to_version_id`),
--    KEY `base_id` (`base_id`)
--) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='all Magento versions';


--
-- Constraints der Tabelle `translation`
--
ALTER TABLE `translation`
    ADD CONSTRAINT `FK_base_translation` FOREIGN KEY (`base_id`) REFERENCES `translation_base` (`base_id`),
    ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`base_id`) REFERENCES `translation_base` (`base_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
