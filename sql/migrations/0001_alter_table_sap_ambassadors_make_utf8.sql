ALTER TABLE `sap_ambassadors` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `sap_ambassadors` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;

# Necessary becuase the above commands will change the TEXT fields to MEDIUMTEXT
ALTER TABLE `sap_ambassadors` CHANGE `why_apply` `why_apply` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, CHANGE `about_you` `about_you` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, CHANGE `organised_event` `organised_event` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
