# clone repo

git clone <repo-url>
cd book-be

# install dependencies

composer install

# copy environment

cp .env.example .env

# generate app key

php artisan key:generate

# generate JWT secret

php artisan jwt:secret

# buat database

CREATE DATABASE book_be;

# migrate tabel

php artisan migrate

# seed data awal

php artisan db:seed

# menjalankan server

php artisan serve
