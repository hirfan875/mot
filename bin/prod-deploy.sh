#!/bin/bash
DESTINATION=~/domains/mallofturkeya.com

cd ~/deployments/mall-of-turkey
git remote update
git merge origin/master

cp mot/app $DESTINATION -r
cp mot/bootstrap $DESTINATION -r
cp mot/config $DESTINATION -r
cp mot/resources $DESTINATION -r
cp mot/database $DESTINATION -r
cp mot/routes $DESTINATION/routes -r

cp mot/public/* $DESTINATION/public_html -r
cp mot/public/.htaccess $DESTINATION/public_html
cp mot/storage $DESTINATION -r

cp mot/artisan $DESTINATION
cp mot/composer.lock $DESTINATION
cp mot/composer.json $DESTINATION

cd $DESTINATION
composer install --no-dev
php artisan migrate
