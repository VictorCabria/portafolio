#!/usr/bin/env bash
#
# Publica en el servidor los últimos cambios subidos a GitHub.
# Uso (en el servidor):  bash /var/www/portafolio/deploy/actualizar.sh

set -euo pipefail

cd /var/www/portafolio

php artisan down || true
trap 'php artisan up' EXIT

git pull --ff-only
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --no-audit --no-fund
npm run build
php artisan migrate --force
php artisan optimize

echo "Actualización completada."
