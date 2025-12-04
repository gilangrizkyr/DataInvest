-- Migration script to add metadata columns to uploads table
-- Run this script on your existing database

-- Add new columns to uploads table
ALTER TABLE `uploads`
ADD COLUMN `upload_name` VARCHAR(255) DEFAULT NULL AFTER `error_message`,
ADD COLUMN `quarter` ENUM('Q1','Q2','Q3','Q4') DEFAULT NULL AFTER `upload_name`,
ADD COLUMN `year` YEAR DEFAULT NULL AFTER `quarter`,
ADD COLUMN `usd_value` DECIMAL(15,2) DEFAULT NULL AFTER `year`;

-- Update the status enum to include 'uploaded' status
ALTER TABLE `uploads`
MODIFY COLUMN `status` ENUM('uploaded','processing','completed','failed') DEFAULT 'uploaded';

-- Update existing records to have 'completed' status (assuming they are already processed)
UPDATE `uploads` SET `status` = 'completed' WHERE `status` = 'processing';
UPDATE `uploads` SET `status` = 'completed' WHERE `status` = 'failed';

-- Add quarter column to projects table
ALTER TABLE projects ADD COLUMN quarter ENUM('Q1','Q2','Q3','Q4') NULL AFTER period_stage;
