# Use a imagem baseada em Alpine com PHP 8.3
FROM php:8.3-fpm-alpine

# Arguments
ARG user=biblioteca
ARG uid=1000

# Atualize os pacotes do sistema operacional e instale os pacotes necessários
RUN apk update && \
    apk upgrade && \
    apk add --no-cache \
    nodejs \
    npm \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    g++ \
    lame && \
    rm -rf /var/cache/apk/*

# Instale as extensões PHP necessárias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install intl && \
    docker-php-ext-install zip

# Instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#/usr/local/etc/php/conf.
RUN adduser --uid $uid --home /home/$user -s /bin/sh -D $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o Diretório do projeto para dentro da pasta do docker
COPY /../../../../projects/bibliotecaVirtual /var/www/html/bibliotecaVirtual

# Define o usuário como root para fazer instalações e manipulações de arquivos
USER root

# Defina o timezone do sistema como America/Sao_Paulo
ENV TZ=America/Sao_Paulo
RUN apk add --no-cache tzdata && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone && \
    apk del tzdata

# Adiciona as configurações ao arquivo custom.ini
RUN echo "memory_limit = 2048M" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize = 80M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 80M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "date.timezone = America/Sao_Paulo" >> /usr/local/etc/php/conf.d/custom.ini

# Copie o script de inicialização para o contêiner
COPY /../../../../confs/init_backend.sh /usr/local/bin/init_backend.sh

# Torna o script executável
RUN chmod +x /usr/local/bin/init_backend.sh

USER $user

# Define o comando principal do contêiner para executar o script de inicialização
CMD ["/usr/local/bin/init_backend.sh"]