#!/bin/bash

red=`tput setaf 1`
green=`tput setaf 2`
reset=`tput sgr0`

# echo "${red}Please use update. We have seeder that may be disrupted.${reset}" && exit 0
PWD=`pwd`
cd /var/www
SKIP_INSTALL=false

echo "${green}Importing $TEST_DB_NAME DB.${reset}"
echo "DROP DATABASE IF EXISTS $TEST_DB_NAME; CREATE DATABASE $TEST_DB_NAME;" | mysql -uroot -psquarehouse -h$TEST_DB_HOST
mysql -uroot -psquarehouse -D$TEST_DB_NAME -h$TEST_DB_HOST  < init.sql
echo "${green}Import DB $TEST_DB_NAME Complete.${reset}"
cd $APP_PATH
echo "${green}Starting Tests Migration${reset}"
php artisan migrate --seed --database=test-mysql --env=testing
echo "${green}Starting Tests${reset}"
vendor/bin/phpunit
####################################################################
# following are helpful . .. should add command line flag for these
####################################################################

#vendor/bin/phpunit tests/Unit/Service/FilterProductsServiceTest.php  --stop-on-failure --filter=testBasic
#echo "select count(*) from category_product;" | mysql -uroot -psquarehouse -D$TEST_DB_NAME -h$TEST_DB_HOST
#echo "select count(*) from products;"  | mysql -uroot -psquarehouse -D$TEST_DB_NAME -h$TEST_DB_HOST

echo "${green}All Done taking you back to ${reset} $PWD"
cd $PWD
