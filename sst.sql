-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 08 Des 2025 pada 00.59
-- Versi server: 11.4.2-MariaDB-log
-- Versi PHP: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sst`
--
CREATE DATABASE IF NOT EXISTS `sst` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sst`;

DELIMITER $$
--
-- Prosedur
--
DROP PROCEDURE IF EXISTS `calculate_country_statistics`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_country_statistics` (IN `upload_id_param` INT)   BEGIN
    -- Hapus statistik lama jika ada
    DELETE FROM country_statistics WHERE upload_id = upload_id_param;

    -- Insert statistik baru per negara (khusus PMA)
    INSERT INTO country_statistics (
        upload_id,
        country,
        project_count
    )
    SELECT
        upload_id_param,
        COALESCE(country, 'Tidak Diketahui') as country,
        COUNT(*) as project_count
    FROM projects
    WHERE upload_id = upload_id_param
    AND investment_type = 'PMA'
    AND country IS NOT NULL
    AND country != ''
    GROUP BY country
    ORDER BY project_count DESC;
END$$

DROP PROCEDURE IF EXISTS `calculate_district_statistics`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_district_statistics` (IN `upload_id_param` INT)   BEGIN
    -- Hapus statistik lama jika ada
    DELETE FROM district_statistics WHERE upload_id = upload_id_param;

    -- Insert statistik baru per kecamatan
    INSERT INTO district_statistics (
        upload_id,
        subdistrict,
        projects_pma,
        projects_pmdn,
        investment_pma,
        investment_pmdn,
        additional_investment_pma,
        additional_investment_pmdn,
        tki_pma,
        tki_pmdn,
        tka_pma,
        tka_pmdn
    )
    SELECT
        upload_id_param,
        subdistrict,
        -- Projects count
        SUM(CASE WHEN investment_type = 'PMA' THEN 1 ELSE 0 END) as projects_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN 1 ELSE 0 END) as projects_pmdn,
        -- Total investment
        SUM(CASE WHEN investment_type = 'PMA' THEN total_investment ELSE 0 END) as investment_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN total_investment ELSE 0 END) as investment_pmdn,
        -- Additional investment
        SUM(CASE WHEN investment_type = 'PMA' THEN additional_investment ELSE 0 END) as additional_investment_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN additional_investment ELSE 0 END) as additional_investment_pmdn,
        -- TKI
        SUM(CASE WHEN investment_type = 'PMA' THEN tki ELSE 0 END) as tki_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN tki ELSE 0 END) as tki_pmdn,
        -- TKA
        SUM(CASE WHEN investment_type = 'PMA' THEN tka ELSE 0 END) as tka_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN tka ELSE 0 END) as tka_pmdn
    FROM projects
    WHERE upload_id = upload_id_param
    AND subdistrict IS NOT NULL
    AND subdistrict != ''
    GROUP BY subdistrict
    ORDER BY subdistrict;
END$$

DROP PROCEDURE IF EXISTS `calculate_period_statistics`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_period_statistics` (IN `upload_id_param` INT)   BEGIN
    -- Hapus statistik lama jika ada
    DELETE FROM period_statistics WHERE upload_id = upload_id_param;

    -- Insert statistik baru per periode
    INSERT INTO period_statistics (
        upload_id,
        period,
        project_count
    )
    SELECT
        upload_id_param,
        period_stage,
        COUNT(*) as project_count
    FROM projects
    WHERE upload_id = upload_id_param
    AND period_stage IS NOT NULL
    AND period_stage != ''
    GROUP BY period_stage
    ORDER BY period_stage;
END$$

DROP PROCEDURE IF EXISTS `calculate_sector_statistics`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_sector_statistics` (IN `upload_id_param` INT)   BEGIN
    -- Hapus statistik lama jika ada
    DELETE FROM sector_statistics WHERE upload_id = upload_id_param;

    -- Hitung total proyek untuk persentase
    SET @total_projects = (SELECT COUNT(*) FROM projects WHERE upload_id = upload_id_param AND sector_detail IS NOT NULL AND sector_detail != '');

    -- Insert statistik baru per sektor
    INSERT INTO sector_statistics (
        upload_id,
        sector,
        project_count,
        percentage
    )
    SELECT
        upload_id_param,
        sector_detail,
        COUNT(*) as project_count,
        ROUND((COUNT(*) / @total_projects) * 100, 2) as percentage
    FROM projects
    WHERE upload_id = upload_id_param
    AND sector_detail IS NOT NULL
    AND sector_detail != ''
    GROUP BY sector_detail
    ORDER BY project_count DESC;
