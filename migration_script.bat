@echo off
:: solo tre operazioni
php artisan migrate:rollback
php artisan migrate
php artisan db:seed