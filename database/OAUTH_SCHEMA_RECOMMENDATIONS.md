# Recomendaciones para Estructura de Tabla Users con OAuth

## Cambios Propuestos

### 1. **Nuevas Columnas**

```sql
- google_id (VARCHAR 255, UNIQUE, NULLABLE)
  └─ Almacena el ID único de Google de cada usuario
  └─ UNIQUE para evitar duplicados
  └─ NULLABLE para compatibilidad con usuarios locales

- microsoft_id (VARCHAR 255, UNIQUE, NULLABLE)
  └─ Almacena el ID único de Microsoft (para futura integración)
  └─ UNIQUE para evitar duplicados
  └─ NULLABLE para compatibilidad

- provider (ENUM: 'local', 'google', 'microsoft')
  └─ Indica cuál es el proveedor de autenticación principal
  └─ DEFAULT: 'local' para mantener compatibilidad hacia atrás
  └─ Útil para mostrar en UI cuál fue el método de login

- profile_picture (VARCHAR 500, NULLABLE)
  └─ Almacena URL de foto de perfil de Google/Microsoft
  └─ Mejora experiencia de usuario con avatares

- updated_at (TIMESTAMP, NULLABLE)
  └─ Tracks de cuándo se actualizó el perfil
  └─ Útil para sincronizar cambios de usuarios OAuth

- password (NULLABLE en lugar de NOT NULL)
  └─ Permite que usuarios OAuth no tengan contraseña
  └─ Usuarios locales siguen con contraseña obligatoria
```

### 2. **Migración de Datos Existentes**

```sql
-- Los usuarios existentes (locales) se mantendrán automáticamente:
- google_id: NULL
- microsoft_id: NULL
- provider: 'local' (DEFAULT)
- profile_picture: NULL
- password: Se mantiene como está
```

### 3. **Índices Optimizados**

```sql
- idx_users_google_id: Búsqueda rápida por google_id
- idx_users_microsoft_id: Búsqueda rápida por microsoft_id
- idx_users_provider: Filtrado de usuarios por proveedor
```

## Nueva Estructura Completa de Tabla

```sql
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  
  -- Autenticación
  `password` VARCHAR(255) NULLABLE,  -- NULL para usuarios OAuth
  `provider` ENUM('local', 'google', 'microsoft') DEFAULT 'local',
  
  -- OAuth IDs
  `google_id` VARCHAR(255) UNIQUE NULL,
  `microsoft_id` VARCHAR(255) UNIQUE NULL,
  
  -- Perfil
  `profile_picture` VARCHAR(500) NULL,
  
  -- Plan y Rol
  `plan` ENUM('free', 'pro', 'premium', '') DEFAULT 'free',
  `rol` ENUM('user', 'admin', '', '') DEFAULT 'user',
  
  -- Timestamps
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Índices
  KEY `idx_email` (`email`),
  KEY `idx_google_id` (`google_id`),
  KEY `idx_microsoft_id` (`microsoft_id`),
  KEY `idx_provider` (`provider`)
);