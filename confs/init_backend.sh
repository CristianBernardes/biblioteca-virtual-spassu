#!/bin/sh

cd /var/www/html/bibliotecaVirtual

# Verificar se o arquivo .env existe
if [ ! -f ".env" ]; then
    # Se o arquivo .env não existe, crie-o a partir do .env.example
    cp .env.example .env
fi

# Verificar se as dependências do Composer estão instaladas
if [ -f "composer.lock" ] && [ -d "vendor" ]; then
    php artisan serve --host=0.0.0.0 --port=8021
else
    composer install && php artisan storage:link && && php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8021
fi