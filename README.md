# Set up OTWorlds

1. Clone repo
2. Install *Laravel* dependencies (requires *composer*) `composer install`
You may have to modify your php.ini and set `opcache.fast_shutdown=0` if you receive an error about `zend_mm_heap`.
3. Install *grunt* (requires *npm*) `npm install --save-dev`
4. Configure `app/config/app.php` to your liking, especially the crypto-key
5. Configure `app/config/database.php`
6. Configure `app/config/facebook.php`
7. Run `php artisan migrate`

Optionally, you can populate the database with some dummy data by running `php artisan otworlds:populate`


# Deploying to production

Either run `deploy.sh` or the following:

1. Checkout unminified HTML templates:  `git checkout -- app/views/`
2. Minify the JS and modify HTML references: `grunt build`
