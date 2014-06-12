# Set up OTWorlds

1. Clone repo
2. Install *Laravel* dependencies (requires *composer*) `composer install`  
You may have to modify your php.ini and set `opcache.fast_shutdown=0` if you receive an error about `zend_mm_heap`.
3. Make sure dependencies like Facebook SDK is included by running `composer update`
4. Install *grunt* (requires *npm*) `npm install --save-dev`
5. Generate a unique crypto-key with `php artisan key:generate`
6. Configure `app/config/app.php` to your liking
7. Create a database and configure `app/config/database.php`
8. Configure `app/config/facebook.php`
9. Run `php artisan migrate`

You may need to set permissions on the storage folder: `chown -R www-data:www-data app/storage/`. The *user:group* combo may be different on your system.

Optionally, you can populate the database with some dummy data by running `php artisan db:seed`

Sometimes you may need to run `composer dump-autoload` after updating. This applies when classes have moved or been added.

# Deploying to production

Either run `deploy.sh` or the following:

1. Checkout unminified HTML templates:  `git checkout -- app/views/`
2. Minify the JS and modify HTML references: `grunt build`
