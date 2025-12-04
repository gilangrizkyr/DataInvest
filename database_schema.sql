-- Sistem Statistik Terpadu - Database Schema
-- Aplikasi untuk menganalisis data investasi PMA/PMDN dari file Excel

-- =====================================================
-- TABEL UTAMA
-- =====================================================

-- Tabel untuk menyimpan informasi upload file Excel
CREATE TABLE IF NOT EXISTS `uploads` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) NOT NULL,
    `original_filename` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` BIGINT(20) DEFAULT NULL,
    `uploaded_by` VARCHAR(100) DEFAULT NULL,
    `upload_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('processing','completed','failed') DEFAULT 'processing',
    `total_records` INT(11) DEFAULT 0,
    `processed_records` INT(11) DEFAULT 0,
    `error_message` TEXT,
    PRIMARY KEY (`id`),
    INDEX `idx_upload_date` (`upload_date`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel utama untuk menyimpan data proyek investasi
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `report_id` VARCHAR(50) DEFAULT NULL,
    `project_id` VARCHAR(50) DEFAULT NULL,
    `project_name` VARCHAR(255) DEFAULT NULL,
    `investment_type` ENUM('PMA','PMDN') NOT NULL,
    `period_stage` VARCHAR(50) DEFAULT NULL,
    `quarter` ENUM('Q1','Q2','Q3','Q4') NULL,
    `main_sector` VARCHAR(100) DEFAULT NULL,
    `sector_23` VARCHAR(100) DEFAULT NULL,
    `business_type` VARCHAR(100) DEFAULT NULL,
    `company_name` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `address` TEXT,
    `location_print` VARCHAR(255) DEFAULT NULL,
    `sector_detail` VARCHAR(255) DEFAULT NULL,
    `kbli_description` TEXT,
    `province` VARCHAR(100) DEFAULT NULL,
    `district` VARCHAR(100) DEFAULT NULL,
    `subdistrict` VARCHAR(100) DEFAULT NULL,
    `license_number` VARCHAR(100) DEFAULT NULL,
    `additional_investment` DECIMAL(25,2) DEFAULT 0,
    `total_investment` DECIMAL(25,2) DEFAULT 0,
    `planned_total_investment` DECIMAL(25,2) DEFAULT 0,
    `fixed_capital_planned` DECIMAL(25,2) DEFAULT 0,
    `tki` INT(11) DEFAULT 0,
    `tka` INT(11) DEFAULT 0,
    `officer_name` VARCHAR(255) DEFAULT NULL,
    `problem_description` TEXT,
    `fixed_capital_explanation` TEXT,
    `phone_number` VARCHAR(50) DEFAULT NULL,
    `country` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_upload_id` (`upload_id`),
    INDEX `idx_investment_type` (`investment_type`),
    INDEX `idx_subdistrict` (`subdistrict`),
    INDEX `idx_province` (`province`),
    INDEX `idx_district` (`district`),
    INDEX `idx_sector_detail` (`sector_detail`),
    INDEX `idx_country` (`country`),
    INDEX `idx_period_stage` (`period_stage`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- TABEL STATISTIK (PRE-CALCULATED)
-- =====================================================

-- Tabel statistik keseluruhan per upload
CREATE TABLE IF NOT EXISTS `upload_statistics` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `total_projects_pma` INT(11) DEFAULT 0,
    `total_projects_pmdn` INT(11) DEFAULT 0,
    `total_investment_pma` DECIMAL(25,2) DEFAULT 0,
    `total_investment_pmdn` DECIMAL(25,2) DEFAULT 0,
    `additional_investment_pma` DECIMAL(25,2) DEFAULT 0,
    `additional_investment_pmdn` DECIMAL(25,2) DEFAULT 0,
    `total_tki_pma` INT(11) DEFAULT 0,
    `total_tki_pmdn` INT(11) DEFAULT 0,
    `total_tka_pma` INT(11) DEFAULT 0,
    `total_tka_pmdn` INT(11) DEFAULT 0,
    `realization_investment_pma` DECIMAL(25,2) DEFAULT 0,
    `realization_investment_pmdn` DECIMAL(25,2) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_upload_id` (`upload_id`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel statistik per kecamatan/district
CREATE TABLE IF NOT EXISTS `district_statistics` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `subdistrict` VARCHAR(100) NOT NULL,
    `projects_pma` INT(11) DEFAULT 0,
    `projects_pmdn` INT(11) DEFAULT 0,
    `investment_pma` DECIMAL(25,2) DEFAULT 0,
    `investment_pmdn` DECIMAL(25,2) DEFAULT 0,
    `additional_investment_pma` DECIMAL(25,2) DEFAULT 0,
    `additional_investment_pmdn` DECIMAL(25,2) DEFAULT 0,
    `tki_pma` INT(11) DEFAULT 0,
    `tki_pmdn` INT(11) DEFAULT 0,
    `tka_pma` INT(11) DEFAULT 0,
    `tka_pmdn` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_upload_district` (`upload_id`, `subdistrict`),
    INDEX `idx_upload_id` (`upload_id`),
    INDEX `idx_subdistrict` (`subdistrict`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel statistik per sektor
CREATE TABLE IF NOT EXISTS `sector_statistics` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `sector` VARCHAR(255) NOT NULL,
    `project_count` INT(11) DEFAULT 0,
    `percentage` DECIMAL(5,2) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_upload_sector` (`upload_id`, `sector`),
    INDEX `idx_upload_id` (`upload_id`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel statistik per negara (khusus PMA)
CREATE TABLE IF NOT EXISTS `country_statistics` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `country` VARCHAR(100) NOT NULL,
    `project_count` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_upload_country` (`upload_id`, `country`),
    INDEX `idx_upload_id` (`upload_id`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel statistik per periode/tahap
CREATE TABLE IF NOT EXISTS `period_statistics` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `upload_id` INT(11) NOT NULL,
    `period` VARCHAR(50) NOT NULL,
    `project_count` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_upload_period` (`upload_id`, `period`),
    INDEX `idx_upload_id` (`upload_id`),
    FOREIGN KEY (`upload_id`) REFERENCES `uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- STORED PROCEDURES
-- =====================================================

DELIMITER $$

-- Stored procedure untuk menghitung statistik keseluruhan per upload
CREATE PROCEDURE IF NOT EXISTS `calculate_upload_statistics`(IN upload_id_param INT)
BEGIN
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

-- Stored procedure untuk menghitung statistik per kecamatan
CREATE PROCEDURE IF NOT EXISTS `calculate_district_statistics`(IN upload_id_param INT)
BEGIN
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

-- Stored procedure untuk menghitung statistik per sektor
CREATE PROCEDURE IF NOT EXISTS `calculate_sector_statistics`(IN upload_id_param INT)
BEGIN
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

-- Stored procedure untuk menghitung statistik per negara (PMA only)
CREATE PROCEDURE IF NOT EXISTS `calculate_country_statistics`(IN upload_id_param INT)
BEGIN
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

-- Stored procedure untuk menghitung statistik per periode
CREATE PROCEDURE IF NOT EXISTS `calculate_period_statistics`(IN upload_id_param INT)
BEGIN
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

DELIMITER ;

-- =====================================================
-- DATA SAMPLE (UNTUK TESTING)
-- =====================================================

-- Insert sample upload record
INSERT INTO uploads (filename, original_filename, file_path, status, total_records, processed_records)
VALUES ('sample_data.xlsx', 'data_investasi.xlsx', '/uploads/sample_data.xlsx', 'completed', 100, 100);

-- Note: Data proyek akan di-insert melalui aplikasi saat upload file Excel
-- Statistika akan dihitung otomatis melalui stored procedures

-- =====================================================
-- INDEX OPTIMIZATION
-- =====================================================

-- Tambahan index untuk performa query yang sering digunakan
CREATE INDEX idx_projects_upload_type ON projects(upload_id, investment_type);
CREATE INDEX idx_projects_subdistrict_type ON projects(subdistrict, investment_type);
CREATE INDEX idx_projects_sector_type ON projects(sector_detail, investment_type);
CREATE INDEX idx_district_stats_upload_subdistrict ON district_statistics(upload_id, subdistrict);
