#!/usr/bin/env bash
# Atualiza a aplicação no EC2 a partir do repositório Git.
# Uso (dentro da pasta do projeto no servidor):  bash deploy/deploy.sh
set -euo pipefail

cd "$(dirname "$0")/.."

echo "==> Baixando a versão mais recente do repositório"
git pull --ff-only

echo "==> Entrando em modo de manutenção"
php artisan down --retry=15 || true

echo "==> Instalando dependências PHP (sem pacotes de desenvolvimento)"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "==> Instalando dependências do front-end e gerando o build"
npm ci --no-audit --no-fund
npm run build

echo "==> Executando migrations no banco (RDS)"
php artisan migrate --force

echo "==> Otimizando caches de configuração, rotas e views"
php artisan optimize

echo "==> Ajustando permissões"
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "==> Recarregando o PHP-FPM"
sudo systemctl reload php8.4-fpm

php artisan up
echo "==> Deploy concluído!"
