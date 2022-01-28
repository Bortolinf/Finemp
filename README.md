
# FINEMP - FINANCIAL CONTROL FOR COMPANIES

## System created as study case, putting in pratice my laravel lessons and studies

## What it does?

- Create a account planning count (incomes and payments)
- Registry of daily moviments
- Generation of reports, graphics, and compairson with other months.

## Technical Details :
- Multi Tenancy structure: supports many clients (Single DB multitenancy);
- Multi User: supports many users in a Tenant;
- Users profiles and permissions: allows to restrict user access;

## Instalation :

Clone the repo and cd into it
composer install
Rename or copy .env.example file to .env
php artisan key:generate
Set your database credentials in your .env file
php artisan migrate
php artisan serve
Visit localhost:8000 in your browser

