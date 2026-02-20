# Configuració App QuizForge (Azure)

| Clau       | Valor                                                     |
|------------|-----------------------------------------------------------|
| **Entorn** | Azure App Service                                         |
| **SO**     | Linux                                                     |
| **Servidor** | Nginx (configurar document root en fitxer de configuració `default`) |
| **PHP**    | 8.4                                                       |
| **MySQL**  | 8.4.7                                                     |

---

## 1. Crear App Service + Base de dades

**Grup de recursos: quizeforgegroup**
---------------------------------

| Clau                  | Valor                                          |
|-----------------------|------------------------------------------------|
| **APP**               | quizforge-app                                  |
| **Zona**              | Spain Central                                  |
| **Subscripció**       | Azure for Students                             |
| **S.O.**              | Linux                                          |
| **Server**            | quizforge-server                               |
| **BD**                | quizforge-db                                   |
| **Punto de conexión** | quizforge-server.mysql.database.azure.com      |


---

## 2. Configurar deploy de `quizforge-app`

**Centre d'implementació => Configuració**
---------------------------------------

| Clau            | Valor                                         |
|-----------------|-----------------------------------------------|
| **Origen**      | GitHub -> alblloboi (connectar amb compte d'usuari) |
| **Organització**| albertollorens                                |
| **Repositori**  | quizforge                                     |
| **Rama**        | main                                          |

# Compilació:

| Clau          | Valor                  |
|---------------|------------------------|
| **Proveïdor** | Acciones de GitHub     |
| **Pila**      | PHP                    |
| **Versió**    | 8.4                    |

---

## 3. Workflow (pipeline) de GitHub Actions

**Fitxer:** `.github/workflows/main_quizforge-app.yml`

```yaml
name: Build and deploy PHP app to Azure Web App - quizforge-app

on:
  push:
    branches:
      - main
	paths:
      - 'backend/**'
      - 'frontend/**'
      - '.github/workflows/**'
      - 'composer.json'
      - 'composer.lock'
      - 'package.json'
      - 'vite.config.js'
      - '.env'
  workflow_dispatch:

jobs:
  build:
    runs-on: ubuntu-latest
    permissions:
      contents: read

    steps:
      - uses: actions/checkout@v4
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with: php-version: '8.4'
      - name: Install backend dependencies
        working-directory: ./backend
        run: |
          composer install --no-dev --optimize-autoloader
      - name: Prepare deployment package
        run: |
          rm -rf deploy
          mkdir deploy
          cp -r backend deploy/
      - name: Create deployment zip
        run: |
          cd deploy
          zip -r ../release.zip .
      - name: Upload artifact
        uses: actions/upload-artifact@v4
        with:
          name: release
          path: release.zip

  deploy:
    runs-on: ubuntu-latest
    needs: build
    permissions:
      id-token: write
      contents: read

    steps:
      - name: Download artifact
        uses: actions/download-artifact@v4
        with:
          name: release
      - name: Login to Azure
        uses: azure/login@v2
        with:
          client-id: ${{ secrets.AZUREAPPSERVICE_CLIENTID_81D8FB210EED4A7F8BCA025B99BEF0D3 }}
          tenant-id: ${{ secrets.AZUREAPPSERVICE_TENANTID_1CCE4FABA6724660BA3FF7775A65E71C }}
          subscription-id: ${{ secrets.AZUREAPPSERVICE_SUBSCRIPTIONID_9BEB573D34B14D4BBE028AE5C7CBF505 }}
      - name: 'Deploy to Azure Web App'
        uses: azure/webapps-deploy@v3
        id: deploy-to-webapp
        with:
          app-name: 'quizforge-app'
          package: release.zip
          clean: false
```

# Explicació ràpida:

1. Build frontend: compila Vue amb Vite → genera `dist/`.
2. Preparar deploy:
   - `deploy/backend/` → backend complet amb Slim, `vendor/`, `src/` i `public/`.
   - `deploy/public/` → build de Vue.
3. Upload/download artifact: permet separar build i deploy en jobs diferents.
4. Deploy: Azure Web App rep només la carpeta preparada (`deploy/`).
5. Secrets necessaris a GitHub:
   - `AZUREAPPSERVICE_CLIENTID`
   - `AZUREAPPSERVICE_TENANTID`
   - `AZUREAPPSERVICE_SUBSCRIPTIONID`


--- 

## 4. Configurar .gitignore ##
```yaml
# Node
node_modules/
frontend/node_modules/

# Vite cache
frontend/dist/

# Composer
vendor/

# Entorn
.env

# Logs
*.log

# OS
.DS_Store
Thumbs.db
```

## 5. Configurar Nginx document root ##
**Fitxer de configuració:** /home/site/wwwroot/default
```yaml
server {
    listen 8080;
    root  /home/site/wwwroot/backend/public;
    index  index.php index.html index.htm hostingstart.html;
    server_name localhost;

    location / {
        index  index.php index.html index.htm hostingstart.html;
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ [^/]\.php(/|$) {
        fastcgi_split_path_info ^(.+?\.php)(|/.*)$;
        fastcgi_pass 127.0.0.1:9000;
        include /etc/nginx/fastcgi_params;
        fastcgi_param HTTP_PROXY "";
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param QUERY_STRING $query_string;
        fastcgi_intercept_errors on;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 3600;
        fastcgi_read_timeout 3600;
    }
}
```

#Startup command a Azure:
cp /home/site/wwwroot/default /etc/nginx/sites-available/default && service nginx reload

## 6. Esquema visual: pipeline i estructura de carpetes ##
```text
quizforge (repo GitHub)
├─ backend/
│  ├─ src/
│  ├─ public/
│  └─ vendor/
├─ frontend/
│  ├─ src/
│  └─ dist/        # build de Vite (no es puja a GitHub)
├─ .github/
│  └─ workflows/
│     └─ main_quizforge-app.yml
├─ .gitignore
└─ .env            # no pujat a GitHub
```

# Pipeline de deploy:
```text
push a main
      │
      ▼
GitHub Actions Build Job
      │
      ├─ Composer install (backend)
      ├─ Preparar carpeta deploy/
      └─ Crear release.zip
      │
      ▼
Upload artifact
      │
      ▼
Deploy Job
      │
      ├─ Download artifact
      ├─ Login Azure
      └─ Deploy a quizforge-app (App Service)
```