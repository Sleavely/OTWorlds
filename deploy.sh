#!/bin/bash

# Make sure we're on the right branch
git checkout master
# Reset the views that we minified last deployment
git checkout -- app/views/
# Now download the latest and greatest
git pull --no-edit origin master

# Update Composer itself and then any dependencies from composer.lock
composer self-update
composer install

# Run any user-defined upgrades
php artisan migrate

# Minify and Uglify
grunt build
