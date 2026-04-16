# Guía de Implementación - Google OAuth Backend

## 📋 Resumen de Cambios Implementados

Se ha implementado soporte completo para autenticación con Google en el backend. Los cambios incluyen:

### 1. **Base de Datos (SQL Migration)**
Archivo: `database/migrations/001_add_oauth_support.sql`

**Cambios implementados:**
```sql
- google_id (VARCHAR 255, UNIQUE, NULL)
- microsoft_id (VARCHAR 255, UNIQUE, NULL)
- provider (ENUM: 'local', 'google', 'microsoft')
- profile_picture (VARCHAR 500, NULL)
- updated_at (TIMESTAMP)
- password (NULL - opcional para usuarios OAuth)
```

**Cómo aplicar:**
```bash
# Conectar a PhpMyAdmin o usar CLI:
mysql -u root -p quizforge < database/migrations/001_add_oauth_support.sql
```

### 2. **Modelo User (src/Models/User.php)**

**Métodos añadidos:**
- `findByGoogleId(string $googleId)` - Buscar usuario por ID de Google
- `findByMicrosoftId(string $microsoftId)` - Buscar usuario por ID de Microsoft
- `findById(int $id)` - Buscar usuario por ID
- `createWithGoogle(...)` - Crear usuario nuevo con Google
- `createWithMicrosoft(...)` - Crear usuario nuevo con Microsoft
- `updateWithGoogle(...)` - Vincular cuenta Google a usuario existente
- `updateWithMicrosoft(...)` - Vincular cuenta Microsoft a usuario existente
- `listAll()` - Listar todos los usuarios
- `delete(int $id)` - Eliminar usuario

### 3. **Controller de Login (src/Controllers/LoginController.php)**

**Métodos añadidos:**
- `loginWithGoogle(Request $request, Response $response)` - POST /api/login/google
- `registerWithGoogle(Request $request, Response $response)` - POST /api/register/google

**Flujo de autenticación con Google:**

#### Login (`POST /api/login/google`)
```
1. Recibe JWT token de Google
2. Decodifica y valida el token
3. Busca usuario por google_id
4. Si no existe, busca por email
5. Si existe por email, vincula Google ID
6. Si no existe, crea nuevo usuario
7. Retorna JWT con datos del usuario
```

#### Registro (`POST /api/register/google`)
```
1. Recibe JWT token de Google
2. Decodifica y valida el token
3. Verifica que email no esté registrado
4. Si existe sin google_id, vincula
5. Si no existe, crea nuevo usuario
6. Retorna JWT con datos del usuario
```

### 4. **Helper de Google Tokens (src/Helpers/GoogleTokenHelper.php)**

**Utilidades para manejar tokens de Google:**
- `verifyAndDecode(token, verifySignature)` - Decodifica y verifica firma del token
- `decodeWithoutVerification(token)` - Decodifica sin verificar firma (DEBUG)
- Caché de certificados públicos de Google con validez de 1 hora

**Características de seguridad:**
- Verifica firma del JWT contra certificados públicos de Google
- Cache local de certificados para mejor rendimiento
- Soporte para modo desarrollo (sin verificación) y producción

## 📝 Estructura de Peticiones

### Login con Google

```bash
POST /api/login/google
Content-Type: application/json

{
  "token": "JWT_TOKEN_DE_GOOGLE"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Autenticación con Google exitosa",
  "token": "JWT_TOKEN_DE_QUIZFORGE",
  "user": {
    "id": 1,
    "email": "usuario@gmail.com",
    "name": "Usuario Google",
    "provider": "google"
  }
}
```

### Registro con Google

```bash
POST /api/register/google
Content-Type: application/json

{
  "token": "JWT_TOKEN_DE_GOOGLE"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Registro con Google exitoso",
  "token": "JWT_TOKEN_DE_QUIZFORGE",
  "user": {
    "id": 2,
    "email": "nuevo@gmail.com",
    "name": "Nuevo Usuario",
    "provider": "google"
  }
}
```

## ⚙️ Configuración Necesaria

### Variables de Entorno

```bash
# En .env o .php file:
APP_ENV=development  # development o production

# Para producción, activar verificación de firma:
# - Si APP_ENV=production, se verifica la firma del token
# - Si APP_ENV=development, se acepta sin verificar (para testing)
```

### Configuración de Frontend

El frontend ya está configurado para:
1. Cargar script de Google (gsi/client)
2. Obtener JWT token de Google
3. Enviar token al endpoint `/api/login/google` o `/api/register/google`
4. Guardar JWT de QuizForge retornado

