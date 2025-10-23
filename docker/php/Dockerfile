FROM php:8.2-fpm

# PHP設定ファイルのコピー（任意）
COPY docker/php/php.ini /usr/local/etc/php/

# 必要なパッケージをインストール
RUN apt update && apt install -y \
  default-mysql-client \
  zlib1g-dev \
  libzip-dev \
  unzip \
  git \
  curl \
  libonig-dev \
  && docker-php-ext-install pdo_mysql mbstring zip

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

# 作業ディレクトリの設定
WORKDIR /var/www

# Laravelプロジェクトのコピー（ビルド時に必要なら）
COPY ../src /var/www

# パーミッション調整（任意）
RUN chown -R www-data:www-data /var/www \
  && chmod -R 755 /var/www
