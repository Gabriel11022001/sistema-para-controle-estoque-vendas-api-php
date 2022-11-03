FROM php:8.1-apache

RUN apt-get update -y && docker-php-ext-install pdo pdo_mysql
