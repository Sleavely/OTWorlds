#!/bin/bash

git checkout master
git checkout -- app/views/
git pull --no-edit origin master
composer dump-autoload
php artisan migrate
grunt build
