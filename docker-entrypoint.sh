#!/bin/bash

chown -R www-data:www-data /var/www/html
chmod 777 -R /var/www/html/storage
#composer install
#composer dump-autoload
php artisan key:generate
php artisan queue:table
#php artisan telescope:install
php artisan migrate
#php artisan db:seed
php artisan storage:link
#php artisan passport:install
php artisan optimize:clear

# Rebuild assets on container start (optional, better in Dockerfile)
#npm install
#npm run build # or npm run de
php artisan queue:work &

echo 'upload_max_filesize = 10M' >> /usr/local/etc/php/conf.d/max.ini
echo '* * * * * /usr/local/bin/php  /var/www/html/artisan schedule:run' >> /etc/cron.d/schedule-run

exec "$@"
