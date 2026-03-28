-- Migration: Add Admin Response Fields to Reports Table
-- This migration adds fields to track admin responses to user reports

ALTER TABLE `reports` 
ADD COLUMN `admin_response` TEXT DEFAULT NULL COMMENT 'Admin response/notes to the report',
ADD COLUMN `admin_response_date` DATETIME DEFAULT NULL COMMENT 'Date when admin responded',
ADD COLUMN `is_responded` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag to indicate if report has been responded to';

-- Index for faster queries
CREATE INDEX idx_user_email ON `reports`(`user_email`);
CREATE INDEX idx_is_responded ON `reports`(`is_responded`);
