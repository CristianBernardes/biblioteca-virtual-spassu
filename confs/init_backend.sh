#!/bin/sh

cd /var/www/html/bibliotecaVirtual

# Verificar se o arquivo .env existe
if [ ! -f ".env" ]; then
    # Se o arquivo .env não existe, crie-o a partir do .env.example
    cp .env.example .env
fi

# Função para verificar se o MySQL está pronto
wait_for_mysql() {
    echo "Aguardando MySQL iniciar na porta 3306..."
    while ! nc -z biblioteca-mysql 3306; do
      sleep 3
    done
    echo "MySQL está pronto!"
}

# Verificar se as dependências do Composer estão instaladas
if [ -f "composer.lock" ] && [ -d "vendor" ]; then
    php artisan serve --host=0.0.0.0 --port=8021
else
    # Aguardar MySQL estar pronto
    wait_for_mysql

    # Executa o npm install e o npm run build
    npm install
    npm run build

    # Instalar dependências do Composer
    composer install || { echo "Falha ao instalar as dependências"; exit 1; }
    
    # Criar o link de storage
    php artisan storage:link || { echo "Falha ao criar o link de storage"; exit 1; }
    
    # Executar migrations e seeds
    php artisan migrate:fresh --seed || { echo "Falha ao rodar migrate:fresh --seed"; exit 1; }
    
    # Executar migrations para ambiente de testes
    php artisan migrate --env=testing || { echo "Falha ao rodar migrate --env=testing"; exit 1; }

    # Executar testes
    php artisan test || { echo "Falha ao rodar teste"; exit 1; }
    
    # Iniciar o servidor
    php artisan serve --host=0.0.0.0 --port=8021 || { echo "Falha ao iniciar o servidor"; exit 1; }
fi
