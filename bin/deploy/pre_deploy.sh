sudo rm -Rf /var/www/micropost_old && \
sudo cp -R /var/www/micropost_current /var/www/micropost_old/ && \
sudo rm /var/www/micropost && \
# need to find another solution instead of removing current directory since it deletes all the user uploaded files
sudo rm -R /var/www/micropost_current && \
# Create symlink to older version && \
sudo ln -s /var/www/micropost_old /var/www/micropost