# QuizForge -- Documentació Acadèmica

## Projecte Final de Cicle (PFC)

Cicle Superior de Desenvolupament d'Aplicacions Web (DAW)

------------------------------------------------------------------------

# 1. Introducció

QuizForge és una aplicació web desenvolupada com a Projecte Final de
Cicle (PFC). L'objectiu principal és dissenyar i implementar una
plataforma de gestió de qüestionaris mitjançant una arquitectura moderna
basada en SPA (Single Page Application) i API REST.

El projecte integra tecnologies actuals tant en frontend com en backend,
així com un entorn de desplegament en el núvol mitjançant Azure.

------------------------------------------------------------------------

# 2. Objectius del Projecte

## 2.1 Objectiu General

Desenvolupar una aplicació web completa amb separació frontend/backend i
autenticació segura.

## 2.2 Objectius Específics

-   Implementar una SPA amb Vue 3.
-   Desenvolupar una API REST amb Slim (PHP).
-   Gestionar autenticació mitjançant JWT.
-   Implementar accés segur a MySQL amb PDO.
-   Configurar desplegament automàtic amb GitHub Actions.
-   Adaptar el projecte a entorn Azure App Service (Linux + Nginx).

------------------------------------------------------------------------

# 3. Tecnologies Utilitzades

## Frontend

-   Vue 3
-   Vite
-   Axios
-   Bootstrap

## Backend

-   PHP 8.4
-   Slim Framework 4
-   PDO
-   Firebase JWT

## Base de Dades

-   MySQL 8.4

## Infraestructura

-   Apache (entorn local)
-   Azure App Service (Linux + Nginx)
-   Azure Database for MySQL
-   GitHub Actions (CI/CD)

------------------------------------------------------------------------

# 4. Arquitectura del Sistema

L'aplicació segueix una arquitectura client-servidor amb separació clara
de responsabilitats:

Frontend (SPA) → Backend (API REST) → Base de Dades

## 4.1 Frontend

-   Gestiona la interfície i la navegació mitjançant Vue Router.
-   Emmagatzema el JWT en localStorage.
-   Realitza peticions HTTP REST amb Axios.

## 4.2 Backend

-   Punt d'entrada: backend/public/index.php
-   Gestió de rutes: src/routes/api.php
-   Controladors: src/Controllers
-   Models: src/Models
-   Middleware JWT per protegir rutes

## 4.3 Persistència

-   Connexió mitjançant PDO
-   Sentències preparades
-   Connexió SSL obligatòria en Azure

------------------------------------------------------------------------

# 5. Configuració en Entorn Local

## 5.1 Entorn

-   Windows 10
-   WampServer
-   Apache 2.4
-   PHP 8.3
-   MySQL 8.4

## 5.2 VirtualHost

DocumentRoot apuntant a:

backend/public

## 5.3 Redirecció Apache (.htaccess)

-   /api → index.php (Slim)
-   Fitxers existents → servits directament
-   Altres rutes → index.html (SPA fallback)

## 5.4 Base de Dades Local

Configuració en .env: - DB_HOST=127.0.0.1 - DB_NAME=quizforge -
DB_USER=root - DB_PASS=

------------------------------------------------------------------------

# 6. Configuració en Azure

## 6.1 Infraestructura

-   Azure App Service (Linux)
-   PHP 8.4
-   Nginx
-   Azure Database for MySQL

## 6.2 Document Root

Configuració Nginx personalitzada per establir:

/home/site/wwwroot/backend/public

com a directori arrel.

## 6.3 Connexió Segura MySQL

Azure requereix SSL obligatori.

En PDO:

-   PDO::MYSQL_ATTR_SSL_CA
-   PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT

Sense SSL apareix l'error: SQLSTATE\[HY000\] \[3159\] Connections using
insecure transport are prohibited

## 6.4 Variables d'Entorn

Configurades en Azure App Service: - DB_HOST - DB_NAME - DB_USER -
DB_PASS - JWT_SECRET

------------------------------------------------------------------------

# 7. Flux d'Execució del Login

1.  L'usuari accedeix a l'aplicació.
2.  Vue Router redirigeix a /login.
3.  Login.vue envia POST /api/login.
4.  Slim rep la petició.
5.  LoginController valida credencials.
6.  Es genera JWT.
7.  El frontend guarda el token.
8.  Redirecció a /dashboard.

------------------------------------------------------------------------

# 8. Seguretat

-   password_hash() i password_verify()
-   Sentències preparades PDO
-   Middleware JWT
-   Connexió SSL en producció
-   Separació d'entorns (local vs producció)

------------------------------------------------------------------------

# 9. Integració Contínua (CI/CD)

Pipeline GitHub Actions:

1.  Instal·lació dependències backend.
2.  Build frontend.
3.  Creació artefacte.
4.  Deploy automàtic a Azure.

------------------------------------------------------------------------

# 10. Conclusions

QuizForge demostra la integració completa d'una arquitectura web moderna
amb separació de capes, autenticació segura, desplegament continu i
adaptació a entorn cloud.

El projecte reflecteix competències en: - Desenvolupament frontend SPA -
Desenvolupament backend REST - Seguretat web - DevOps bàsic -
Configuració de servidor en entorn Linux

------------------------------------------------------------------------

# Annex A -- Estructura del Projecte

projecte-app/ │ ├── backend/ ├── frontend/ ├── database/ └──
.github/workflows/
