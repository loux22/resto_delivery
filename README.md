# resto_delivery

## How to use ?

### Step 1: Run Composer

Run: ` composer install` in your project's folder

### Step 2: Database

#### Create database
`php bin/console doctrine:database:create`

#### Create migration
`php bin/console make:migration`

#### Migration in database
`php bin/console doctrine:migrations:migrate`

### Step 3: Data insertion

launch file `'resto_delivery/delivery/restaurent_delivery.sql'` in phpMyAdmin to do site tests