END$$

DROP PROCEDURE IF EXISTS `calculate_upload_statistics`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_upload_statistics` (IN `upload_id_param` INT)   BEGIN
    -- Hapus statistik lama jika ada
    DELETE FROM upload_statistics WHERE upload_id = upload_id_param;

    -- Insert statistik baru
    INSERT INTO upload_statistics (
        upload_id,
        total_projects_pma,
        total_projects_pmdn,
        total_investment_pma,
        total_investment_pmdn,
        additional_investment_pma,
        additional_investment_pmdn,
        total_tki_pma,
        total_tki_pmdn,
        total_tka_pma,
        total_tka_pmdn,
        realization_investment_pma,
        realization_investment_pmdn
    )
    SELECT
        upload_id_param,
        -- Total projects
        SUM(CASE WHEN investment_type = 'PMA' THEN 1 ELSE 0 END) as total_projects_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN 1 ELSE 0 END) as total_projects_pmdn,
        -- Total investment
        SUM(CASE WHEN investment_type = 'PMA' THEN total_investment ELSE 0 END) as total_investment_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN total_investment ELSE 0 END) as total_investment_pmdn,
        -- Additional investment
        SUM(CASE WHEN investment_type = 'PMA' THEN additional_investment ELSE 0 END) as additional_investment_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN additional_investment ELSE 0 END) as additional_investment_pmdn,
        -- TKI
        SUM(CASE WHEN investment_type = 'PMA' THEN tki ELSE 0 END) as total_tki_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN tki ELSE 0 END) as total_tki_pmdn,
        -- TKA
        SUM(CASE WHEN investment_type = 'PMA' THEN tka ELSE 0 END) as total_tka_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN tka ELSE 0 END) as total_tka_pmdn,
        -- Realization investment (planned - actual)
        SUM(CASE WHEN investment_type = 'PMA' THEN (planned_total_investment - total_investment) ELSE 0 END) as realization_investment_pma,
        SUM(CASE WHEN investment_type = 'PMDN' THEN (planned_total_investment - total_investment) ELSE 0 END) as realization_investment_pmdn
    FROM projects
    WHERE upload_id = upload_id_param;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `country_statistics`
--

DROP TABLE IF EXISTS `country_statistics`;
CREATE TABLE `country_statistics` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `project_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `district_statistics`
--

DROP TABLE IF EXISTS `district_statistics`;
CREATE TABLE `district_statistics` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `subdistrict` varchar(100) NOT NULL,
  `projects_pma` int(11) DEFAULT 0,
  `projects_pmdn` int(11) DEFAULT 0,
  `investment_pma` decimal(25,2) DEFAULT 0.00,
  `investment_pmdn` decimal(25,2) DEFAULT 0.00,
  `additional_investment_pma` decimal(25,2) DEFAULT 0.00,
  `additional_investment_pmdn` decimal(25,2) DEFAULT 0.00,
  `tki_pma` int(11) DEFAULT 0,
  `tki_pmdn` int(11) DEFAULT 0,
  `tka_pma` int(11) DEFAULT 0,
  `tka_pmdn` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-12-04-040151', 'App\\Database\\Migrations\\CreateSecurityLogsTable', 'default', 'App', 1764820934, 1),
