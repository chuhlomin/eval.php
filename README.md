eval.php
========

Simple script for checking small php code from web-page.

Example of nginx config:
~~~
server {
    set $bootstrap "index.php";
    charset utf-8;
    client_max_body_size 128M;

    listen 80;

    server_name eval.dev.local;
    root        'path/to/directory';
    index       $bootstrap;

    location / {
        try_files $uri $uri/ /$bootstrap?$args;
    }

    location ~ \.php$ {
        include fastcgi.conf;
        fastcgi_pass unix:/opt/local/var/run/php-fpm.sock;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}
~~~