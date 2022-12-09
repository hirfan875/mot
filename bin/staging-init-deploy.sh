#!/bin/bash
echo "This script will overwrite all files in staging area. Uploaded images and documents will be erased."

echo -n "Should we proceed (y/n)? "
read answer


if [ "$answer" != "${answer#[Yy]}" ] ;then
    echo "Proceeding "
else
    echo "Quitting"
    exit
fi


DESTINATION=~/domains/mallofturkeya.com/public_html/staging
rm -rf $DESTINATION/*


cd ~/deployments/mall-of-turkey
git remote update
git merge origin/master


echo "Copying files"
cp mot/app $DESTINATION -r
cp mot/bootstrap $DESTINATION -r
cp mot/config $DESTINATION -r
cp mot/resources $DESTINATION -r
cp mot/database $DESTINATION -r
cp mot/routes $DESTINATION/routes -r

echo "Copying public "
cp mot/public $DESTINATION/public -r
cp mot/public/.htaccess $DESTINATION/public
cp mot/storage $DESTINATION -r

cp mot/artisan $DESTINATION
cp mot/composer.lock $DESTINATION
cp mot/composer.json $DESTINATION

cd $DESTINATION
cp ~/staging-env.example .env
composer install --no-dev
php artisan storage:link

php artisan migrate

cd $DESTINATION/public/storage
tar -xzf ~/storage.tar.gz

