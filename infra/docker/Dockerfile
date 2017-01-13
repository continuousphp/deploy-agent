FROM php:7.0-apache

# Set default system timezone
RUN ln -sf /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Install last update and php extension
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    vim \
    bzip2 \
    zip \
    libbz2-dev \
    libmcrypt-dev \
    libicu-dev \
    && docker-php-ext-configure mysqli \
    && docker-php-ext-install mysqli pdo_mysql bz2 mcrypt intl \
    && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \ 
    && mv composer.phar /usr/bin/composer  

# Enable Apache Rewrite module
RUN a2enmod rewrite

# Default Vhost for developement
COPY infra/docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Implement application
WORKDIR /var/app
COPY . /var/app/

RUN /usr/bin/composer update \
    && ./scripts/post-create-project

CMD ["apache2-foreground"]
