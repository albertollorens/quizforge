# CONFIGURACIÓ APP QuizForge

## Entorn
|------------|-----------------|
| **Tipus**  | Local           |
| **SO**     | Windows 10      |
| **Servidor** | WampServer 3.4.0 |
| **Apache** | 2.4.65          |
| **PHP**    | 8.3.28          |
| **MySQL**  | 8.4.7           |

------------------------------------------------------------------------

# 1️⃣ Configurar alias localhost

Editar el fitxer:

`C:\Windows\System32\drivers\etc\hosts`

Afegir:

127.0.0.1 quizforge.local

------------------------------------------------------------------------

# 2️⃣ Configurar VirtualHost en Apache

Editar `httpd-vhosts.conf` perquè apunte a `backend/public`:

```yaml
<VirtualHost \*:80\>
    ServerName quizforge.local 
    DocumentRoot "d:/ruta/al/projecte/backend/public"

    <Directory "d:/ruta/al/projecte/backend/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/quizforge-error.log"
    CustomLog "logs/quizforge-access.log" common
</VirtualHost>
```
> El frontend (Vue + Vite) compilat es serveix des de `backend/public`.

------------------------------------------------------------------------

# 3️⃣ Estructura del projecte

# Estructura visual del projecte `projecte-app`

```text
projecte-app/
│
├── backend/                     # Backend PHP Slim
│   ├── public/                  # Fitxers públics
│   │   ├── index.php            # Entry point Slim
│   │   ├── index.html           # Landing/HTML estàtic
│   │   ├── assets/              # CSS, JS, imatges
│   │   ├── favicon.ico
│   │   └── logo.png
│   │
│   ├── src/                     # Codi PHP principal
│   │   ├── config/              # Configuració
│   │   │   ├── Database.php
│   │   │   └── Settings.php
│   │   ├── Controllers/         # Controladors Slim
│   │   ├── Middleware/          # Middleware Slim
│   │   ├── Models/              # Models
│   │   └── routes/api.php       # Rutes API
│   │
│   ├── vendor/                  # Dependències Composer
│   ├── .env                     # Variables entorn
│   ├── composer.json
│   └── composer.lock
│
├── database/                     # SQL / Backup
│   └── projecte-db.sql
│
├── frontend/                     # Frontend Vue + Vite
│   ├── dist/                     # Build producció
│   ├── node_modules/             # Dependències NPM
│   ├── src/
│   │   ├── services/authservice.js
│   │   ├── router/index.js
│   │   ├── App.vue
│   │   └── main.js
│   ├── index.html
│   ├── package.json
│   └── vite.config.js
│
├── .gitignore
└── README.md
```

------------------------------------------------------------------------

## Aclaracions

-   **Axios** → Comunicació frontend/backend via HTTP REST\
-   **JWT** → Token generat pel backend després del login\
-   **Middleware** → Valida el JWT en rutes protegides\
-   **CORS** → Permet comunicació entre localhost:5173 i localhost:8080 (No és necessari si frontend i backend comparteixen domini)

------------------------------------------------------------------------

# 4️⃣ Rutes API REST

  | Mètode | Ruta | Protegida |
  | :------- | :-------------------------- | :----- |
  | POST     | /api/login                  |   ❌  | 
  | POST     | /api/register               |   ❌  | 
  | GET      | /api/dashboard              |   ✅  | 
  | GET      | /api/quizzes                |   ✅  | 
  | POST     | /api/quizzes                |   ✅  | 
  | PUT      | /api/quizzes/{{id}}         |   ✅  | 
  | DELETE   | /api/quizzes/{{id}}         |   ✅  | 
  | GET      | /quizzes/{{id}}/export/gift |   ✅  | 
  | GET      | /quizzes/{{id}}/export/xml  |   ✅  | 

------------------------------------------------------------------------

# 5️⃣ Configuració .htaccess
```yaml
RewriteEngine On

/#1. Rutes API → Slim
RewriteCond %{REQUEST_URI} ^/api
RewriteRule ^api/(.*)$ index.php [QSA,L]

/#2. Fitxers existents
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

/#3. Vue SPA fallback
RewriteRule . /index.html [L]
```
------------------------------------------------------------------------

# 6️⃣ Esquema d'inici de l'aplicació

Navegador  
   ↓  
`http://quizforge.local`  
   ↓ (Apache)  
`public/index.html`  
   ↓  
Vue (`main.js` + `router`)  
   ↓  
`/`  → redirigeix a `/login`  
`/login` → `Login.vue`


------------------------------------------------------------------------

# 7️⃣ Flux d'execució del login

1.  L'usuari accedeix a http://quizforge.local\
2.  Apache serveix index.html\
3.  Vue inicialitza l'aplicació\
4.  Router redirigeix a /login\
5.  Login.vue envia POST /api/login\
6.  Slim rep la petició\
7.  LoginController valida usuari\
8.  Es genera JWT\
9.  Vue guarda el token i redirigeix a /dashboard

------------------------------------------------------------------------

# Resum

> Entorn local basat en Apache amb DocumentRoot apuntant a `backend/public`, frontend compilat amb Vite i backend Slim exposant API REST protegida amb JWT.
