#!/bin/bash
cd ..
git remote update
git merge upstream/staging
composer install
php artisan migrate
