# resto_delivery

## How to use ?

### Step 1: Run Composer

Run: ` composer install` in your project's folder

### Step 2: Base de donnée

`php bin/console doctrine:database:create`
`php bin/console make:migration`
`php bin/console doctrine:migrations:migrate`
`lancer le fichier 'restaurent_delivery (1).sql' dans phpMyAdmin pour faire des tests sur le site`
