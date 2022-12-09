#!/bin/bash

red=`tput setaf 1`
green=`tput setaf 2`
reset=`tput sgr0`

# echo "${red}Please use update. We have seeder that may be disrupted.${reset}" && exit 0
PWD=`pwd`
cd /var/www
SKIP_INSTALL=false

echo "${green}Importing $DB_NAME DB.${reset}"
echo "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;" | mysql -uroot -psquarehouse -h$DB_HOST
mysql -uroot -psquarehouse -D$DB_NAME -h$DB_HOST < init.sql
echo "${green}Import DB $DB_NAME Complete. ${reset}"
echo "${green}Importing $TEST_DB_NAME DB.${reset}"
echo "DROP DATABASE IF EXISTS $TEST_DB_NAME; CREATE DATABASE $TEST_DB_NAME;" | mysql -uroot -psquarehouse -h$TEST_DB_HOST
mysql -uroot -psquarehouse -D$TEST_DB_NAME -h$TEST_DB_HOST  < init.sql
echo "${green}Import DB $TEST_DB_NAME Complete.${reset}"
cd $APP_PATH
cp .env.example .env
#if [ ! $SKIP_INSTALL ] ; then
  npm install && composer install
  export SKIP_INSTALL=true
  php artisan storage:link
  mkdir -p public/storage/original
#fi
php artisan key:generate
php artisan migrate --seed
echo "${green}Starting Tests Migration${reset}"
php artisan migrate --seed --database=test-mysql --env=testing
echo "${green}Starting Tests${reset}"
vendor/bin/phpunit
echo "${green}All Done taking you back to ${reset} $PWD"
cd $PWD
