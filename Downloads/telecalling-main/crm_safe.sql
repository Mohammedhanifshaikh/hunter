-- Safe idempotent CRM dump (MySQL/MariaDB)
-- Compatible with MariaDB 10.4+ and MySQL 5.7+/8.0+

CREATE DATABASE IF NOT EXISTS `crm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crm`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table: agents
CREATE TABLE IF NOT EXISTS `agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `agent_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `device_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agents`
(`id`, `company_id`, `agent_name`, `phone`, `email`, `password`, `status`, `device_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Soud', '9876543210', 'agent@gmail.com', '$2y$12$HRH8V.CHoAVbqjlAwewQNOHqMWCGxmxcZ00q7FZ/SVKmFqoQjJLpu', 'approved', NULL, '2025-09-27 17:20:06', '2025-09-28 07:58:53'),
(4, 1, 'Soud R', '9876543211', 'agent123@gmail.com', '$2y$12$qz4PQMR.5Mc/pahE7NiAfOecSQEBQEzXbz0MQY9JyJhCllb2z74wa', 'approved', NULL, '2025-10-02 18:31:49', '2025-10-02 18:34:14'),
(5, 2, 'surendra', '9165156200', 'surendrasingh@gmail.com', '$2y$12$.rmIK1xnFpX1RzpehS/pF.3SLE1KNj2u4IfEFmVtmpjRXFVh5MHZ.', 'approved', NULL, '2025-10-04 08:39:46', '2025-10-04 08:42:48')
ON DUPLICATE KEY UPDATE
`company_id`=VALUES(`company_id`),
`agent_name`=VALUES(`agent_name`),
`phone`=VALUES(`phone`),
`email`=VALUES(`email`),
`password`=VALUES(`password`),
`status`=VALUES(`status`),
`device_token`=VALUES(`device_token`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `agents` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- --------------------------------------------------------
-- Table: cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: companies
CREATE TABLE IF NOT EXISTS `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `pan_no` varchar(255) NOT NULL,
  `adhaar_no` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `companies`
(`id`, `company_name`, `company_address`, `pan_no`, `adhaar_no`, `mobile_no`, `email`, `password`, `device_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Saud Travels', '123 abc mumbra mumbai maharastra', 'pan-2bhd27723', '8939387282823', '8283783323', 'company@gmail.com', '$2y$12$OP2UMnzmEob3v74Am25CReQQZ6oyNNol1wjI2dEqYUvyPovr4Nu.u', NULL, 'approved', '2025-09-27 16:15:18', '2025-09-27 17:01:11'),
(2, 'AMazon', '123 abc mumbra mumbai maharastra', 'pan-2bhd27723', '8939387282823', '8283781212', 'amazon@gmail.com', '$2y$12$kPWTC4XV0K2dYk.0G7eEy.LFtGtR5qP8gFvuNh/RqSbaJCisEdKXW', NULL, 'approved', '2025-10-04 08:37:07', '2025-10-04 08:37:59')
ON DUPLICATE KEY UPDATE
`company_name`=VALUES(`company_name`),
`company_address`=VALUES(`company_address`),
`pan_no`=VALUES(`pan_no`),
`adhaar_no`=VALUES(`adhaar_no`),
`mobile_no`=VALUES(`mobile_no`),
`email`=VALUES(`email`),
`password`=VALUES(`password`),
`device_token`=VALUES(`device_token`),
`status`=VALUES(`status`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `companies` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------
-- Table: failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `failed_jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table: jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table: job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: leads
CREATE TABLE IF NOT EXISTS `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sheat_id` bigint(20) NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `lead_source` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `follow_up` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leads`
(`id`, `sheat_id`, `agent_id`, `company_id`, `name`, `email`, `phone`, `lead_source`, `status`, `follow_up`, `created_at`, `updated_at`) VALUES
(15, 8, 1, 1, 'surendra', 'surendra@gmail.com', '9166257832', 'ref', 'new', '2025-09-27', '2025-10-16 18:42:32', '2025-10-16 18:42:32'),
(16, 8, 1, 1, 'saud', 'saud@gmail.com', '9023912345', 'web', 'new', '2025-09-27', '2025-10-16 18:42:32', '2025-10-16 18:42:32'),
(19, 10, 1, 1, 'surendra', 'surendra@gmail.com', '9166257832', 'ref', 'new', '2025-09-27', '2025-10-16 18:54:52', '2025-10-16 18:54:52'),
(20, 10, 5, 1, 'saud', 'saud@gmail.com', '9023912345', 'web', 'new', '2025-09-27', '2025-10-16 18:54:52', '2025-10-16 18:54:52')
ON DUPLICATE KEY UPDATE
`sheat_id`=VALUES(`sheat_id`),
`agent_id`=VALUES(`agent_id`),
`company_id`=VALUES(`company_id`),
`name`=VALUES(`name`),
`email`=VALUES(`email`),
`phone`=VALUES(`phone`),
`lead_source`=VALUES(`lead_source`),
`status`=VALUES(`status`),
`follow_up`=VALUES(`follow_up`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `leads` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

-- --------------------------------------------------------
-- Table: migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_21_172820_create_companies_table', 1),
(5, '2025_09_21_233948_create_oauth_auth_codes_table', 1),
(6, '2025_09_21_233949_create_oauth_access_tokens_table', 1),
(7, '2025_09_21_233950_create_oauth_refresh_tokens_table', 1),
(8, '2025_09_21_233951_create_oauth_clients_table', 1),
(9, '2025_09_21_233952_create_oauth_personal_access_clients_table', 1),
(10, '2025_09_27_174249_create_agents_table', 1),
(13, '2025_09_27_184100_create_leads_table', 2),
(15, '2025_10_16_231705_create_subscriptions_table', 4),
(16, '2025_10_16_230757_create_plans_table', 5),
(17, '2025_09_27_190014_create_sheats_table', 6)
ON DUPLICATE KEY UPDATE
`migration`=VALUES(`migration`),
`batch`=VALUES(`batch`);

ALTER TABLE `migrations` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

-- --------------------------------------------------------
-- Table: oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_access_tokens`
(`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('254ff7e6262f4c13d027e7296e7abd44d1dd45caa14c34b7b41266fabdc4e06666dae3e57529b07c', 5, 1, 'AgentToken', '[]', 0, '2025-10-15 18:17:55', '2025-10-15 18:17:55', '2026-10-15 23:47:55'),
('430e91b56ebdf7243aba2f376b9b306545b6186442571ab8b574fec23180a6c99d030b2ecbfd41e0', 1, 1, 'CompanyToken', '[]', 0, '2025-10-15 18:16:53', '2025-10-15 18:16:55', '2026-10-15 23:46:53'),
('4c18d11e83eb61c2e2abd36d3e67cb6d01cbdd457103ed2c2becadfb45b3e4caad8af696f5c01a0a', 5, 1, 'AgentToken', '[]', 0, '2025-10-04 08:50:26', '2025-10-04 08:50:26', '2026-10-04 14:20:26'),
('559c16eaec09a396d65bc95d30c52f3e3a5ab3cbc03bae46684654c2a6c3c089c0ececfd948b0822', 4, 1, 'AgentToken', '[]', 0, '2025-10-02 18:35:05', '2025-10-02 18:35:08', '2026-10-03 00:05:05'),
('6748173a852a51fca68a88619d90a0b19995a081524cff4cbc7eaed157491c214f550bab9a7ca0ca', 4, 1, 'AgentToken', '[]', 0, '2025-10-04 07:28:55', '2025-10-04 07:28:56', '2026-10-04 12:58:55'),
('716badc200a41e1f65f02653846323ffd3ae6f64aa871565aad3ed3c19156c9aa144b4d9b28281ff', 1, 1, 'CompanyToken', '[]', 0, '2025-10-04 08:09:45', '2025-10-04 08:09:45', '2026-10-04 13:39:45'),
('967816c74cf43a98ad19ba039d61f6979049d260ada80447c1b7dc7900097cb747134061d18892d2', 1, 1, 'CompanyToken', '[]', 0, '2025-10-15 18:26:21', '2025-10-15 18:26:21', '2026-10-15 23:56:21'),
('9e58043fcda1fc1553c955fe56a06c481967116d1debe00886630fcf782c0df677fd9554e4f4f845', 1, 1, 'CompanyToken', '[]', 0, '2025-09-27 16:29:13', '2025-09-27 16:29:15', '2026-09-27 21:59:13'),
('aee3676142305f173083788724673fc7c11684c973f658d4834d01e4c34cdb56de6a6c8b3364b3a1', 2, 1, 'CompanyToken', '[]', 0, '2025-10-04 08:38:07', '2025-10-04 08:38:07', '2026-10-04 14:08:07'),
('e6e78d30dd5427f55a973286ced7e9dfd4dba65934423a86cc07cbd73514cc10e5e39d5034b03d49', 1, 1, 'AgentToken', '[]', 0, '2025-09-27 19:41:07', '2025-09-27 19:41:08', '2026-09-28 01:11:07'),
('f46ffb2aa748be49830625db4c36343be0cf78d80ad860ea1fdbb6b275917c05e7eecac89e92eaad', 2, 1, 'CompanyToken', '[]', 0, '2025-10-15 18:17:11', '2025-10-15 18:17:11', '2026-10-15 23:47:11')
ON DUPLICATE KEY UPDATE
`user_id`=VALUES(`user_id`),
`client_id`=VALUES(`client_id`),
`name`=VALUES(`name`),
`scopes`=VALUES(`scopes`),
`revoked`=VALUES(`revoked`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`),
`expires_at`=VALUES(`expires_at`);

-- --------------------------------------------------------
-- Table: oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_clients`
(`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'GTTia9JZXIQD6seShD9Mgeodl9MmU86z3hjw5z2y', NULL, 'http://localhost', 1, 0, 0, '2025-09-27 16:28:45', '2025-09-27 16:28:45')
ON DUPLICATE KEY UPDATE
`user_id`=VALUES(`user_id`),
`name`=VALUES(`name`),
`secret`=VALUES(`secret`),
`provider`=VALUES(`provider`),
`redirect`=VALUES(`redirect`),
`personal_access_client`=VALUES(`personal_access_client`),
`password_client`=VALUES(`password_client`),
`revoked`=VALUES(`revoked`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `oauth_clients` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Table: oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_personal_access_clients`
(`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-09-27 16:28:46', '2025-09-27 16:28:46')
ON DUPLICATE KEY UPDATE
`client_id`=VALUES(`client_id`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `oauth_personal_access_clients` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Table: oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: plans
CREATE TABLE IF NOT EXISTS `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `max_agents` int(11) NOT NULL,
  `max_leads` int(11) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `plans` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table: sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions`
(`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AJcFOuUPY117BLccimy4zNZcNsS1tv0jEHE5q40C', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkU3M2IwZGRCMXpVdGI0VDNVR3l1MnRRVVdDTGczQWdzb3MyMTdjZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sZWFkLWxpc3QvMTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1760641229),
('bqwXHxqg3OgEicxpA8SMnvkQGVQfCBEkkUQg6Hhq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaWtSRzNGMDd2RmdERXFuTjBEUnpEd2lSUlBPenB3SGRGQjdOMk9idCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FnZW50LWxpc3QiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL215LWFkbWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760638494)
ON DUPLICATE KEY UPDATE
`user_id`=VALUES(`user_id`),
`ip_address`=VALUES(`ip_address`),
`user_agent`=VALUES(`user_agent`),
`payload`=VALUES(`payload`),
`last_activity`=VALUES(`last_activity`);

-- --------------------------------------------------------
-- Table: sheats
CREATE TABLE IF NOT EXISTS `sheats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sheat_name` varchar(255) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `agents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`agents`)),
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sheats`
(`id`, `sheat_name`, `company_id`, `agents`, `status`, `created_at`, `updated_at`) VALUES
(8, 'dummy', 1, '["1"]', 'new', '2025-10-16 18:42:32', '2025-10-16 18:42:32'),
(10, 'dummy', 1, '["1","5"]', 'new', '2025-10-16 18:54:52', '2025-10-16 18:54:52')
ON DUPLICATE KEY UPDATE
`sheat_name`=VALUES(`sheat_name`),
`company_id`=VALUES(`company_id`),
`agents`=VALUES(`agents`),
`status`=VALUES(`status`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `sheats` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

-- --------------------------------------------------------
-- Table: subscriptions
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `plan_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `subscriptions` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users`
(`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$cB1zy3UU.b8qYeZSz5u2uuO6tlvy4VXYfUaitfK/nKcnbuqZPefCe', 'Admin', NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE
`name`=VALUES(`name`),
`email_verified_at`=VALUES(`email_verified_at`),
`password`=VALUES(`password`),
`role`=VALUES(`role`),
`remember_token`=VALUES(`remember_token`),
`created_at`=VALUES(`created_at`),
`updated_at`=VALUES(`updated_at`);

ALTER TABLE `users` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
