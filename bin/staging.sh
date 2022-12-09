#!/bin/bash
DB_HOST=127.0.0.1
DB_NAME="motcom_staging"
DB_USER="motcom_staging"
DB_PASSOWRD="bAv&Tm!q]j[j"

echo "Importing DB. $DB_NAME"
echo "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;" | mysql -u$DB_USER -p$DB_PASSOWRD -h$DB_HOST
mysql -u$DB_USER -p$DB_PASSOWRD -D$DB_NAME -h$DB_HOST < ../init.sql
cp .env.staging .env

composer install
php artisan key:generate
php artisan storage:link
php  artisan migrate --seed

