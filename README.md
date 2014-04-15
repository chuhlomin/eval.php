eval.php
========

Simple script for checking small php code from web-page.

![](https://raw.githubusercontent.com/chuhlomin/eval.php/master/screenshot.png)

Requirements:
-------------

* php5 (5.5)
* php5-fpm
* nginx
* redis
* php5-redis
* php5-json
* bower (for js dependencies)
* npm

Installation: 
-------------

~~~
curl -sS https://getcomposer.org/installer | php
php composer.phar install
bower update
grunt install
~~~

Example of nginx config:
------------------------

~~~
server {
	set $bootstrap "index.php";
    charset utf-8;
    client_max_body_size 128M;

    listen 80;

    server_name eval.local;
    root        /var/www/eval.php/web/;
    index       $bootstrap;

    access_log /var/www/eval.php/access.log;
    error_log /var/www/eval.php/error.log;

    location / {
        try_files $uri $uri/ /$bootstrap?$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include /etc/nginx/fastcgi_params;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }

}
~~~
