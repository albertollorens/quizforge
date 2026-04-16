# Recomendaciones Finales - Estructura de Tabla Users para Google OAuth

## 📊 Comparación: Antes vs Después

### ANTES (Estructura Original)
```sql
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,           -- ⚠️ Obligatoria
  `plan` ENUM('free','pro','premium',''),
  `rol` ENUM('user','admin','',''),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### DESPUÉS (Estructura Mejorada)
```sql
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NULL,               -- ✅ Opcional para OAuth
  `provider` ENUM('local','google','microsoft') DEFAULT 'local',
  `google_id` VARCHAR(255) UNIQUE NULL,       -- ✨ Nuevo
  `microsoft_id` VARCHAR(255) UNIQUE NULL,    -- ✨ Nuevo (futuro)
  `profile_picture` VARCHAR(500) NULL,        -- ✨ Nuevo
  `plan` ENUM('free','pro','premium',''),
  `rol` ENUM('user','admin','',''),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_google_id` (`google_id`),
  KEY `idx_microsoft_id` (`microsoft_id`),
  KEY `idx_provider` (`provider`)
);
```

## 🎯 Justificación de Cada Cambio

### 1. **password VARCHAR(255) NULL**
**Por qué:** Los usuarios que se registran con Google no tienen contraseña local
**Ventaja:** Permite registros OAuth puros sin contraseña
**Riesgo:** Hay que validar en login local que password no sea NULL

### 2. **provider ENUM('local','google','microsoft')**
**Por qué:** Indicador del método principal de autenticación
**Ventajas:**
- UI puede mostrar "Conectado con Google"
- Facilita análisis de uso de OAuth
- Permite migrar usuarios entre proveedores
**Ejemplo en UI:**
```
Usuario registrado por: Google ✓
Vincular cuenta Microsoft: [Botón]
```

### 3. **google_id VARCHAR(255) UNIQUE**
**Por qué:** ID único asignado por Google (no es email)
**Ventajas:**
- Email de Google puede cambiar
- Google ID es inmutable
- Previene duplicados
**Formato:** Número grande (ej: "123456789012345678")

### 4. **microsoft_id VARCHAR(255) UNIQUE**
**Por qué:** Extensibilidad futura para Microsoft OAuth
**Ventaja:** Estructura lista sin cambios posteriores
**Uso futuro:**
```
Usuario con Google: google_id = "xxx", microsoft_id = NULL
Usuario con ambos: google_id = "xxx", microsoft_id = "yyy"
```

### 5. **profile_picture VARCHAR(500) NULL**
**Por qué:** Guardar foto de perfil de Google
**Ventajas:**
- Mostrar avatar en UI
- No depender de Google cada vez
- Mejor rendimiento
**Ejemplo:** `profile_picture = "https://lh3.googleusercontent.com/..."`

### 6. **updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP**
**Por qué:** Track de cambios en perfil
**Ventajas:**
- Auditoría de cambios
- Detectar actualizaciones de Google
- Sincronización de datos

## 🔄 Migración de Datos Existentes

La migración SQL es **100% segura** porque:

```sql
-- Cambio de columna
MODIFY COLUMN `password` VARCHAR(255) NULL
-- Usuarios existentes mantienen su password ✅

-- Nuevas columnas con DEFAULT/NULL
ADD COLUMN `google_id` VARCHAR(255) UNIQUE NULL
-- Usuarios existentes tienen google_id = NULL ✅

-- Enum con valor DEFAULT
ADD COLUMN `provider` ENUM(...) DEFAULT 'local'
-- Usuarios existentes tienen provider = 'local' ✅
```

**Resultado:** Cero pérdida de datos, compatibilidad hacia atrás garantizada

## 💡 Casos de Uso

### Caso 1: Usuario Nuevo con Google
```
- google_id: "123456789"
- microsoft_id: NULL
- provider: "google"
- password: NULL
- profile_picture: "URL_de_google"
```

