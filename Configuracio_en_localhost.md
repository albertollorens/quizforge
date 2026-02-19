# CONFIGURACIÓ APP QuizForge

## Entorn

-   **Tipus:** Local\
-   **SO:** Windows 10\
-   **Servidor:** WampServer 3.4.0\
-   **Apache:** 2.4.65\
-   **PHP:** 8.3.28\
-   **MySQL:** 8.4.7

------------------------------------------------------------------------

# 1️⃣ Configurar alias localhost

Editar el fitxer:

C:`\Windows`{=tex}`\System32`{=tex}`\drivers`{=tex}`\etc`{=tex}`\hosts`{=tex}

Afegir:

127.0.0.1 quizforge.local

------------------------------------------------------------------------

# 2️⃣ Configurar VirtualHost en Apache

Editar `httpd-vhosts.conf` perquè apunte a `backend/public`:

\<VirtualHost \*:80\> ServerName quizforge.local DocumentRoot
"d:/ruta/al/projecte/backend/public"

    <Directory "d:/ruta/al/projecte/backend/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/quizforge-error.log"
    CustomLog "logs/quizforge-access.log" common

`</VirtualHost>`{=html}

> El frontend (Vue + Vite) compilat es serveix des de `backend/public`.

------------------------------------------------------------------------

# 3️⃣ Estructura del projecte

projecte-app/ │ ├── backend/ │ ├── public/ │ ├── src/ │ ├── vendor/ │
├── .env │ ├── composer.json │ └── composer.lock │ ├── database/ │ └──
projecte-db.sql │ ├── frontend/ │ ├── dist/ │ ├── src/ │ ├──
package.json │ └── vite.config.js │ ├── .gitignore └── README.md

------------------------------------------------------------------------

## Aclaracions

-   **Axios** → Comunicació frontend/backend via HTTP REST\
-   **JWT** → Token generat pel backend després del login\
-   **Middleware** → Valida el JWT en rutes protegides\
-   **CORS** → No és necessari si frontend i backend comparteixen domini

------------------------------------------------------------------------

# 4️⃣ Rutes API REST

  Mètode   Ruta                          Protegida
  -------- ----------------------------- -----------
  POST     /api/login                    ❌
  POST     /api/register                 ❌
  GET      /api/dashboard                ✅
  GET      /api/quizzes                  ✅
  POST     /api/quizzes                  ✅
  PUT      /api/quizzes/{{id}}           ✅
  DELETE   /api/quizzes/{{id}}           ✅
  GET      /quizzes/{{id}}/export/gift   ✅
  GET      /quizzes/{{id}}/export/xml    ✅

------------------------------------------------------------------------

# 5️⃣ Configuració .htaccess

RewriteEngine On

# API → Slim

RewriteCond %{REQUEST_URI} \^/api RewriteRule \^api/(.\*)\$ index.php
\[QSA,L\]

# Fitxers existents

RewriteCond %{REQUEST_FILENAME} -f \[OR\] RewriteCond
%{REQUEST_FILENAME} -d RewriteRule \^ - \[L\]

# Vue SPA fallback

RewriteRule . /index.html \[L\]

------------------------------------------------------------------------

# 6️⃣ Flux d'execució del login

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

Entorn local basat en Apache amb DocumentRoot apuntant a
`backend/public`, frontend compilat amb Vite i backend Slim exposant API
REST protegida amb JWT.
