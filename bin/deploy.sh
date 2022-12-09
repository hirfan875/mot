#!/bin/bash
composer install
cp .env.production .env
php artisan key:generate
php artisan migrate --seed
