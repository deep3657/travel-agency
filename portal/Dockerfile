FROM php:8.2-cli

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl unzip ca-certificates \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libonig-dev libxml2-dev libzip-dev libicu-dev \
        zlib1g-dev libwebp-dev \
        poppler-utils \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN composer dump-autoload --optimize --no-dev \
    && npm run build \
    && rm -rf node_modules \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force \
    && (php artisan storage:link || true) \
    && php artisan db:seed --class=RolesAndPermissionsSeeder --force \
    && php artisan db:seed --class=AgencySettingsSeeder --force \
    && php artisan tinker --execute="use App\Models\User; \$a = User::firstOrCreate(['email'=>'admin@maruti.test'],['name'=>'Maruti Admin','phone'=>'+91 9000000001','password'=>'password','user_type'=>User::TYPE_STAFF,'is_active'=>true,'email_verified_at'=>now()]); if(!\$a->hasRole('admin')) \$a->assignRole('admin'); \$g = User::firstOrCreate(['email'=>'agent@maruti.test'],['name'=>'Maruti Agent','phone'=>'+91 9000000002','password'=>'password','user_type'=>User::TYPE_STAFF,'is_active'=>true,'email_verified_at'=>now()]); if(!\$g->hasRole('agent')) \$g->assignRole('agent'); echo 'BOOTSTRAP_USERS_OK admin='.\$a->email.' agent='.\$g->email.PHP_EOL;" \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && (php artisan queue:work --tries=3 --sleep=3 --max-time=3600 &) \
    && PHP_CLI_SERVER_WORKERS=4 php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
