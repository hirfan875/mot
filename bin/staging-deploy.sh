#!/bin/bash
DESTINATION=~/domains/mallofturkeya.com/public_html/staging

cd ~/deployments/mall-of-turkey
git remote update
git merge origin/master

echo "Copying app"
cp mot/app $DESTINATION -r
echo "Copying bootstrap"
cp mot/bootstrap $DESTINATION -r
echo "Copying config"
cp mot/config $DESTINATION -r
echo "Copying resources"
cp mot/resources $DESTINATION -r
echo "Copying databases"
cp mot/database $DESTINATION -r
echo "Copying routes"
cp mot/routes $DESTINATION/routes -r
echo "Copying public"
cp mot/public $DESTINATION/public -r
echo "Copying htaccess"
cp mot/public/.htaccess $DESTINATION/public
echo "Copying storage"
cp mot/storage $DESTINATION -r

cp mot/artisan $DESTINATION
cp mot/composer.lock $DESTINATION
cp mot/composer.json $DESTINATION

cd $DESTINATION
composer install --no-dev


# get node modules from desireweb
php artisan migrate
