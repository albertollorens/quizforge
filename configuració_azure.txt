CONFIGURACIÓ APP quizforge (AZURE)

Entorn: azure App Service
SO: Linux
Servidor: Nginx	(configurar document root en fitxer de configuració default)
		  PHP 8.4
		  MySQL 8.4.7

1.- Crear App Service + Base de datos
   ┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
	Grup de recursos: quizeforgegroup
		APP			=> quizforge-app
		Zona 		=> Spain Central
		Subscripció => Azure for Students
		S.O.		=> Linux
		Server		=> quizforge-server
		BD			=> quizforge-db		Punto de conexión: quizforge-server.mysql.database.azure.com
   └─────────────────────────────────────────────────────────────────────────────────────────────────┘

2.- Configurar deploy quizforge-app:
   ┌─────────────────────────────────────────────────────────────────────────┐
	Centre d'implementació=>Configuració
		Origen: Github ->alblloboi (connectar amb compte d'usuari)
		Organització: albertollorens (creada a partir del compte d'usuari)
		Repositori:	quizforge
		Rama: main
		
		Compilació
			Proveïdor => Acciones de Github
			Pila...   => PHP
			Versió	  => 8.4
   └─────────────────────────────────────────────────────────────────────────┘

3.- Workflow (pipeline) de Github Actions per a desplegament automàtic en Azure (.github/workflows/main_quizforge-app.yml):
   ┌───────────────────────────────────────────────────────────────────────────────────────────────────────────┐
	# Docs for the Azure Web Apps Deploy action: https://github.com/Azure/webapps-deploy
	# More GitHub Actions for Azure: https://github.com/Azure/actions

	name: Build and deploy PHP app to Azure Web App - quizforge-app

	on:
	  push:
		branches:
		  - main
	  workflow_dispatch:

	jobs:
	  build:
		runs-on: ubuntu-latest
		permissions:
		  contents: read #This is required for actions/checkout

		steps:
		  # 1.- Checkout del codi
		  - uses: actions/checkout@v4

		  # 2.- Configurar PHP per al backend
		  - name: Setup PHP
			uses: shivammathur/setup-php@v2
			with:          
			  php-version: '8.4'

		  # 3.- Instal·lar dependèndies PHP
		  - name: Install backend dependencies
			working-directory: ./backend
			run: |
			  composer install --no-dev --optimize-autoloader

		  # 4.- Preparar carpeta de deploy
		  - name: Prepare deployment package
			run: |
			  rm -rf deploy
			  mkdir deploy
			  # Copiem directament el backend a deploy
			  cp -r backend deploy/
		  
		  # 5.- Create ZIP per a Azure
		  - name: Create deployment zip
			run: |
			  cd deploy
			  zip -r ../release.zip .

		  # 6️.- Upload artifact
		  - name: Upload artifact
			uses: actions/upload-artifact@v4
			with:
			  name: release
			  path: release.zip

	  deploy:
		runs-on: ubuntu-latest
		needs: build
		permissions:
		  id-token: write #This is required for requesting the JWT
		  contents: read #This is required for actions/checkout

		steps:
		  # 1.- Download artifact
		  - name: Download artifact
			uses: actions/download-artifact@v4
			with:
			  name: release

		  # 2.- Login a Azure
		  - name: Login to Azure
			uses: azure/login@v2
			with:
			  client-id: ${{ secrets.AZUREAPPSERVICE_CLIENTID_81D8FB210EED4A7F8BCA025B99BEF0D3 }}
			  tenant-id: ${{ secrets.AZUREAPPSERVICE_TENANTID_1CCE4FABA6724660BA3FF7775A65E71C }}
			  subscription-id: ${{ secrets.AZUREAPPSERVICE_SUBSCRIPTIONID_9BEB573D34B14D4BBE028AE5C7CBF505 }}

		  # 3.- Deploy a Azure App Service
		  - name: 'Deploy to Azure Web App'
			uses: azure/webapps-deploy@v3
			id: deploy-to-webapp
			with:
			  app-name: 'quizforge-app'
			  package: release.zip
			  clean: false
   └───────────────────────────────────────────────────────────────────────────────────────────────────────────┘
   =>Explicació ràpida:
		1.- Build frontend: compila Vue amb Vite i genera dist/.
		2.- Preparar deploy:
			deploy/backend/ → backend complet amb Slim, vendor/, src/ i public/.
			deploy/public/ → build de Vue.
		3.- Upload/download artifact: permet separar build i deploy en jobs diferents.
		4.- Deploy: Azure Web App rep només la carpeta preparada (deploy/).
		5.- Secrets necessaris a GitHub: 
			AZUREAPPSERVICE_CLIENTID
			AZUREAPPSERVICE_TENANTID
			AZUREAPPSERVICE_SUBSCRIPTIONID
   
4.- Configurar .gitignore:
┌──────────────────────┐
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
└──────────────────────┘

5.- A Azure, configura backend/public/ com a document root si vols que Slim interprete /api correctament. Per a açò definir un fitxer
de configuració de NGINX que es copiarà al dir /etc/nginx/sites-available/default cada cop que engegue el servidor:
   ┌─────────────────────────────────────────────────────────────────────────────┐
	/home/site/wwwroot/default:
	server {
		listen 8080;
		root  /home/site/wwwroot/backend/public;
		index  index.php index.html index.htm hostingstart.html;
		server_name localhost;

		location / {
			index  index.php index.html index.htm hostingstart.html;
			try_files $uri $uri/ /index.php?$args; # changed for Slim
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
   └─────────────────────────────────────────────────────────────────────────────┘

Startup command:
   ┌───────────────────────────────────────────────────────────────────────────────────────────┐
	cp /home/site/wwwroot/default /etc/nginx/sites-available/default && service nginx reload
   └───────────────────────────────────────────────────────────────────────────────────────────┘