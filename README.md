# Set up OTWorlds

1. Clone repo
2. Install *Laravel* dependencies (requires *composer*) `composer install`  
You may have to modify your php.ini and set `opcache.fast_shutdown=0` if you receive an error about `zend_mm_heap`.
3. Make sure dependencies like Facebook SDK is included by running `composer update`
4. Install *grunt* (requires *npm*) `npm install --save-dev`
5. Configure `app/config/app.php` to your liking, especially the crypto-key
6. Create a database and configure `app/config/database.php`
7. Configure `app/config/facebook.php`
8. Run `php artisan migrate`

Optionally, you can populate the database with some dummy data by running `php artisan db:seed`


# Deploying to production

Either run `deploy.sh` or the following:

1. Checkout unminified HTML templates:  `git checkout -- app/views/`
2. Minify the JS and modify HTML references: `grunt build`