## 🔒 Seguridad

### En Desarrollo
- Los tokens se aceptan sin verificar firma (para testing)
- Use esta fase para validar la integración

### En Producción
- Se verifica la firma del token contra certificados públicos de Google
- Se cachea los certificados por 1 hora
- Se valida email, google_id y otros datos obligatorios

### Recomendaciones
1. Usar HTTPS siempre en producción
2. Validar que el iss (issuer) sea `accounts.google.com`
3. Validar que aud (audience) sea tu client_id de Google
4. Guardar profile_picture solo si viene de Google
5. Logs de intentos fallidos para debugging

## 📊 Flujo Completo de Autenticación

```
Frontend (Vue 3)          Backend (Slim 4)          Google OAuth
   |                          |                          |
   |---(1) Click Google------->|                          |
   |<--Script Google-----------|                          |
   |---(2) User Login-------->Google OAuth Server        |
   |<--JWT Token Google--------                          |
   |---(3) POST /login/google + token--------->|          |
   |         [decodeGoogleToken]               |          |
   |         [findByGoogleId]                  |          |
   |         [createWithGoogle]                |          |
   |         [Generate JWT]                   |          |
   |<--JWT QuizForge-----(4)---|                         |
   |                      [Save to localStorage]         |
   |---(5) Go /dashboard with JWT------------->|         |
   |         [AuthMiddleware checks JWT]      |         |
   |<--Dashboard content----------|              |
```

## 🧪 Pruebas para Validar

### Test 1: Nuevo Usuario con Google
```bash
1. Ejecutar frontend en localhost:5173
2. Ir a /signup
3. Hacer click en "Google"
4. Seleccionar cuenta Google
5. Verificar que:
   - Se crea usuario en BD
   - Se guarda JWT en localStorage
   - Se redirige a /dashboard
   - Se muestra nombre del usuario
```

### Test 2: Usuario Existente (email) + Google
```bash
1. Crear usuario con email: test@gmail.com y contraseña local
2. Ir a /signin
3. Hacer click en "Google" con la misma cuenta
4. Verificar que:
   - Se vincula el google_id a usuario existente
   - Se carga el usuario existente
   - Se retorna JWT
```

### Test 3: Vincular cuenta local con Google
```bash
1. Usuario registrado localmente con email abc@gmail.com
2. Intenta login con Google desde otra cuenta (diferente email)
3. Luego intenta con la misma cuenta de Gmail
4. Verificar que se vincula correctamente
```

## 📚 Archivos Modificados/Creados

```
backend/
├── src/
│   ├── Controllers/
│   │   └── LoginController.php ✏️ (agregados loginWithGoogle, registerWithGoogle)
│   ├── Models/
│   │   └── User.php ✏️ (agregados métodos OAuth)
│   └── Helpers/
│       └── GoogleTokenHelper.php ✨ (nuevo)
├── database/
│   ├── migrations/
│   │   └── 001_add_oauth_support.sql ✨ (nuevo)
│   └── OAUTH_SCHEMA_RECOMMENDATIONS.md ✨ (nuevo)
└── src/routes/
    └── api.php (ya tiene las rutas definidas)
```

## 🐛 Debugging

### Habilitar logs de Google Auth
```php
// En LoginController.php, descomentar:
error_log('Google Auth Debug: ' . json_encode($decodedToken));
```

### Ver logs
```bash
# Logs PHP están generalmente en:
/var/log/php-fpm.log  # Si usas PHP-FPM
/var/log/apache2/error.log  # Si usas Apache
```

### Verificar token manualmente
```bash
# Decodificar token sin verificar firma en desarrollo
# Usar GoogleTokenHelper::decodeWithoutVerification($token)
```

## ✅ Checklist de Implementación

- [ ] Ejecutar migración SQL del archivo `001_add_oauth_support.sql`
- [ ] Verificar que las nuevas columnas existan en tabla `users`
- [ ] Probar endpoint `/api/login/google` con Postman/Insomnia
- [ ] Probar endpoint `/api/register/google` con Postman/Insomnia
- [ ] Probar flujo completo desde frontend Vue 3
- [ ] Verificar que JWT se retorna correctamente
- [ ] Verificar que usuario se crea en BD
- [ ] Probar vincular cuenta existente con Google
- [ ] En producción, cambiar APP_ENV a 'production' para verificación de firma

## 📞 Soporte

Si encuentras errores:
1. Verificar logs del backend
2. Usar herramientas de debugging JWT (jwt.io)
3. Validar que el client_id de Google coincida en frontend y BD
4. Verificar permisos de BD para nuevas columnas
