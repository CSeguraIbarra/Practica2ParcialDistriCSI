## habilitar apis 
php artisan install:api

## hacer correr las migraciones

php artisan migrate

## Crear modelo y migracion, seeder, factory y controller para api

php artisan make:model Libro -msfc --api

## Luego de crear el modelo

### en la migracion colocar los campos

## CRear los seeders 
no olivdar enlazar los seeders al databaseseeder

## hacer correr las migraciones y los seeders

php artisan migrate:fresh --seed

## hacer correr la api
php artisan serve

# Instalar JWT
# Dara seguridad a la api  vamos firebase/php-jwt
composer require firebase/php-jwt

# Crear el midleware 
php artisan make:middleware JwtMiddleware

# Escribir codigo del midleware

# Proteger las rutasincluyendo 
->middleware(JwtMiddleware::class)

# crear el controlador para login
php artisan make:controller LoginController