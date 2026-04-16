# Flujos de Autenticación - Google OAuth en QuizForge

## 🔄 Flujo 1: Nuevo Usuario con Google

```
┌─────────────┐                    ┌──────────────┐                  ┌────────────┐
│  Frontend   │                    │   Backend    │                  │    BD      │
│  (Vue 3)    │                    │  (Slim 4)    │                  │  (MySQL)   │
└─────────────┘                    └──────────────┘                  └────────────┘
      │                                  │                                 │
      │ (1) User clicks "Google"        │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (2) Google popup              │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (3) Select account             │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (4) Google returns JWT        │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (5) POST /api/register/google  │                                 │
      │     + google_token             │ (6) Decode token               │
      │                                ├────────────────────────────→   │
      │                                │                                │
      │                                │ (7) Check if email exists     │
      │                                ├────────────────→ NOT FOUND     │
      │                                │                                │
      │                                │ (8) Create user               │
      │                                │     google_id, provider='google'
      │                                ├────────────────────────────→  Create!
      │                                │                                │
      │                                │ (9) Get user data             │
      │                                ├────────────────────────────→  SELECT
      │                                │                    data ←──────┤
      │                                │                                │
      │                                │ (10) Generate JWT             │
      │                                │      (QuizForge)              │
      │                                │                                │
      │ (11) Return JWT + user info   │                                │
      │← ────────────────────────────┤                                 │
      │                                │                                │
      │ (12) Save JWT in localStorage │                                │
      │      Redirect to /dashboard   │                                │
      │                                │                                │
```

**Resultado:**
```
✅ Usuario creado en BD
✅ JWT guardado en localStorage
✅ Usuario autenticado para toda la sesión
```

---

## 🔄 Flujo 2: Usuario Existente + Vincular Google

```
┌─────────────┐                    ┌──────────────┐                  ┌────────────┐
│  Frontend   │                    │   Backend    │                  │    BD      │
│  (Vue 3)    │                    │  (Slim 4)    │                  │  (MySQL)   │
└─────────────┘                    └──────────────┘                  └────────────┘
      │                                  │                                 │
      │ ESCENARIO: Usuario registrado   │                                 │
      │ localmente con abc@gmail.com    │                                 │
      │                                  │                                 │
      │ (1) Click "Google Sign In"      │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (2) Google OAuth flow         │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (3) Same Google account       │                                 │
      │     (abc@gmail.com)           │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (4) POST /api/login/google    │                                 │
      │     + google_token            │ (5) Decode token              │
      │                                ├────────────────────────────→   │
      │                                │ (email = abc@gmail.com)        │
      │                                │                                │
      │                                │ (6) findByGoogleId()          │
      │                                ├────────────────→ NOT FOUND     │
      │                                │                                │
      │                                │ (7) findByEmail()             │
      │                                ├────────────────────→ EXISTS! ◄─┤
      │                                │ (user_id = 1)    (local user) │
      │                                │                                │
      │                                │ (8) updateWithGoogle()        │
      │                                │     user_id=1, google_id=xxx  │
      │                                ├────────────────────────────→  UPDATE!
      │                                │                                │
      │                                │ (9) Get complete user data   │
      │                                ├────────────────────────────→  SELECT
      │                                │                    data ←──────┤
      │                                │                                │
      │                                │ (10) Generate JWT             │
      │                                │      (QuizForge)              │
      │                                │                                │
      │ (11) Return JWT + user        │                                │
      │← ────────────────────────────┤                                 │
      │                                │                                 │
      │ (12) Save JWT & Login!        │                                │
      │                                │                                │
```

**Resultado:**
```
✅ Usuario existente vinculado a Google
✅ provider cambia de 'local' a 'google'
✅ Próximos logins pueden usar Google o local
✅ google_id guardado para futuros logins
```

---

## 🔄 Flujo 3: Usuario Existente Login (después de vincular)

```
┌─────────────┐                    ┌──────────────┐                  ┌────────────┐
│  Frontend   │                    │   Backend    │                  │    BD      │
│  (Vue 3)    │                    │  (Slim 4)    │                  │  (MySQL)   │
└─────────────┘                    └──────────────┘                  └────────────┘
      │                                  │                                 │
      │ Usuario ya vinculado con Google │                                 │
      │ (google_id, provider='google')  │                                 │
      │                                  │                                 │
      │ (1) Click "Google Sign In"      │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (2) Google OAuth complete     │                                 │
      ├─────────────────────────────→   │                                 │
      │                                │                                 │
      │ (3) POST /api/login/google    │                                 │
      │     + google_token            │ (4) Decode token              │
      │                                ├────────────────────────────→   │
      │                                │                                │
      │                                │ (5) findByGoogleId(xxx)       │
      │                                ├────────────────────→ FOUND! ◄──┤
      │                                │ (usuario_id=1)                │
      │                                │                                │
      │                                │ (6) Get user data             │
      │                                ├────────────────────────────→  SELECT
      │                                │                    data ←──────┤
      │                                │                                │
      │                                │ (7) Generate JWT              │
      │                                │      (QuizForge)              │
      │                                │                                │
      │ (8) Return JWT                │                                │
      │← ────────────────────────────┤                                 │
      │                                │                                 │
      │ (9) Authenticated!            │                                │
      │     Go to /dashboard          │                                │
      │                                │                                 │
```

**Resultado:**
```
✅ Login rápido para usuarios vinculados
✅ No necesita contraseña si usa Google
✅ Siguiente intento: recordará proveedor
```

---

## 🔍 Proceso de Decodificación de Token Google

