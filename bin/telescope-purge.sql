SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
truncate table telescope_entries_tags;
truncate table telescope_monitoring;
truncate table telescope_entries;
COMMIT ;
SET FOREIGN_KEY_CHECKS = 1;
