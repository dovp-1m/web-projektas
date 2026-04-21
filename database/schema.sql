-- BlogCMS Database Schema
-- Internet Technologies Course Project
-- Vilnius University 2026

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- users table  (User object – does NOT count as an "object"
--               per project requirements but is required)
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)    NOT NULL,
  `email`      VARCHAR(100)   NOT NULL,
  `password`   VARCHAR(255)   NOT NULL,          -- bcrypt hash
  `role`       ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  `created_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`),
  UNIQUE KEY `uq_email`    (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- categories table  (Object 1)
-- Fields (non-FK): name, slug, description, color
-- ============================================================
CREATE TABLE IF NOT EXISTS `categories` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)  NOT NULL,
  `slug`        VARCHAR(100)  NOT NULL,
  `description` TEXT,
  `color`       VARCHAR(7)    NOT NULL DEFAULT '#0d6efd',
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- posts table  (Object 2)
-- Fields (non-FK): title, slug, body, excerpt,
--                  status, featured_image, views, published_at
-- Foreign keys:  category_id -> categories, user_id -> users
-- ============================================================
CREATE TABLE IF NOT EXISTS `posts` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255)  NOT NULL,
  `slug`           VARCHAR(255)  NOT NULL,
  `body`           LONGTEXT      NOT NULL,
  `excerpt`        TEXT,
  `category_id`    INT UNSIGNED,
  `user_id`        INT UNSIGNED,
  `status`         ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `featured_image` VARCHAR(255),
  `views`          INT UNSIGNED  NOT NULL DEFAULT 0,
  `published_at`   TIMESTAMP     NULL DEFAULT NULL,
  `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_category`  (`category_id`),
  KEY `idx_user`      (`user_id`),
  KEY `idx_status`    (`status`),
  CONSTRAINT `fk_post_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_post_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`      (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- logs table  (stores application events / errors)
-- ============================================================
CREATE TABLE IF NOT EXISTS `logs` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `level`       VARCHAR(20)   NOT NULL,           -- INFO | WARNING | ERROR
  `class_name`  VARCHAR(100),
  `method_name` VARCHAR(100),
  `message`     TEXT          NOT NULL,
  `user_id`     INT UNSIGNED  NULL,
  `ip_address`  VARCHAR(45),
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_level`   (`level`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- ci_sessions  (CodeIgniter database session storage)
-- ============================================================
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id`         VARCHAR(128)    NOT NULL,
    `ip_address` VARCHAR(45)     NOT NULL,
    `timestamp`  INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `data`       BLOB            NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
