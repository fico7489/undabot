FROM php:8.2.16-fpm-alpine3.19

#set www-data user
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:1000:/' /etc/passwd

