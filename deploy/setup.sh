#!/usr/bin/env bash
#
# Instala y configura el portafolio en un servidor Ubuntu 24.04 limpio
# (pensado para Oracle Cloud Always Free).
#
# Uso:   bash setup.sh [dominio]
#   bash setup.sh                         -> sitio por http://IP-PUBLICA
#   bash setup.sh victorcabria.duckdns.org -> sitio por https://dominio
#
# Si existe ~/portafolio.sql (copia de tu base de datos local) se importa.
# Se puede volver a ejecutar sin perder datos: no recrea .env ni la base.

set -euo pipefail

DOMINIO="${1:-}"
REPO="https://github.com/VictorCabria/portafolio.git"
APP="/var/www/portafolio"
PHP="8.3"
USUARIO="$(whoami)"

paso() { printf '\n\033[1;34m==> %s\033[0m\n' "$1"; }

paso "Instalando paquetes del sistema"
sudo apt-get update -y
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y \
    nginx mysql-server git unzip curl openssl \
    php$PHP-fpm php$PHP-cli php$PHP-mysql php$PHP-mbstring php$PHP-xml php$PHP-curl \
    php$PHP-zip php$PHP-bcmath php$PHP-intl php$PHP-gd

if ! command -v composer >/dev/null; then
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
fi

if ! command -v node >/dev/null || [ "$(node -v | cut -d. -f1 | tr -d v)" -lt 20 ]; then
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi

# Las instancias pequeñas (1 GB) se quedan sin memoria al compilar; añade swap
if [ "$(free -m | awk '/Mem:/ {print $2}')" -lt 2000 ] && ! swapon --show | grep -q swapfile; then
    paso "Creando 2 GB de swap"
    sudo fallocate -l 2G /swapfile
    sudo chmod 600 /swapfile
    sudo mkswap /swapfile
    sudo swapon /swapfile
    echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab >/dev/null
fi

paso "Abriendo los puertos 80 y 443 en el firewall interno de Oracle"
for puerto in 80 443; do
    if ! sudo iptables -C INPUT -p tcp --dport "$puerto" -m state --state NEW -j ACCEPT 2>/dev/null; then
        sudo iptables -I INPUT 6 -p tcp --dport "$puerto" -m state --state NEW -j ACCEPT
    fi
done
command -v netfilter-persistent >/dev/null && sudo netfilter-persistent save || true

paso "Descargando el proyecto"
if [ -d "$APP/.git" ]; then
    git -C "$APP" pull --ff-only
else
    sudo mkdir -p "$APP"
    sudo chown "$USUARIO":www-data "$APP"
    git clone "$REPO" "$APP"
fi
cd "$APP"

if [ -n "$DOMINIO" ]; then URL="https://$DOMINIO"; else URL="http://$(curl -s https://ifconfig.me)"; fi

if [ ! -f .env ]; then
    paso "Creando base de datos y archivo .env"
    DB_PASS="$(openssl rand -hex 16)"
    sudo mysql <<SQL
CREATE DATABASE IF NOT EXISTS portafolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'portafolio'@'localhost' IDENTIFIED BY '$DB_PASS';
ALTER USER 'portafolio'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON portafolio.* TO 'portafolio'@'localhost';
FLUSH PRIVILEGES;
SQL

    cp .env.example .env
    sed -i '/^#\? \?DB_/d' .env
    sed -i \
        -e 's/^APP_NAME=.*/APP_NAME=Portafolio/' \
        -e 's/^APP_ENV=.*/APP_ENV=production/' \
        -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
        -e "s|^APP_URL=.*|APP_URL=$URL|" \
        -e 's/^LOG_LEVEL=.*/LOG_LEVEL=error/' \
        .env
    cat >> .env <<ENV

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portafolio
DB_USERNAME=portafolio
DB_PASSWORD=$DB_PASS
ENV
    if [ -n "$DOMINIO" ]; then echo "SESSION_SECURE_COOKIE=true" >> .env; fi
    chmod 640 .env
    NUEVA_INSTALACION=1
else
    sed -i "s|^APP_URL=.*|APP_URL=$URL|" .env
    if [ -n "$DOMINIO" ] && ! grep -q '^SESSION_SECURE_COOKIE' .env; then echo "SESSION_SECURE_COOKIE=true" >> .env; fi
    NUEVA_INSTALACION=0
fi

paso "Instalando dependencias y compilando estilos"
composer install --no-dev --optimize-autoloader --no-interaction
grep -q '^APP_KEY=base64' .env || php artisan key:generate --force
npm ci --no-audit --no-fund
npm run build

if [ "$NUEVA_INSTALACION" = 1 ] && [ -f "$HOME/portafolio.sql" ]; then
    paso "Importando tus datos desde ~/portafolio.sql"
    DB_PASS="$(grep '^DB_PASSWORD=' .env | cut -d= -f2-)"
    mysql -u portafolio -p"$DB_PASS" portafolio < "$HOME/portafolio.sql"
fi

paso "Preparando la base de datos"
php artisan migrate --force

if [ "$(php artisan tinker --execute 'echo App\Models\User::count();' | tail -n1)" = "0" ]; then
    paso "Creando tu usuario administrador"
    read -rp "Email de acceso: " ADMIN_EMAIL
    read -rsp "Contraseña (no se mostrará): " ADMIN_PASSWORD; echo
    ADMIN_EMAIL="$ADMIN_EMAIL" ADMIN_PASSWORD="$ADMIN_PASSWORD" php artisan tinker --execute \
        'App\Models\User::create(["name" => "Administrador", "email" => getenv("ADMIN_EMAIL"), "password" => getenv("ADMIN_PASSWORD")]);'
fi

php artisan storage:link 2>/dev/null || true
sudo chown -R "$USUARIO":www-data "$APP"
sudo chmod -R ug+rwX storage bootstrap/cache
php artisan optimize

paso "Configurando PHP y Nginx"
sudo sed -i -e 's/^upload_max_filesize.*/upload_max_filesize = 10M/' -e 's/^post_max_size.*/post_max_size = 12M/' /etc/php/$PHP/fpm/php.ini
sudo systemctl restart php$PHP-fpm

sudo tee /etc/nginx/sites-available/portafolio >/dev/null <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMINIO:-_};
    root $APP/public;
    index index.php;
    charset utf-8;
    client_max_body_size 12M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/run/php/php$PHP-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX
sudo ln -sf /etc/nginx/sites-available/portafolio /etc/nginx/sites-enabled/portafolio
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

if [ -n "$DOMINIO" ]; then
    paso "Activando HTTPS gratis con Let's Encrypt"
    sudo apt-get install -y certbot python3-certbot-nginx
    sudo certbot --nginx -d "$DOMINIO" --non-interactive --agree-tos --register-unsafely-without-email --redirect
fi

paso "¡Listo! Tu portafolio está en $URL  (panel: $URL/login)"
