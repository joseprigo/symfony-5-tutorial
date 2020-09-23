sudo rm /var/www/micropost && \
sudo ln -s /var/www/micropost_current /var/www/micropost && \
cd /var/www/micropost && \
echo $APP_ENV && \
echo $DATABASE_URL  &&\
sudo APP_ENV=$APP_ENV DATABASE_URL=mysql://root:root@127.0.0.1/micro-post php bin/console doctrine:migrations:migrate --no-interaction && \
sudo chown -R www-data:www-data /var/www/micropost_current && \
sudo chown -h www-data:www-data /var/www/micropost && \
sudo service apache2 restart