FROM phusion/baseimage:0.9.15

CMD ["/sbin/my_init"]

# install server tools
RUN apt-get update -y
RUN apt-get upgrade -y
RUN apt-get install -y apache2
RUN apt-get install -y mysql-server
RUN apt-get install -y php5
RUN apt-get install -y php5-mysql
RUN apt-get install -y php5-gd
RUN apt-get install -y php5-curl
RUN apt-get install -y git
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# server setting and start up scripts
COPY docker/etc/my_init.d/01_apache2.bash /etc/my_init.d/01_apache2.bash
COPY docker/etc/my_init.d/02_mysql.bash /etc/my_init.d/02_mysql.bash
COPY docker/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 755 /etc/my_init.d/01_apache2.bash
RUN chmod 755 /etc/my_init.d/02_mysql.bash

# add source files
ADD app /var/www/app
ADD src /var/www/src
ADD web /var/www/web
ADD composer.json /var/www/composer.json
ADD composer.lock /var/www/composer.lock

# install enhavo
RUN /bin/bash -c "/usr/bin/mysqld_safe &" && \
  sleep 5 && \
  mysql -u root -e "CREATE DATABASE enhavo" && \
  cd /var/www/ && \
  composer install --no-interaction && \
  app/console doctrine:schema:update --force && \
  app/console enhavo:install:fixtures && \
  app/console fos:user:create admin info@localhost.com admin --super-admin

# user rights
RUN usermod -u 1000 www-data && \
    cd /var/www/ && \
    chown www-data:www-data -R app/cache && \
    chmod 755 app/cache && \
    chown www-data:www-data -R app/logs && \
    chmod 755 app/logs && \
    chown www-data:www-data -R app/media && \
    chmod 755 app/media

WORKDIR /var/www

EXPOSE 80