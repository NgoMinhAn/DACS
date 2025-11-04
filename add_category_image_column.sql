-- Add image column to specialties table if it doesn't exist
ALTER TABLE specialties 
ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL AFTER icon;

