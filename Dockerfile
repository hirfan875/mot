FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    wget \
    curl \
    git \
	procps \
	sudo \
	default-mysql-client \
	gnupg2 \
	dnsutils \
    curl

RUN apt-get install -y  dos2unix
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN rm -rf /var/lib/apt/lists/ && curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install nodejs -y

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd  --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
