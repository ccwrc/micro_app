FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-scripts --no-autoloader

ENV APP_ENV=prod
ENV APP_SECRET=your_app_secret
# //todo failed: No address associated with hostname
ENV DATABASE_URL=mysql://project:project@mysql57:3307/project

EXPOSE 9000

RUN composer dump-autoload --optimize
RUN php bin/console doctrine:migrations:migrate --no-interaction
# //todo untested (failed: No address associated with hostname)
RUN php php bin/console doctrine:fixtures:load -y

CMD ["php-fpm"]