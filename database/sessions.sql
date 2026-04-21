-- CodeIgniter 3 database sessions table.
-- Must exist BEFORE the application starts if sess_driver = 'database'.
-- This is appended to schema.sql automatically, or run separately.

CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         VARCHAR(128)    NOT NULL,
    `ip_address` VARCHAR(45)     NOT NULL,
    `timestamp`  INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `data`       BLOB            NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
