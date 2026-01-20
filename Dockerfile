# --- ETAPA 1: php-base (Arquitectura de Conectividad) ---
FROM debian:trixie-slim AS php-base

# Evitamos prompts interactivos durante la instalación
ENV DEBIAN_FRONTEND=noninteractive

# 1. Preparación y Axiomas de Time (Instalación en un solo bloque para optimizar)
RUN apt-get update && apt-get install -y \
    lsb-release ca-certificates curl gnupg2 git unzip \
    && curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && apt-get update && apt-get install -y \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-zip \
    php8.4-intl \
    php8.4-curl \
    php8.4-bcmath \
    php8.4-soap \
    php8.4-redis \
    nodejs npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configuración de FPM para red y Composer
RUN sed -i 's|listen = /run/php/php8.4-fpm.sock|listen = 9000|' /etc/php/8.4/fpm/pool.d/www.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# --- ETAPA 2: development (Entorno de Carlos) ---
FROM php-base AS development

ARG USER_ID=1000
ARG GROUP_ID=1000

# Creamos el usuario laravel para coincidir con tu UID de host
RUN groupadd -g ${GROUP_ID} laravel && \
    useradd -u ${USER_ID} -g laravel -m -s /bin/bash laravel

# Configuración de Logs a stderr y permisos de carpetas
RUN mkdir -p /run/php /var/log/php && \
    sed -i 's|error_log = /var/log/php8.4-fpm.log|error_log = /proc/self/fd/2|' /etc/php/8.4/fpm/php-fpm.conf && \
    chown -R laravel:laravel /var/www /var/log/php /run/php

USER laravel

# Exponemos el puerto de Vite por si acaso (aunque se maneja en el compose)
EXPOSE 5173 9000

# Flag -O para ignorar logs conflictivos en PHP 8.4
CMD ["php-fpm8.4", "-F", "-R", "-O"]