(2, '2025-12-04-045717', 'App\\Database\\Migrations\\CreateBlockedIpsTable', 'default', 'App', 1764824484, 2),
(3, '2025-12-04-045732', 'App\\Database\\Migrations\\CreateSecurityAccessLogsTable', 'default', 'App', 1764824546, 3),
(4, '2025-12-04-050135', 'App\\Database\\Migrations\\CreateBlockedIpsTable', 'default', 'App', 1764824546, 3),
(5, '2025-12-04-050141', 'App\\Database\\Migrations\\CreateSecurityAccessLogsTable', 'default', 'App', 1764824597, 4),
(6, '2025-12-04-051829', 'App\\Database\\Migrations\\CreateSecurityLogsTable', 'default', 'App', 1764825609, 5),
(7, '2025-12-04-052539', 'App\\Database\\Migrations\\CreateSecurityLogsTable', 'default', 'App', 1764826134, 6),
(8, '2025-12-04-053849', 'App\\Database\\Migrations\\CreateSecurityAlertsTable', 'default', 'App', 1764826752, 7),
(9, '2025-12-04-091231', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1764839676, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `period_statistics`
--

DROP TABLE IF EXISTS `period_statistics`;
CREATE TABLE `period_statistics` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `period` varchar(50) NOT NULL,
  `project_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `report_id` varchar(50) DEFAULT NULL,
  `project_id` varchar(50) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `investment_type` enum('PMA','PMDN') NOT NULL,
  `period_stage` varchar(50) DEFAULT NULL,
  `quarter` enum('Q1','Q2','Q3','Q4') DEFAULT NULL,
  `main_sector` varchar(100) DEFAULT NULL,
  `sector_23` varchar(100) DEFAULT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location_print` varchar(255) DEFAULT NULL,
  `sector_detail` varchar(255) DEFAULT NULL,
  `kbli_description` text DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `subdistrict` varchar(100) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `additional_investment` decimal(25,2) DEFAULT 0.00,
  `total_investment` decimal(25,2) DEFAULT 0.00,
  `planned_total_investment` decimal(25,2) DEFAULT 0.00,
  `fixed_capital_planned` decimal(25,2) DEFAULT 0.00,
  `tki` int(11) DEFAULT 0,
  `tka` int(11) DEFAULT 0,
  `officer_name` varchar(255) DEFAULT NULL,
  `problem_description` text DEFAULT NULL,
  `fixed_capital_explanation` text DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sector_statistics`
--

DROP TABLE IF EXISTS `sector_statistics`;
CREATE TABLE `sector_statistics` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `project_count` int(11) DEFAULT 0,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `security_alerts`
--

DROP TABLE IF EXISTS `security_alerts`;
CREATE TABLE `security_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `alert_type` enum('critical_escalation','brute_force') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `security_logs`
--

DROP TABLE IF EXISTS `security_logs`;
CREATE TABLE `security_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `severity` enum('critical','high','medium','low') NOT NULL DEFAULT 'medium',
  `status` enum('blocked','detected') NOT NULL DEFAULT 'detected',
  `payload` longtext DEFAULT NULL,
  `uri` text DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `security_logs`
--

INSERT INTO `security_logs` (`id`, `type`, `ip_address`, `severity`, `status`, `payload`, `uri`, `method`, `user_agent`, `created_at`) VALUES
(1, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"search\":\"1\' OR \'1\'=\'1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:30:15'),
(2, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"name\":\"john\",\"email\":\"john@example.com\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:31:01'),
(3, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"file\":\"..\\/..\\/etc\\/passwd\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:31:52'),
(4, 'XSS Attack', '::1', 'high', 'blocked', '{\"comment\":\"<img src=x onerror=alert(1)>\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:32:11'),
(5, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"test; ls -la\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:32:39'),
(6, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:33:03'),
(7, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 UNION SELECT * FROM uploads\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 13:33:24'),
(8, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 15:38:28'),
(9, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 15:38:52'),
(10, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 15:39:46'),
(11, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-04 15:43:21'),
(12, 'Command Injection Attempt', '::1', 'critical', 'blocked', '[]', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 15:59:31'),
(13, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"upload_id\":\"31\",\"upload_name\":\"Test\",\"quarter\":\"1\",\"year\":\"2025\",\"usd_value\":\"16653\"}', 'http://localhost:8080/index.php/dashboard/processMetadata', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:00:19'),
(14, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"upload_id\":\"31\",\"upload_name\":\"Test\",\"quarter\":\"1\",\"year\":\"2025\",\"usd_value\":\"16653\"}', 'http://localhost:8080/index.php/dashboard/processMetadata', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:00:29'),
(15, 'Command Injection Attempt', '::1', 'critical', 'blocked', '[]', 'http://localhost:8080/index.php/dashboard/metadata/31', 'GET', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:00:30'),
(16, 'Command Injection Attempt', '::1', 'critical', 'blocked', '[]', 'http://localhost:8080/index.php/', 'GET', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 16:00:32'),
(17, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-05 01:40:32'),
(18, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-05 01:43:29'),
(19, 'SQL Injection Attempt', '::1', 'critical', 'blocked', '{\"id\":\"1 OR 1=1\"}', 'http://localhost:8080/index.php/dashboard/deleteUpload', 'POST', 'curl/8.10.1', '2025-12-05 01:44:34'),
(20, 'Command Injection Attempt', '::1', 'critical', 'blocked', '[]', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-05 14:07:24'),
(21, 'XSS Attack', '::1', 'high', 'blocked', '{\"name\":\"<script>alert(\'XSS\')<\\/script>\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:00:49'),
(22, 'XSS Attack', '::1', 'high', 'blocked', '{\"name\":\"<script>alert(\'XSS\')<\\/script>\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:01:38'),
(23, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:02:36'),
(24, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:02:50'),
(25, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:03:27'),
(26, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:03:51'),
(27, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:04:49'),
(28, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:05:23'),
(29, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:06:20'),
(30, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 17:06:46'),
(31, 'Command Injection Attempt', '::1', 'critical', 'blocked', '[]', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 17:07:22'),
(32, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 20:40:41'),
(33, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"cmd\":\"; rm -rf \\/\"}', 'http://localhost:8080/index.php/dashboard/upload', 'POST', 'curl/8.10.1', '2025-12-07 20:41:43'),
(34, 'Command Injection Attempt', '::1', 'critical', 'blocked', '{\"upload_id\":\"38\"}', 'http://localhost:8080/index.php/dashboard/deleteUpload', 'POST', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-08 08:58:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `upload_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('uploaded','processing','completed','failed') DEFAULT 'uploaded',
  `total_records` int(11) DEFAULT 0,
  `processed_records` int(11) DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `upload_name` varchar(255) DEFAULT NULL,
  `quarter` enum('Q1','Q2','Q3','Q4') DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `usd_value` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `upload_statistics`
--

DROP TABLE IF EXISTS `upload_statistics`;
CREATE TABLE `upload_statistics` (
  `id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `total_projects_pma` int(11) DEFAULT 0,
  `total_projects_pmdn` int(11) DEFAULT 0,
  `total_investment_pma` decimal(25,2) DEFAULT 0.00,
  `total_investment_pmdn` decimal(25,2) DEFAULT 0.00,
  `additional_investment_pma` decimal(25,2) DEFAULT 0.00,
  `additional_investment_pmdn` decimal(25,2) DEFAULT 0.00,
  `total_tki_pma` int(11) DEFAULT 0,
  `total_tki_pmdn` int(11) DEFAULT 0,
  `total_tka_pma` int(11) DEFAULT 0,
  `total_tka_pmdn` int(11) DEFAULT 0,
  `realization_investment_pma` decimal(25,2) DEFAULT 0.00,
  `realization_investment_pmdn` decimal(25,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','user') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'superadmin', 'superadmin@sst.local', '$2y$12$eYVTrylo3/VQK2vJPbsy7eSq9nDDSlz3rCRDE/qyZC6CFAfC.84ta', 'superadmin', 'active', '2025-12-08 08:58:06', '2025-12-04 17:17:17', '2025-12-08 08:58:06', NULL),
(2, 'users', 'users@gmail.com', '$2y$12$mzDUW9JCpBbMA2VXT/7q1.lDeymIvariMRHuBC8zBp5EtpkeS4yfe', 'user', 'active', '2025-12-08 08:56:11', '2025-12-04 17:33:59', '2025-12-08 08:56:11', NULL),
(3, 'admin', 'admin@gmail.com', '$2y$12$qAa9Ok6Qr/sSNmQoVo1DmeTThUoj7LikIbMNOkyyJL7vNoTWLjnFm', 'admin', 'active', '2025-12-08 08:55:52', '2025-12-04 17:34:19', '2025-12-08 08:55:52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `country_statistics`
--
ALTER TABLE `country_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upload_country` (`upload_id`,`country`),
  ADD KEY `idx_upload_id` (`upload_id`);

--
-- Indeks untuk tabel `district_statistics`
--
ALTER TABLE `district_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upload_district` (`upload_id`,`subdistrict`),
  ADD KEY `idx_upload_id` (`upload_id`),
  ADD KEY `idx_subdistrict` (`subdistrict`),
  ADD KEY `idx_district_stats_upload_subdistrict` (`upload_id`,`subdistrict`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `period_statistics`
--
ALTER TABLE `period_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upload_period` (`upload_id`,`period`),
  ADD KEY `idx_upload_id` (`upload_id`);

--
-- Indeks untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_upload_id` (`upload_id`),
  ADD KEY `idx_investment_type` (`investment_type`),
  ADD KEY `idx_subdistrict` (`subdistrict`),
  ADD KEY `idx_province` (`province`),
  ADD KEY `idx_district` (`district`),
  ADD KEY `idx_sector_detail` (`sector_detail`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_period_stage` (`period_stage`),
  ADD KEY `idx_projects_upload_type` (`upload_id`,`investment_type`),
  ADD KEY `idx_projects_subdistrict_type` (`subdistrict`,`investment_type`),
  ADD KEY `idx_projects_sector_type` (`sector_detail`,`investment_type`);

--
-- Indeks untuk tabel `sector_statistics`
--
ALTER TABLE `sector_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upload_sector` (`upload_id`,`sector`),
  ADD KEY `idx_upload_id` (`upload_id`);

--
-- Indeks untuk tabel `security_alerts`
--
ALTER TABLE `security_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `alert_type` (`alert_type`);

--
-- Indeks untuk tabel `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `ip_address` (`ip_address`),
  ADD KEY `severity` (`severity`);

--
-- Indeks untuk tabel `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_quarter_year` (`quarter`,`year`,`status`),
  ADD KEY `idx_upload_date` (`upload_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `upload_statistics`
--
ALTER TABLE `upload_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upload_id` (`upload_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `country_statistics`
--
ALTER TABLE `country_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `district_statistics`
--
ALTER TABLE `district_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `period_statistics`
--
ALTER TABLE `period_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11068;

--
-- AUTO_INCREMENT untuk tabel `sector_statistics`
--
ALTER TABLE `sector_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=858;

--
-- AUTO_INCREMENT untuk tabel `security_alerts`
--
ALTER TABLE `security_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `upload_statistics`
--
ALTER TABLE `upload_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `country_statistics`
--
ALTER TABLE `country_statistics`
  ADD CONSTRAINT `country_statistics_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `district_statistics`
--
ALTER TABLE `district_statistics`
  ADD CONSTRAINT `district_statistics_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `period_statistics`
--
ALTER TABLE `period_statistics`
  ADD CONSTRAINT `period_statistics_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sector_statistics`
--
ALTER TABLE `sector_statistics`
  ADD CONSTRAINT `sector_statistics_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `upload_statistics`
--
ALTER TABLE `upload_statistics`
  ADD CONSTRAINT `upload_statistics_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
