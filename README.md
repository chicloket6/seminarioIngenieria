<h1 align="center">Sistema Hotel Honolulu</h1>

<p align="center" style="display:flex; flex-wrap:wrap; justify-content:center; align-items: center;">

<a style="width: 25%;"><img  src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg"></a>
<a style="width: 25%" href="https://backpackforlaravel.com/"><img style="" src="https://backpackforlaravel.com/presentation/img/backpack/logos/backpack_logo_color.png" alt="Backpack for Laravel"></a>
<a style="width: 25%" href="https://laravel-excel.com/" style="text-decoration: none">Laravel Excel</a>
<a style="width: 25%" href="https://github.com/Laravel-Backpack/PermissionManager" style="text-decoration: none">Permission Manager</a>
</p>

## Integrantes
-Erick Márquez
-Israel Martinez

## Acerca del Sistema Hotel Honolulu

Es un sistema que busca resolver el problema de reservaciones en el Hotel Honolulu. El sistema cuenta con las siguientes caracteristicas:

- CRUD para la la mayoría de las vistas en el sistema.
- Cuenta con un sistema de autoverificación de disponibilidad de habitaciones dependiendo de varias opciones dadas.
- Cuenta con 2 roles base, el primero es "Gerencia", este rol permite ver el sistema comolo veria un administrador de cualquier otro sistema, el segundo es "Recepción", este rol solo permite el acceso a reservaciones y clientes.
- El sistema es capaz de mandar correo electrónico (haciendo uso de Gmail smtp) una vez realizada una reservación con éxito al cliente. También si se modifica alguna cosa en su reservación, o si es eliminada dicha reservación.
- Para el "Gerente" se encuentra una vista llamada "Reportes", la cual contiene la información de las reservaciones y un botón que genera un archivo Excel y permite la descarga del mismo.

##Requisitos
- PHP 7.2.5 mínimo

##Instalación
- Clonar/Descargar el proyecto.
- Copiar contenido del archivo ".env.example" que se encuentra en la raíz del proyecto y pegarlo dentro de un archivo nuevo/creado llamado ".env" también en la raíz del proyecto.
- En la raíz del proyecto también se encuentra un archivo llamado "correo honolulu.txt", copiar solo las variables de entorno y reemplazarlas (solo reemplazar las de mailgun) por las que están en el ".env".
- En la terminal, dentro del proyecto, ejecutar el comando "composer install" para instalar todos los paquetes necesarios para el sistema.
- Ejecutar el comando "php artisan key:generate" en caso de que dentro del ".env" la variable "APP_KEY" se encuentre vacía.
- Crear una base de datos en su sistema local.
- En ".env" en las variable "DB_DATABASE" poner el nombre de la base de datos a la que accederá el sistema, "DB_USERNAME" el nombre del usuario en la misma base de datos, "DB_PASSWORD" en caso de que el usuaro  de la base tenga una contraseña ponerla ahí.
- Ejecutar en la terminal "php artisan migrate --seed"
- Ejecutar en la terminal "php artisan serve" y dentro del navegador de internet escribir localhost:8000, o si usa algún otro sistema de host virtual acceder mediante dicho host.