### Caso 2: Usuario Local + Vincula Google
```
-- Estado inicial:
- google_id: NULL
- microsoft_id: NULL
- provider: "local"
- password: "hash_bcrypt"

-- Después de vincular:</
- google_id: "123456789" ← AGREGADO
- microsoft_id: NULL
- provider: "google" ← ACTUALIZADO
- password: "hash_bcrypt" ← Se mantiene
```

### Caso 3: Usuario con Múltiples Proveedores
```
- google_id: "123456789"
- microsoft_id: "microsoft-999"
- provider: "google" ← Proveedor principal
- password: NULL o valores
```

## 🔒 Consideraciones de Seguridad

### 1. **Email + Google Authentication**
```php
// Email debe ser ÚNICO globalmente
// Un email solo puede tener UN provider principal
// Pero puede vincularse a múltiples OAuth IDs

user@gmail.com:
  - google_id: "xxx" ← Vinculado
  - microsoft_id: "yyy" ← Vinculado
  - provider: "google" ← Principal
```

### 2. **Prevención de Ataques**
```
❌ Un usuario local puede cambiar email a uno de Google
✅ Sistema detecta el email durante registro OAuth
✅ Vincula al usuario existente automáticamente

❌ Alguien registra con google_id clonado
✅ UNIQUE constraint previene duplicados

❌ Token de Google tampoco viene
✅ Método GoogleTokenHelper valida firma
```

### 3. **GDPR Compliance**
```
Si usuario solicita borrar datos:
- profile_picture URL se guarda en caché local
- Se puede borrar cuando se elimina usuario
- Google ID se mantiene solo por integridad referencial
```

## 📈 Escalabilidad Futura

La estructura permite fácilmente:

### Opción 1: Agregar GitHub OAuth
```sql
ALTER TABLE users ADD google_github_id VARCHAR(255) UNIQUE NULL;
```

### Opción 2: Agregar LinkedIn OAuth
```sql
ALTER TABLE users ADD linkedin_id VARCHAR(255) UNIQUE NULL;
```

### Opción 3: Tabla Social Links (más escalable)
```sql
CREATE TABLE social_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  provider VARCHAR(50),
  provider_id VARCHAR(255),
  profile_picture VARCHAR(500),
  connected_at TIMESTAMP,
  UNIQUE(provider, provider_id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);
```

## ✅ Checklist de Validación

- [ ] Schema tiene todas las nuevas columnas
- [ ] `password` es NULL (no NOT NULL)
- [ ] `provider` tiene DEFAULT 'local'
- [ ] `google_id` y `microsoft_id` son UNIQUE
- [ ] Índices creados para búsquedas rápidas
- [ ] Datos existentes intactos después de migración
- [ ] Tests de login local aún funcionan
- [ ] Tests de Google OAuth funcionan

## 📚 Documentación Relacionada

- `database/migrations/001_add_oauth_support.sql` - Script de migración
- `src/Models/User.php` - Métodos para manejar OAuth
- `src/Controllers/LoginController.php` - Endpoints de autenticación
- `src/Helpers/GoogleTokenHelper.php` - Validación de tokens
- `backend/GOOGLE_OAUTH_IMPLEMENTATION_GUIDE.md` - Guía completa

## 📞 Preguntas Frecuentes

**P: ¿Puedo cambiar provider de un usuario?**
A: Sí, actualiza la columna `provider`, pero mantén los IDs OAuth vinculados.

**P: ¿Qué pasa si un usuario tiene múltiples Google IDs?**
A: El constraint UNIQUE previene esto. Solo uno por usuario.

**P: ¿Debo migrar usuarios locales a algún provider?**
A: No. Mantén `provider = 'local'` para usuarios con contraseña.

**P: ¿Puedo eliminar una columna de provider después?**
A: No es recomendable. Mantén la estructura flexible.

**P: ¿Es seguro guardar profile_picture URL?**
A: Sí, es solo una URL. La imagen se sirve desde Google servers.