```
┌──────────────────────────────────────────────────────────────┐
│  Token JWT de Google (formato: header.payload.signature)    │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 1: Separar en 3 partes                                │
│  • header (algoritmo, tipo)                                  │
│  • payload (datos del usuario)                               │
│  • signature (firma)                                         │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 2: Decodificar header (Base64 URL-decoded)            │
│  Result: { "alg": "RS256", "kid": "xxxxx" }                │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 3: Obtener certificado público de Google              │
│  • Usar kid (Key ID) del header                              │
│  • Descargar certificados de                                 │
│    https://www.googleapis.com/oauth2/v3/certs              │
│  • Cachear por 1 hora                                        │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 4: Verificar firma (RS256)                            │
│  • Usar certificado público                                  │
│  • Verificar que token no fue alterado                       │
│  • ✅ Válido: continuar                                      │
│  • ❌ Inválido: rechazar                                     │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 5: Decodificar payload                                │
│  Result:                                                      │
│  {                                                            │
│    "sub": "123456789",          ← Google User ID            │
│    "email": "user@gmail.com",   ← Email                     │
│    "name": "User Name",         ← Nombre                    │
│    "picture": "https://...",    ← Foto                      │
│    "aud": "client_id.apps...",  ← Client ID                 │
│    "iss": "accounts.google.com" ← Emisor                    │
│  }                                                            │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  PASO 6: Validaciones adicionales                           │
│  • ✅ iss = "accounts.google.com"                            │
│  • ✅ aud = nuestro client_id                                │
│  • ✅ exp > ahora (no expirado)                              │
│  • ✅ email + sub no son vacíos                              │
└──────────────────────────────────────────────────────────────┘
                            │
                            ▼
                  ✅ Token válido y seguro
```

---

## 🔒 Matriz de Decisiones del Backend

```
┌─────────────────────────────────────────────────────────────┐
│  ¿Existe usuario con google_id? ────┐                      │
│                                     │                       │
│                                   SI │ NO                   │
│                                     │                       │
│                     ┌─ RETURN user ◄┘  │                   │
│                     │ (login rápido)   │                   │
│                     │                   │                   │
│               ┌─────┘                   │                   │
│               │                    ┌─ ¿Existe usuario con email?
│               │                    │                       │
│               │              SI    │ NO                    │
│               │              │      │                      │
│               │    ┌─────────┘      │                      │
│               │    │         ┌──────┘                      │
│               │    │         │                             │
│         CASE: NEW    UPDATE     CREATE                    │
│         ────────    ──────────   ──────                   │
│         • Create    • Vincular   • Usuario               │
│         • With Google  google_id nuevo                   │
│         • Return JWT   • Return JWT   • Return JWT       │
│                                                           │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Estructura de Datos Compartidos

```
┌──────────────────────────────┐
│   Google JWT Payload         │
├──────────────────────────────┤
│ sub: "123456789"             │  (Google User ID)
│ email: "user@gmail.com"      │  (Email)
│ name: "Usuario"              │  (Nombre completo)
│ picture: "url"               │  (Foto de perfil)
│ email_verified: true         │  (Email verificado)
│ aud: "client_id..."          │  (Audience/App ID)
│ iss: "accounts.google.com"   │  (Emisor)
│ exp: 1234567890              │  (Expiración)
└──────────────────────────────┘
           │
           │ MAPEAR A
           │
           ▼
┌──────────────────────────────┐
│   Usuario en QuizForge       │
├──────────────────────────────┤
│ id: auto_increment           │
│ name: "Usuario"              │ ◄── from name
│ email: "user@gmail.com"      │ ◄── from email
│ google_id: "123456789"       │ ◄── from sub
│ profile_picture: "url"       │ ◄── from picture
│ provider: "google"           │
│ password: NULL               │
│ plan: "free"                 │
│ rol: "user"                  │
└──────────────────────────────┘
           │
           │ GENERAR
           │
           ▼
┌──────────────────────────────┐
│  QuizForge JWT Payload       │
├──────────────────────────────┤
│ sub: 1                       │ (User ID en BD)
│ email: "user@gmail.com"      │
│ username: "Usuario"          │
│ rol: "user"                  │
│ plan: "free"                 │
│ provider: "google"           │
│ quizzes: 0                   │
│ exp: ahora + 3600            │ (1 hora)
│ iss: "quizforge.local"       │
└──────────────────────────────┘
```

---

## ⚡ Tabla de Comparación: Local vs OAuth

```
                  │   Local Login   │  Google Login   │  Usuario Resultado
──────────────────┼─────────────────┼─────────────────┼──────────────────
Requiere password │      ✅         │      ❌         │  Optional
Email verificado  │      ❌         │      ✅         │  ✅ confirmado
Foto de perfil    │      ❌         │      ✅         │  ✅ de Google
Google ID         │      ❌         │      ✅         │  ✅ almacenado
Provider          │   'local'       │   'google'      │  'google'
Vinculación       │      ❌         │      ✅         │  Posible
Cambiar contraseña│      ✅         │      ❌         │  No aplica
2FA               │   Manualmente   │   Google maneja │  Segura
```

---

## 📈 Escalabilidad: Agregar Microsoft OAuth

```
Estructura actual: Google ID + Microsoft ID (NULLABLE)

Para agregar Microsoft:
1. Ya existe la columna microsoft_id
2. Agregar método createWithMicrosoft() en User.php ✅
3. Agregar método loginWithMicrosoft() en Controller ✅
4. Agregar ruta POST /api/login/microsoft ✅
5. Agregar ruta POST /api/register/microsoft ✅
6. Agregar botón en Frontend ✅

Cambios mínimos = Arquitectura escalable
```

---

**Toda la implementación sigue arquitectura limpia, principios SOLID y best practices de seguridad.**
