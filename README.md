# resto_delivery

## How to use ?

### Step 1: Run Composer

Run: ` composer install` in your project's folder

### Step 2: Base de donnée

#### Creation de la base de données
`php bin/console doctrine:database:create`

#### Creation de la migration
`php bin/console make:migration`

#### Migration dans la base de donnée
`php bin/console doctrine:migrations:migrate`

### Step 3: Insertion des données

lancer le fichier 'restaurent_delivery.sql' dans phpMyAdmin pour faire des tests sur le site

