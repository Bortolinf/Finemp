
# FINEMP - FINANCIAL CONTROL FOR COMPANIES

![Tela Site](https://github.com/Bortolinf/Finemp/blob/main/public/img/screen/screen_site.png?raw=true)
![Tela Dash](https://github.com/Bortolinf/Finemp/blob/main/public/img/screen/screen_dash.png?raw=true)
![Tela 1](https://github.com/Bortolinf/Finemp/blob/main/public/img/screen/screen1.png?raw=true)
![Tela 2](https://github.com/Bortolinf/Finemp/blob/main/public/img/screen/screen2.png?raw=true)


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

- Clone the repo and cd into it
- Create a folder 'framework' under de 'storage' folder
- Create the folders 'sessions', 'cache' and 'views' inside the framework folder
- composer install
- Rename or copy .env.example file to .env
- php artisan key:generate
- Set your database credentials in your .env file
- php artisan migrate
- php artisan serve
- Visit localhost:8000 in your browser

## How to Use :
- Register new company
- Fill 'filiais' table
- Fill 'plano de contas' table 
- Go to 'Gerenciador' >>> 'Configurações' and set the Incomes an Expenses account
- Now you can make the daily moviments
- Enjoy!

## Special Thanks:
- Professor Bonieky Lacerda (b7web)
- Stack Overflow
- Indian Guys on Youtube
