# habiliar api en laravel
php artisan install:api
# modificar el archivo .env
copiar el contenido de .env.example a .env
# modificar el archivo .env para configurar la base de datos
DB_CONNECTION=mysql
DB_HOST=    
DB_PORT=3306
DB_DATABASE=bd_agenda_api
DB_USERNAME=root
DB_PASSWORD=

# crear  key
php artisan key:generate



## Crear un modelo, su controlador, su migracion, su seeder y su factory
php artisan make:model Persona -mcrsf --api

## Ejecutar migraciones y llenar la base de datos con datos de prueba
php artisan migrate --seed  
## borrar la base de datos, volver a crearla y llenarla con datos de prueba
php artisan migrate:fresh --seed  


## Instalar libreria
composer require firebase/php-jwt

# Escribir codigo en el archivo .env
JWT_SECRET=secret
JWT_ALGORITHM=HS256

# Crear el midleware 
php artisan make:middleware JwtMiddleware

# Escribir codigo del midleware
 escribir elcodigo para leer el token  
# Proteger las rutasincluyendo a las que sean necesarias
->middleware(JwtMiddleware::class)
# crear un Controlador para el login
php artinas make:controller LoginController


# Grapql

## instalar libreria
composer require nuwave/lighthouse

## publicarla
php artisan vendor:publish --tag=lighthouse-schema

## modificar el archivo schema.graphql para crear el tipo Query y Mutation
type Query {
    personas: [Persona]
}   
type Mutation {
    createPersona(nombre: String!, email: String!): Persona
}

## playgroud 
composer require mll-lab/laravel-graphql-playground



