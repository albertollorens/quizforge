-- =====================================================
-- MIGRATION: Agregar soporte para Google OAuth
-- =====================================================

-- Modificar tabla users para soportar autenticación social
ALTER TABLE `users` 
ADD COLUMN `google_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE NULL,
ADD COLUMN `microsoft_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE NULL,
ADD COLUMN `provider` ENUM('local', 'google', 'microsoft') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
ADD COLUMN `profile_picture` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
MODIFY COLUMN `password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

-- Crear índices para búsquedas rápidas por provider ID
CREATE INDEX idx_users_google_id ON `users` (`google_id`);
CREATE INDEX idx_users_microsoft_id ON `users` (`microsoft_id`);
CREATE INDEX idx_users_provider ON `users` (`provider`);
